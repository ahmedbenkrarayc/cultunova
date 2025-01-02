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
            echo $e->getMessage();
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

    public function logout(){
        setcookie('user_id', '', time() - 60 * 60, '/');
        setcookie('user_role', '', time() - 60 * 60, '/');
        header('Location: '.$_SERVER['PHP_SELF']);
    }

    public static function verifyAuth($role){
        if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_role'])){
            return $_COOKIE['user_role'] == $role;
        }
    
        return false;
    }
}