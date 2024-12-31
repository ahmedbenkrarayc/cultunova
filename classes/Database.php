<?php

require_once __DIR__.'/../env.php';
require_once __DIR__.'/../exceptions/EnvException.php';

class Database{
    private $connection;

    public function __construct(){
        try{
            $this->validate();

            $this->connection = new PDO('mysql:host='.$GLOBALS['host'].';dbname='.$GLOBALS['db'].'; charset=utf8mb4',
                $GLOBALS['username'],
                $GLOBALS['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }catch(EnvException $e){
            echo "Error : " . $e->getMessage();
        }catch(PDOException $e){
            echo "Error : " . $e->getMessage();
        }
    }

    public function getConnection(){
        return $this->connection;
    }

    private function validate(){
        if(!isset($GLOBALS['host']) || empty($GLOBALS['host'])){
            throw new EnvException('Host doesn\'t exist in env !');
        }

        if(!isset($GLOBALS['db']) || empty($GLOBALS['db'])){
            throw new EnvException('Database doesn\'t exist in env !');
        }

        if(!isset($GLOBALS['username']) || empty($GLOBALS['username'])){
            throw new EnvException('Username doesn\'t exist in env !');
        }

        
        if(!isset($GLOBALS['password'])){
            throw new EnvException('Password doesn\'t exist in env !');
        }
    }
}