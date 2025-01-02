<?php

require_once __DIR__.'/User.php';
require_once __DIR__.'/IRegistrable.php';
require_once __DIR__.'/../traits/Register.php';

class Visitor extends User implements IRegistrable{
    use Register;
    public function __construct($id, $fname, $lname, $email, $password, $createdAt, $updatedAt){
        parent::__construct($id, $fname, $lname, $email, $password, 'visitor', $createdAt, $updatedAt);
    }
}