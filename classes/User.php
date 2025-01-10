<?php

require_once __DIR__.'/Database.php';
require_once __DIR__.'/../exceptions/InputException.php';
require_once __DIR__.'/../utils/Logger.php';

class User{
    protected $id;
    protected $fname;
    protected $lname;
    protected $email;
    protected $password;
    protected $role;
    protected $createdAt;
    protected $updatedAt;
    protected $database;
    protected $errors = [];

    public function __construct($id, $fname, $lname, $email, $password, $role, $createdAt, $updatedAt){
        try{
            $this->setId($id);
            $this->setFname($fname);
            $this->setLname($lname);
            $this->setEmail($email);
            $this->setPassword($password);
            $this->setRole($role);
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

    public function getFname(){
        return $this->fname;
    }

    public function getLname(){
        return $this->lname;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getRole(){
        return $this->role;
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

    public function setFname($fname){
        if($fname != null){
            if(!is_string($fname))
                throw new InputException('First name must be a string !');

            if(strlen(trim($fname)) < 3)
                throw new InputException('First name must contain at least 3 characters !');
        }
        $this->fname = $fname;
    }

    public function setLname($lname){
        if($lname != null){
            if(!is_string($lname))
                throw new InputException('Last name must be a string !');

            if(strlen(trim($lname)) < 3)
                throw new InputException('Last name must be a string !');
        }
        $this->lname = $lname;
    }

    public function setEmail($email){
        if($email != null)
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                throw new InputException('Email isn\'t valid !');
        $this->email = $email;
    }

    public function setPassword($password){
        if($password != null)
            if(strlen($password) < 8)
                throw new InputException('Password must contain at least 8 characters !');
        $this->password = $password;
    }

    public function setRole($role){
        if($role != null)
            if($role != 'admin' && $role != 'author' && $role != 'visitor')
                throw new InputException('Role can only be admin, visitor or author !');
        $this->role = $role;
    }

    //methods
    public function login(){
        try{
            $nullvalue = false;
            if($this->email == null){
                array_push($this->errors, 'Email must have a value !');
                $nullvalue = true;
            }
            
            if($this->password == null){
                array_push($this->errors, 'Password must have a value !');
                $nullvalue = true;
            }

            if($nullvalue)
                return false;
    
            $connection = $this->database->getConnection();
            $query = 'SELECT id, role, password FROM user WHERE email = :email';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':email', htmlspecialchars($this->email), PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch();

            if($user){
                //email found
                if(password_verify($this->password, $user['password'])){
                    //correct password
                    setcookie('user_id', $user['id'], time() + 24 * 60 * 60, '/');
                    setcookie('user_role', $user['role'], time() + 24 * 60 * 60, '/');
                    return true;
                }else{
                    //wrong password
                    array_push($this->errors, 'Wrong password !');
                    return false;
                }
            }else{
                //email notfound
                array_push($this->errors, 'We have no user with this email !');
                return false;
            }
        }catch(PDOException $e){
            Logger::error_log($e->getMessage());
            array_push($this->errors, 'Something went wrong !');
            return false;
        }
    }

    public static function logout(){
        setcookie('user_id', '', time() - 60 * 60, '/');
        setcookie('user_role', '', time() - 60 * 60, '/');
        header('Location: ./login.php');
    }

    public static function verifyAuth($role = null){
        if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_role'])){
            if($role != null){
                return $_COOKIE['user_role'] == $role;
            }else{
                return true;
            }
        }
    
        return false;
    }

    public function getUser(){
        try{
            if($this->id == null){
                return false;
            }

            $connection = $this->database->getConnection();
            $query = 'SELECT * FROM user WHERE id = :id';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':id', htmlspecialchars($this->id), PDO::PARAM_INT);
            $stmt->execute();
            $user =  $stmt->fetch();
            if($user){
                if($user['role'] == 'author'){
                    $query = 'SELECT * FROM author_details WHERE author_id = :id';
                    $checkStmt = $connection->prepare($query);
                    $checkStmt->bindValue(':id', htmlspecialchars($this->id), PDO::PARAM_INT);
                    $checkStmt->execute();
                    $author = $checkStmt->fetch();
                    if($author){
                        if($author['deleted'] == 1)
                            return null;
                        else
                            $user = array_merge($user, $author);
                    }
                }
                return $user;
            }else{
                return null;
            }
        }catch(PDOException $e){
            Logger::error_log($e->getMessage());
            array_push($this->errors, 'Something went wrong !');
            return null;
        }
    }
    
    public function updateProfile(){
        try{
            $nullvalue = false;
            if($this->id == null){
                array_push($this->errors, 'Id is required !');
                $nullvalue = true;
            }

            if($this->fname == null){
                array_push($this->errors, 'First name is required !');
                $nullvalue = true;
            }

            if($this->lname == null){
                array_push($this->errors, 'Last name is required !');
                $nullvalue = true;
            }

            if($nullvalue)
                return false;

            $connection = $this->database->getConnection();
            $query = 'UPDATE user SET fname = :fname, lname = :lname '; 
            if($this->password != null)
                $query .= 'password = :password';
            $query .= ' WHERE id = :id';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':id', htmlspecialchars($this->id), PDO::PARAM_INT);
            $stmt->bindValue(':fname', htmlspecialchars($this->fname), PDO::PARAM_STR);
            $stmt->bindValue(':lname', htmlspecialchars($this->lname), PDO::PARAM_STR);
            if($this->password != null){
                $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
                $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
            }

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