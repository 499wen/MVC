<?php
    /* 
        链接redis 服务器
    */

    namespace libs;

    // 单例模式
    class Redis {
        private static $redis = null;
        private function __construct(){}
        private function __clone(){}

        public static function getInstance (){

            if(self::$redis === null){
                self::$redis = new \Predis\Client([
                    'scheme' => 'tcp',
                    'host'   => '127.0.0.1',
                    'port'   => 6379,
                ]);
            }
            
            return self::$redis;
        }

    }