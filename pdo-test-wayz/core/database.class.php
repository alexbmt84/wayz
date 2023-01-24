<?php

class Database {

     public static function dbConnection(){

        $db_host = "localhost";
        $db_name = "testwayz";
        $db_username = "root";
        $db_password = "";
         
        $dsn_db = 'mysql:host='.$db_host.';dbname='.$db_name.';charset=utf8';

        try{

            $site_db = new PDO($dsn_db, $db_username, $db_password);
            $site_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $site_db;

        } catch (PDOException $e){
            
            echo $e->getMessage();
            exit;
        }
    }
     
}

?>