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

        // 开启事务
        public function startTrans()
        {
            self::$pdo->exec('start transaction');
        }

        // 提交事务
        public function commit()
        {
            self::$pdo->exec('commit');
        }

        // 回滚事务
        public function rollback()
        {
            self::$pdo->exec('rollback');
        }
    }
