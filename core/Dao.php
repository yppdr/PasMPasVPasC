<?php

abstract class Dao implements Crud_interface,Repository_interface {

    protected $pdo;


    function __construct() {

        $listeConnect = json_decode(file_get_contents('./config/db.json'),true);

        $this->pdo = new PDO(
            $listeConnect['type']
            .":host=".$listeConnect['host'].($listeConnect['port'] === "" ? ";" : ";port=".$listeConnect['port'].";")
            ."dbname=".$listeConnect['dbName']
            ,$listeConnect['user']
            ,$listeConnect['password']
        );
    }


}
