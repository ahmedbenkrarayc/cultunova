<?php

require_once __DIR__.'/User.php';

class Visitor extends User{
    public function __construct($id, $fname, $lname, $email, $password, $createdAt, $updatedAt){
        parent::__construct($id, $fname, $lname, $email, $password, 'visitor', $createdAt, $updatedAt);
    }
}