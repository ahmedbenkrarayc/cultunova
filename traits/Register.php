<?php
require_once __DIR__.'/../classes/Database.php';
require_once __DIR__.'/../classes/User.php';

trait Register{
    public function register(User $user){
        $database = new Database();
        $nullvalue = false;
        $errors = [];
        try{
            if($user->getFname() == null){
                array_push($errors, 'First name is required !');
                $nullvalue = true;
            }

            if($user->getLname() == null){
                array_push($errors, 'First name is required !');
                $nullvalue = true;
            }

            if($user->getEmail() == null){
                array_push($errors, 'Email is required !');
                $nullvalue = true;
            }
            
            if($user->getPassword() == null){
                array_push($errors, 'Password is required !');
                $nullvalue = true;
            }

            if($user->getRole() == null){
                array_push($errors, 'Role is required !');
                $nullvalue = true;
            }

            if($nullable)
                return ['success' => false, 'errors' => $errors];
    
            $connection = $database->getConnection();
            $query = 'SELECT id, role, password FROM user WHERE email = :email';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch();

            if($user){
                //email found
                if(password_verify($this->password, $user['password'])){
                    //correct password
                    setcookie('user_id', $user['id'], time() + 24 * 60 * 60, '/');
                    setcookie('user_role', $user['role'], time() + 24 * 60 * 60, '/');
                    return ['success' => true, 'errors' => $errors];
                }else{
                    //wrong password
                    array_push($errors, 'Wrong password !');
                    return ['success' => false, 'errors' => $errors];
                }
            }else{
                //email notfound
                array_push($errors, 'We have no user with this email !');
                return ['success' => false, 'errors' => $errors];
            }
        }catch(PDOException $e){
            echo $e->getMessage();
            array_push($errors, 'Something went wrong !');
            return ['success' => false, 'errors' => $errors];
        }
    }
}