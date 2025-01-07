<?php
require_once __DIR__.'/Database.php';
require_once __DIR__.'/../exceptions/InputException.php';
require_once __DIR__.'/../utils/Logger.php';

class Like{
    private $article_id;
    private $visitor_id;
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
    public function getArticleId() {
        return $this->article_id;
    }

    public function getVisitorId() {
        return $this->visitor_id;
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

    //methods
    public function likeArticle(){
        try{
            if($this->article_id == null){
                array_push($this->errors, 'Article id is required !');
                return false;
            }

            if($this->visitor_id == null){
                array_push($this->errors, 'Visitor id is required !');
                return false;
            }

            $connection =  $this->database->getConnection();
            $query = 'insert into likes(article_id, visitor_id) values(:article_id, :visitor_id)';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':article_id', htmlspecialchars($this->article_id), PDO::PARAM_INT);
            $stmt->bindValue(':visitor_id', htmlspecialchars($this->visitor_id), PDO::PARAM_INT);
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

    public function unlikeArticle(){
        try{
            if($this->article_id == null){
                array_push($this->errors, 'Article id is required !');
                return false;
            }

            if($this->visitor_id == null){
                array_push($this->errors, 'Visitor id is required !');
                return false;
            }

            $connection = $this->database->getConnection();
            $query = 'delete from likes where article_id = :article_id AND visitor_id = :visitor_id';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':article_id', htmlspecialchars($this->article_id), PDO::PARAM_INT);
            $stmt->bindValue(':visitor_id', htmlspecialchars($this->visitor_id), PDO::PARAM_INT);
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

    public function favoriteByVisitor(){
        try{
            if($this->visitor_id == null){
                array_push($this->errors, 'Visitor id is required !');
                return false;
            }

            $connection = $this->database->getConnection();
            $query = 'SELECT a.* FROM article a, likes l WHERE a.id = l.article_id AND l.visitor_id = :visitor_id';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':visitor_id', htmlspecialchars($this->visitor_id), PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }catch(PDOException $e){
            Logger::error_log($e->getMessage());
            array_push($this->errors, 'Something went wrong !');
            return null;
        }
    }
}