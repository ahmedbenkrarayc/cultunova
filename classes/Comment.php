<?php
require_once __DIR__.'/Database.php';
require_once __DIR__.'/../exceptions/InputException.php';
require_once __DIR__.'/../utils/Logger.php';

class Comment{
    private $id;
    private $article_id;
    private $visitor_id;
    private $content;
    private $createdAt;
    private $updatedAt;
    private $errors = [];
    private $database;

    public function __construct($id, $article_id, $visitor_id, $content, $createdAt = null, $updatedAt = null){
        try{
            $this->setArticleId($article_id);
            $this->setVisitorId($visitor_id);
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
    public function create(){
        try{
            if($this->article_id == null){
                array_push($this->errors, 'Article id is required !');
                return false;
            }

            if($this->visitor_id == null){
                array_push($this->errors, 'Visitor id is required !');
                return false;
            }

            if($this->content == null){
                array_push($this->errors, 'Comment is required !');
                return false;
            }

            $connection =  $this->database->getConnection();
            $query = 'insert into comment(article_id, visitor_id, content) values(:article_id, :visitor_id, :content)';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':article_id', htmlspecialchars($this->article_id), PDO::PARAM_INT);
            $stmt->bindValue(':visitor_id', htmlspecialchars($this->visitor_id), PDO::PARAM_INT);
            $stmt->bindValue(':content', htmlspecialchars($this->content), PDO::PARAM_STR);
            if($stmt->execute()){
                return true;
            }

            array_push($this->errors, 'Something went wrong !');
            return false;
        }catch(InputException $e){
            Logger::error_log($e->getMessage());
            array_push($this->errors, 'Something went wrong !');
            return false;
        }
    }

    public function delete(){
        try{
            if($this->id == null){
                array_push($this->errors, 'Id is required !');
                return false;
            }

            $connection = $this->database->getConnection();
            $query = 'DELETE FROM comment WHERE id = :id';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':id', htmlspecialchars($this->id), PDO::PARAM_INT);
            if($stmt->execute()){
                return true;
            }

            array_push($this->errors, 'Something went wrong !');
            return false;
        }catch(PDOException $e){
            Logger::error_log($e->getMessage());
            array_push($this->errors, 'Something went wrong !');
            return false;
        }
    }

    public function articleComments(){
        try{
            if($this->article_id == null){
                array_push($this->errors, 'Article id is required !');
                return false;
            }

            $connection = $this->database->getConnection();
            $query = 'SELECT a.*, fname, lname, c.content as comment FROM article a, comment c, user u WHERE a.id = c.article_id AND u.id = c.id AND c.article_id = :article_id';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':article_id', htmlspecialchars($this->article_id), PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }catch(PDOException $e){
            Logger::error_log($e->getMessage());
            array_push($this->errors, 'Something went wrong !');
            return null;
        }
    }
}