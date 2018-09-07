<?php
    namespace Models;

    use PDO;
    class Base {
        static $pdo = null;

        function __construct (){
            $config = config("db");
            
            if(self::$pdo === null){
                
                self::$pdo = new PDO("mysql:host=".$config['host'].";dbname=".$config['dbname'],$config['user'],$config['pwd']);
                self::$pdo->exec("set names ".$config['charset']);
            }
            

        }
    }
