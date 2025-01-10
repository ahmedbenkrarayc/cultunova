<?php
require_once __DIR__.'/Database.php';
require_once __DIR__.'/../exceptions/InputException.php';
require_once __DIR__.'/../utils/Logger.php';

class ArticleTag{
    private $article_id;
    private $tag_id;
    private $errors = [];
    private $database;

    public function __construct($article_id, $tag_id, $createdAt = null, $updatedAt = null){
        try{
            $this->setArticleId($article_id);
            $this->setTagId($tag_id);
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

    public function getTagId() {
        return $this->tag_id;
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

    public function setTagId($tag_id){
        if($tag_id != null){
            if(!filter_var($tag_id, FILTER_VALIDATE_INT))
                throw new InputException('Tag id must be a number !');

            if($tag_id < 1)
                throw new InputException('Tag id must be a positive number greater than 0 !');
        }
        $this->tag_id = $tag_id;
    }

    //methods
    public function addArticleTag(){
        try{
            if($this->article_id == null){
                array_push($this->errors, 'Article id is required !');
                return false;
            }

            if($this->tag_id == null){
                array_push($this->errors, 'Tag id is required !');
                return false;
            }

            $connection =  $this->database->getConnection();
            $query = 'insert into articletag(article_id, tag_id) values(:article_id, :tag_id)';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':article_id', htmlspecialchars($this->article_id), PDO::PARAM_INT);
            $stmt->bindValue(':tag_id', htmlspecialchars($this->tag_id), PDO::PARAM_INT);
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

    public function detachArticleTag(){
        try{
            if($this->article_id == null){
                array_push($this->errors, 'Article id is required !');
                return false;
            }

            if($this->tag_id == null){
                array_push($this->errors, 'Tag id is required !');
                return false;
            }

            $connection = $this->database->getConnection();
            $query = 'delete from articletag where article_id = :article_id AND tag_id = :tag_id';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':article_id', htmlspecialchars($this->article_id), PDO::PARAM_INT);
            $stmt->bindValue(':tag_id', htmlspecialchars($this->tag_id), PDO::PARAM_INT);
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

    public function detachAllArticleTags(){
        try{
            if($this->article_id == null){
                array_push($this->errors, 'Article id is required !');
                return false;
            }
            $connection = $this->database->getConnection();
            $query = 'delete from articletag where article_id = :article_id';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':article_id', htmlspecialchars($this->article_id), PDO::PARAM_INT);
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

    public function tagsOfArticle(){
        try{
            if($this->article_id == null){
                array_push($this->errors, 'Article id is required !');
                return false;
            }

            $connection = $this->database->getConnection();
            $query = 'SELECT t.* from article a, articletag t WHERE a.id = t.article_id and a.id = :article_id';
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