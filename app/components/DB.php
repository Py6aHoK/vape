<?php

class DB {
    private static $instance;
    private static $connection;
    
    public static function getInstance(){
        if(!isset(self::$instance)){
            $config = require_once ROOT . '/app/config/db_config.php';
            $dsn    = 'mysql:dbname=' . $config['dbname'] . ';host=' . $config['host'];
            $user   = $config['user'];
            $pass   = $config['password'];
            
            self::$instance = new self();
            
            if(self::$connection = new PDO($dsn,$user,$pass)){
                self::$connection->query('SET NAMES utf8');
                self::$connection->query('SET CHARACTER SET utf8');
            }
        }
        return self::$instance;
    }
    
    public function getConnection(){
        return self::$connection;
    }
}
