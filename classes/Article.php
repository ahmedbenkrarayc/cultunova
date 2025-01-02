<?php
require_once __DIR__.'/Database.php';
require_once __DIR__.'/../exceptions/InputException.php';
require_once __DIR__.'/../utils/Logger.php';

class Article {
    private $id;
    private $title;
    private $description;
    private $content;
    private $cover;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $database;
    private $errors = [];

    public function __construct($id, $title, $description, $content, $cover, $status, $createdAt, $updatedAt){
        try{
            $this->setId($id);
            $this->setTitle($title);
            $this->setDescription($description);
            $this->setContent($content);
            $this->setCover($cover);
            $this->setStatus($status);
            $this->createdAt = $createdAt;
            $this->updatedAt = $updatedAt;
            $this->database = new Database();
        }catch(InputException $e){
            array_push($this->errors, $e->getMessage());
        }
    }

    //getters
    public function getId(){
        return $this->id;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getContent(){
        return $this->content;
    }

    public function getCover(){
        return $this->cover;
    }

    public function getStatus(){
        return $this->status;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function getUpdatedAt(){
        return $this->updatedAt;
    }

    public function getErrors(){
        $errors = $this->errors;
        $this->errors = [];
        return $errors;
    }

    //setters
    public function setId($id){
        if($id != null){
            if(!filter_var($id, FILTER_VALIDATE_INT))
                throw new InputException('Id must be a number !');

            if($id < 1)
                throw new InputException('Id must be a positive number greater than 0 !');
        }
        $this->id = $id;
    }

    public function setTitle($title){
        if($title != null){
            if(!is_string($title))
                throw new InputException('Title must be a string !');
            if(strlen(trim($title)))
                throw new InputException('Title should at least contain 3 characters !');
        }
        $this->title = $title;
    }

    public function setDescription($description){
        if($description != null){
            if(!is_string($description))
                throw new InputException('Description must be a string !');
            if(strlen(trim($description)))
                throw new InputException('Description should at least contain 100 characters !');
        }
        $this->description = $description;
    }

    public function setContent($content){
        if($content != null){
            if(!is_string($content))
                throw new InputException('Content must be a string !');
            if(strlen(trim($content)) < 200)
                throw new InputException('Content should at least contain 200 characters !');
        }
        $this->content = $content;
    }

    public function setCover($cover){
        $this->cover = $cover;
    }

    public function setStatus($status){
        if($status != null)
            if($status != 0 && $status != 1)
                throw new InputException('Status value must either be a 0 or 1 !');
        $this->status = $status;
    }
}
