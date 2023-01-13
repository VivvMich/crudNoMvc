<?php

namespace App\Crud;
class BDD
{
    private string $host;
    private string $dbName;
    private string $user;
    private string $password;
    public static $pdo;

    /**
     * @param string $host
     * @param string $dbName
     * @param string $user
     * @param string $password
     */
    public function __construct(string $host, string $dbName, string $user, string $password)
    {
        $this->host = $host;
        $this->dbName = $dbName;
        $this->user = $user;
        $this->password = $password;
    }

    public function initBdd(){

        BDD::$pdo = new \PDO($this->host . "; " . $this->dbName, $this->user, $this->password);
        BDD::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function select($classString, int $id){  // HYDRATATION

        $request = BDD::$pdo->query("SELECT * FROM $classString WHERE id=$id");
        return $request->fetchObject('App\\Crud\\' . ucfirst($classString));
    }

    public function selectAll($classString){
        $request = BDD::$pdo->query("SELECT * FROM $classString");
        return $request->fetchall(\PDO::FETCH_CLASS, 'App\\Crud\\' . ucfirst($classString));
    }

    public function update($obj){

        $ref = new \ReflectionClass($obj);
        $data = [];
        $id = $obj->getId();

        $tableName = strtolower($ref->getShortName());
        // Création dynamique du SQL
        $sql = "UPDATE $tableName SET "; // UDPATE user SET

        $properties  = $ref->getProperties();


        foreach ($properties as $property){

            if ($property->getName() !== "id" ){
                $sql .= $property->getName() . "=?, ";  //  UPDATE user SET username=?, paswword=?, etc...

                // Tableau de données
                $method = 'get' . ucfirst($property->getName()); // getUsername... getPassword... ect ..

                if (method_exists($obj, $method)){
                    $CheckData = $obj->$method();
                    if (is_array($CheckData)){
                        $CheckData = implode(",", $CheckData );
                        $data[] = $CheckData;
                    }
                    else{
                        $data[] = $CheckData; // Patrick, etc
                    }
                }
            }
        }
        $data[] = $id;
        $sql = substr($sql,0,-2 ); // J'enlève la dernière virgule avant le WHERE.
        $sql .= " WHERE id=?"; // UPDATE user SET username=?, paswword=?, etc... WHERE id=?
        $req = BDD::$pdo->prepare($sql);
        if($req->execute($data)){
            $message = "Enregistrement réusis.";
            header("Location:http://localhost/crud/crudNoMvc/?succes=$message");
            exit;
        }
        else{
            $message = "Erreur pendant l'enregistrement";
            header("Location:http://localhost/crud/crudNoMvc/?error=$message");
            exit;
        }

    }

    public function insert($obj)
    {

        $ref = new \ReflectionClass($obj); // Ca me retourne un objet qui contient l'objet injecté et me permettra d'avoir des informations sur celui-ci.
        $data = []; // tableau qui contiendra les valeur des getters de l'objet qui est injecter dans la methode insert ( cela n'a rien avoir avec le reflection class )
        $valueString = "(";

        $tableName = strtolower($ref->getShortName()); // USER devient user
        // Création dynamique du SQL
        $sql = "INSERT INTO $tableName ("; // INSERT INTO user (
        $properties = $ref->getProperties(); // retourne le tableau de toutes les propriétés de User ( exemple : username, password etc .. )
        foreach ($properties as $property) {
            if ( $property->getName() != 'id') {
                $sql .= $property->getName() . ","; // username, password, ect...
                $valueString .= "?,"; // (?,?, ect ...

                // Tableau de données
                $method = 'get' . ucfirst($property->getName()); // get . ucfirst(username)  =>  get . Username => getUsername

                if (method_exists($obj, $method)) {
                    $CheckData = $obj->$method(); // getUsername();
                    if (is_array($CheckData)){
                        $CheckData = implode(",", $CheckData );
                        $data[] = $CheckData;
                    }
                    else{
                        $data[] = $CheckData;
                    }
                }
            }
        }

        // INSERT INTO user (username, password, job, $country, sex, language, leisure,
        $sql = substr_replace($sql, ')', -1); // Je remplace la dernière par ) pour fermer.
        // INSERT INTO user (username, password, job, $country, sex, language, leisure)

        //  // (?,?,?,?,?,?,?,
        $valueString = substr_replace($valueString, ')', -1); // Je remplace la dernière par ) pour fermer.
        // (?,?,?,?,?,?,?)


        $sql .= " VALUE " . $valueString;
        // INSERT INTO user (username, password, job, $country, sex, language, leisure) VALUE (?,?,?,?,?,?,?)
        $req = BDD::$pdo->prepare($sql);
        if($req->execute($data)){
            $message = "Enregistrement réusis.";
            header("Location:http://localhost/crud/crudNoMvc/?succes=$message");
            exit;
        }
        else{
            $message = "Erreur pendant l'enregistrement";
            header("Location:http://localhost/crud/crudNoMvc/?error=$message");
            exit;
        }
    }

    public function delete($obj){

        $ref = new \ReflectionClass($obj);
        $id = $obj->getId();

        $tableName = strtolower($ref->getShortName());

        // Création dynamique du SQL
        $sql = "DELETE FROM $tableName WHERE id=? ";
        $req = BDD::$pdo->prepare($sql);
        $req->execute([$id]);
    }
}