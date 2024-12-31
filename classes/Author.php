<?php

require_once __DIR__.'/User.php';

class Author extends User{
    private $deleted;
    private $picture;
    private $cover;

    public function __construct($id, $fname, $lname, $email, $password, $createdAt, $updatedAt, $picture, $cover, $deleted){
        parent::__construct($id, $fname, $lname, $email, $password, 'author', $createdAt, $updatedAt);
        $this->setPicture($picture);
        $this->setCover($cover);
        $this->setDeleted($deleted);
    }

    //getters
    public function getDeleted(){
        return $this->deleted;
    }

    public function getPicture(){
        return $this->picture;
    }

    public function getCover(){
        return $this->cover;
    }

    //setters
    public function setDeleted($deleted){
        $this->deleted = $deleted;
    }

    public function setPicture($picture){
        $this->picture = $picture;
    }

    public function setCover($cover){
        $this->cover = $cover;
    }
}