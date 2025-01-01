<?php

class EnvException extends Exception{
    public function __construct($message, $code = 0, Exception $previous = null){
        parent::__construct("Env varibales exception : ".$message, $code, $previous);
    }   
}