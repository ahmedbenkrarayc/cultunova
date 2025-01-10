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

    public function softDelete(){
        try{
            if($this->id == null){
                return false;
            }

            $connection = $this->database->getConnection();
            $query = 'SELECT * FROM author_details WHERE author_id = :id';
            $checkStmt = $connection->prepare($query);
            $checkStmt->bindValue(':id', htmlspecialchars($this->id), PDO::PARAM_INT);
            $checkStmt->execute();
            if($checkStmt->fetch()){
                $query = 'UPDATE author_details SET deleted = 1 WHERE author_id = :id';
                $stmt = $connection->prepare($query);
                $stmt->bindValue(':id', htmlspecialchars($this->id), PDO::PARAM_INT);
                if($stmt->execute()){
                    return true;
                }
            }else{
                $query = 'INSERT INTO author_details(author_id, picture, cover, deleted) values(:id, null, null, 1)';
                $stmt = $connection->prepare($query);
                $stmt->bindValue(':id', htmlspecialchars($this->id), PDO::PARAM_INT);
                if($stmt->execute()){
                    return true;
                }
            }

            array_push($this->errors, 'Something went wrong !');
            return false;
        }catch(PDOException $e){
            Logger::error_log($e->getMessage());
            array_push($this->errors, 'Something went wrong !');
            return null;
        }
    }

    public function updateProfile() {
        try {
            $result = parent::updateProfile();
            if ($result) {
                $connection = $this->database->getConnection();
    
                $query = 'SELECT * FROM author_details WHERE author_id = :id';
                $checkStmt = $connection->prepare($query);
                $checkStmt->bindValue(':id', htmlspecialchars($this->id), PDO::PARAM_INT);
                $checkStmt->execute();
    
                if ($checkStmt->fetch()) {
                    $query = 'UPDATE author_details SET picture = :picture, cover = :cover WHERE author_id = :id';
                    $stmt = $connection->prepare($query);
                    $stmt->bindValue(':id', htmlspecialchars($this->id), PDO::PARAM_INT);
                    $stmt->bindValue(':picture', htmlspecialchars($this->picture), PDO::PARAM_STR);
                    $stmt->bindValue(':cover', htmlspecialchars($this->cover), PDO::PARAM_STR);
    
                    if ($stmt->execute()) {
                        return true;
                    }
                } else {
                    $query = 'INSERT INTO author_details (author_id, picture, cover, deleted) VALUES (:id, :picture, :cover, 0)';
                    $stmt = $connection->prepare($query);
                    $stmt->bindValue(':id', htmlspecialchars($this->id), PDO::PARAM_INT);
                    $stmt->bindValue(':picture', $this->picture !== null ? htmlspecialchars($this->picture) : null, PDO::PARAM_STR);
                    $stmt->bindValue(':cover', $this->cover !== null ? htmlspecialchars($this->cover) : null, PDO::PARAM_STR);
    
                    if ($stmt->execute()) {
                        return true;
                    }
                }
            }
    
            $this->errors[] = 'Something went wrong!';
            return false;
        } catch (PDOException $e) {
            Logger::error_log($e->getMessage());
            $this->errors[] = 'Something went wrong!';
            return false;
        }
    }
    
}