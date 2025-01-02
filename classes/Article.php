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
    private $category_id;
    private $author_id;
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

    public function getAuthorId(){
        return $this->author_id;
    }

    public function getCategoryId(){
        return $this->category_id;
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
            if($status != 'accepted' && $status != 'in review' && $status != 'rejected')
                throw new InputException('Status value must either be accepted, in review or rejected !');
        $this->status = $status;
    }

    public function setAuthorId($author_id){
        if($author_id != null){
            if(!filter_var($author_id, FILTER_VALIDATE_INT))
                throw new InputException('Author Id must be a number !');

            if($author_id < 1)
                throw new InputException('Author Id must be a positive number greater than 0 !');
        }
        $this->author_id = $author_id;
    }

    public function setCategoryId($category_id){
        if($category_id != null){
            if(!filter_var($category_id, FILTER_VALIDATE_INT))
                throw new InputException('Category Id must be a number !');

            if($category_id < 1)
                throw new InputException('Category Id must be a positive number greater than 0 !');
        }
        $this->category_id = $category_id;
    }

    //methods
    public function create(){
        try{
            $nullvalue = false;
            if($this->title == null){
                array_push($this->errors, 'Title is required !');
                $nullvalue = true;
            }

            if($this->description == null){
                array_push($this->errors, 'Description is required !');
                $nullvalue = true;
            }

            if($this->content == null){
                array_push($this->errors, 'Content is required !');
                $nullvalue = true;
            }

            if($this->cover == null){
                array_push($this->errors, 'Cover is required !');
                $nullvalue = true;
            }

            if($this->category_id == null){
                array_push($this->errors, 'Category is required !');
                $nullvalue = true;
            }

            if($this->author_id == null){
                array_push($this->errors, 'Author is required !');
                $nullvalue = true;
            }

            if($nullvalue)
                return false;

            $connection = $this->database->getConnection();
            $query = 'INSERT INTO article(title, description, content, cover, category_id, author_id) values(:title, :description, :content, :cover, :category_id, :author_id)';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
            $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);
            $stmt->bindValue(':cover', $this->cover, PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
            $stmt->bindValue(':author_id', $this->author_id, PDO::PARAM_INT);
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

    public function update(){
        try{
            $nullvalue = false;
            if($this->id == null){
                array_push($this->errors, 'Id is required !');
                $nullvalue = true;
            }

            if($this->title == null){
                array_push($this->errors, 'Title is required !');
                $nullvalue = true;
            }

            if($this->description == null){
                array_push($this->errors, 'Description is required !');
                $nullvalue = true;
            }

            if($this->content == null){
                array_push($this->errors, 'Content is required !');
                $nullvalue = true;
            }

            if($this->cover == null){
                array_push($this->errors, 'Cover is required !');
                $nullvalue = true;
            }

            if($this->category_id == null){
                array_push($this->errors, 'Category is required !');
                $nullvalue = true;
            }

            if($this->author_id == null){
                array_push($this->errors, 'Author is required !');
                $nullvalue = true;
            }

            if($this->status == null){
                array_push($this->errors, 'Status is required !');
                $nullvalue = true;
            }

            if($nullvalue)
                return false;

            $connection = $this->database->getConnection();
            $query = 'UPDATE article SET title = :title, description = :description, content = :content, cover = :cover, category_id = :category_id, author_id = :author_id, status = :status WHERE id = :id';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
            $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);
            $stmt->bindValue(':cover', $this->cover, PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
            $stmt->bindValue(':author_id', $this->author_id, PDO::PARAM_INT);
            $stmt->bindValue(':status', $this->status, PDO::PARAM_STR);
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

    public function delete(){
        try{
            if($this->id == null){
                array_push($this->errors, 'Id is required !');
                return false;
            }

            $connection = $this->database->getConnection();
            $query = 'DELETE FROM article WHERE id = :id';
            $stmt = $connection->prepare($query);
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
}
