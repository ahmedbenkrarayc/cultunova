<?php

require_once __DIR__.'/Database.php';

class User{
    protected $id;
    protected $fname;
    protected $lname;
    protected $email;
    protected $password;
    protected $role;
    protected $createdAt;
    protected $updatedAt;
    protected $database;

    public function __construct($id, $fname, $lname, $email, $password, $role, $createdAt, $updatedAt){
        $this->setId($id);
        $this->setFname($fname);
        $this->setLname($lname);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setRole($role);
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
        $this->database = new Database();
    }

    //getters
    public function getId(){
        return $this->id;
    }

    public function getFname(){
        return $this->fname;
    }

    public function getLname(){
        return $this->lname;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getRole(){
        return $this->role;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function getUpdatedAt(){
        return $this->updatedAt;
    }

    //setters
    public function setId($id){
        $this->id = $id;
    }

    public function setFname($fname){
        $this->fname = $fname;
    }

    public function setLname($lname){
        $this->lname = $lname;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function setPassword($password){
        $this->password = $password;
    }

    public function setRole($role){
        $this->role = $role;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt($updatedAt){
        $this->updatedAt = $updatedAt;
    }
}