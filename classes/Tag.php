<?php
require_once __DIR__.'/Database.php';
require_once __DIR__.'/../exceptions/InputException.php';
require_once __DIR__.'/../utils/Logger.php';

class Tag{
    private $id;
    private $name;
    private $createdAt;
    private $updatedAt;
    private $errors = [];
    private $database;

    public function __construct($id, $name, $createdAt = null, $updatedAt = null){
        try{
            $this->setId($id);
            $this->setName($name);
            $this->createdAt = $createdAt;
            $this->updatedAt = $updatedAt;
            $this->database = new Database();
        }catch(InputException $e){
            array_push($this->errors, $e->getMessage());
        }
    }

    //getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
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

    public function setName($name){
        if($name != null){
            if(!is_string($name))
                throw new InputException('Tag name must be a string !');
            if(strlen(trim($name)) < 3)
                throw new InputException('Tag name should at least contain 3 characters !');
        }
        $this->name = $name;
    }

    //methods
    public function create(){
        try{
            if($this->name == null){
                array_push($this->errors, 'Tag name is required !');
                return false;
            }
            $connection =  $this->database->getConnection();
            $query = 'insert into tag(name) values(:name)';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':name', htmlspecialchars($this->name), PDO::PARAM_STR);
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

            if($this->name == null){
                array_push($this->errors, 'Tag name is required !');
                $nullvalue = true;
            }

            if($nullvalue)
                return false;

            $connection = $this->database->getConnection();
            $query = 'update tag set name = :name where id = :id';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':name', htmlspecialchars($this->name), PDO::PARAM_STR);
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

    public function delete(){
        try{
            if($this->id == null){
                array_push($this->errors, 'Id is required !');
                return false;
            }

            $connection = $this->database->getConnection();
            $query = 'delete from tag where id = :id';
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

    public function tagList(){
        try{
            $connection = $this->database->getConnection();
            $query = 'select * from tag';
            $stmt = $connection->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        }catch(PDOException $e){
            Logger::error_log($e->getMessage());
            array_push($this->errors, 'Something went wrong !');
            return null;
        }
    }

    public function tagById(){
        try{
            $connection = $this->database->getConnection();
            $query = 'select * from tag where id = :id';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':id', htmlspecialchars($this->id), PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        }catch(PDOException $e){
            Logger::error_log($e->getMessage());
            array_push($this->errors, 'Something went wrong !');
            return null;
        }
    }
}