<?php
require_once __DIR__.'/Database.php';
require_once __DIR__.'/../exceptions/InputException.php';

class Category{
    private $id;
    private $name;
    private $createdAt;
    private $updatedAt;
    private $errors = [];

    public function __construct($id, $name, $createdAt, $updatedAt){
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
        if($id != null)
            if(!filter_var($id, FILTER_VALIDATE_INT))
                throw new InputException('Id must be a number !');
        $this->id = $id;
    }

    public function setName($name){
        if($name != null)
            if(!is_string($name))
                throw new InputException('Category name must be a string !');
        $this->name = $name;
    }

    //methods
    public function create(){
        try{
            if($this->name == null){
                array_push($this->errors, 'Category name is required !');
                return false;
            }
            $connection =  $this->database->getConnection();
            $query = 'insert into category(name) values(:name)';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            if($stmt->execute()){
                return true;
            }

            array_push($this->errors, 'Something went wrong !');
            return false;
        }catch(PDOException $e){
            echo $e->getMessage();
            array_push($this->errors, 'Something went wrong !');
            return false;
        }
    }
}