<?php
require_once __DIR__.'/Database.php';
require_once __DIR__.'/../exceptions/InputException.php';
require_once __DIR__.'/../utils/Logger.php';

class Like{
    private $id;
    private $article_id;
    private $visitor_id;
    private $content;
    private $createdAt;
    private $updatedAt;
    private $errors = [];
    private $database;

    public function __construct($article_id, $visitor_id, $createdAt = null, $updatedAt = null){
        try{
            $this->setArticleId($article_id);
            $this->setVisitor($visitor_id);
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

    public function getArticleId() {
        return $this->article_id;
    }

    public function getVisitorId() {
        return $this->visitor_id;
    }

    public function getContent() {
        return $this->content;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
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

    public function setArticleId($article_id){
        if($article_id != null){
            if(!filter_var($article_id, FILTER_VALIDATE_INT))
                throw new InputException('Article id must be a number !');

            if($article_id < 1)
                throw new InputException('Article id must be a positive number greater than 0 !');
        }
        $this->article_id = $article_id;
    }

    public function setVisitorId($visitor_id){
        if($visitor_id != null){
            if(!filter_var($visitor_id, FILTER_VALIDATE_INT))
                throw new InputException('Visitor id must be a number !');

            if($visitor_id < 1)
                throw new InputException('Visitor id must be a positive number greater than 0 !');
        }
        $this->visitor_id = $visitor_id;
    }

    public function setContent($content){
        if($content != null){
            if(strlen(trim($content)) < 3)
                throw new InputException('Comment should at least contain 3 characters !');
        }
        $this->content = $content;
    }

    //methods
    
}