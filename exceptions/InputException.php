<?php

class InputException extends Exception{
    public function __construct($message, $code = 0, Exception $previous = null){
        parent::__construct("Input value exception : ".$message, $code, $previous);
    }   
}