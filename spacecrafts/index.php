<?php
/*
 Script d'export json des communes de France depuis la base de données de l'Insee.
 Développé par Franck Canonne.
 franck.canonne@gmail.com
 https://le7.net
 https://qr.bzh
 */

class Constantes
{
    //DATABASE DETAILS
    static $DB_SERVER="db5006980713.hosting-data.io";
    static $DB_NAME="dbs5763769";
    static $USERNAME="dbu1571656";
    static $PASSWORD="S#d93jtd29#l";

    //STATEMENTS
    static $SQL_SELECT_ALL="SELECT * FROM insee";
}

class Insee
{
    public function connect()
    {
        $con=new mysqli(Constantes::$DB_SERVER,Constantes::$USERNAME,Constantes::$PASSWORD,Constantes::$DB_NAME);
        if($con->connect_error)
        {
            // echo "Impossible de se connecter"; - For debug
            return null;
        }else
        {
            //echo "Connecté"; - For debug
            return $con;
        }
    }

    public function select()
    {
        $con=$this->connect();
        if($con != null)
        {
            $result=$con->query(Constantes::$SQL_SELECT_ALL);
            if($result->num_rows>0)
            {
                $insee=array();
                while($row=$result->fetch_array())
                {
                    array_push($insee, array("id"=>$row['code_commune_INSEE'],"cp"=>$row['code_postal'],
                    "nom"=>$row['nom_commune_complet'],"departement"=>$row['nom_departement'],
                    "latitude"=>$row['latitude'],"longitude"=>$row['longitude'],"region"=>$row['nom_region']));
                }
                print(json_encode(array_reverse($insee), JSON_UNESCAPED_UNICODE)); // ", JSON_UNESCAPED_UNICODE" pour avoir de l'utf8
            }else
            {
                print(json_encode(array("Erreur : aucune donnée trouvée dans la base. ")));
            }
            $con->close();

        }else{
            print(json_encode(array("Erreur : connexion à la base de données impossible.")));
        }
    }
}

$insee=new Insee();
$insee->select();

//fin

