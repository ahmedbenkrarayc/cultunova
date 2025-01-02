<?php

require_once __DIR__.'/User.php';
require_once __DIR__.'/../exceptions/InputException.php';
require_once __DIR__.'/IRegistrable.php';
require_once __DIR__.'/../traits/Register.php';

class Author extends User implements IRegistrable{
    use Register;
    
    private $deleted;
    private $picture;
    private $cover;

    public function __construct($id, $fname, $lname, $email, $password, $createdAt, $updatedAt, $picture, $cover, $deleted){
        parent::__construct($id, $fname, $lname, $email, $password, 'author', $createdAt, $updatedAt);
        try{
            $this->setPicture($picture);
            $this->setCover($cover);
            $this->setDeleted($deleted);
        }catch(InputException $e){
            echo $e->getMessage();
        }
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
        if($deleted != null)
            if($deleted != 0 && $deleted != 1)
                throw new InputException('Deleted value must either be a 0 or 1 !');
        $this->deleted = $deleted;
    }

    public function setPicture($picture){
        $this->picture = $picture;
    }

    public function setCover($cover){
        $this->cover = $cover;
    }
}