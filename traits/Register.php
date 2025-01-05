<?php
require_once __DIR__.'/../classes/Database.php';
require_once __DIR__.'/../classes/User.php';
require_once __DIR__.'/../utils/Logger.php';

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

            $emailCheckQuery = 'SELECT COUNT(*) FROM user WHERE email = :email';
            $emailCheckStmt = $connection->prepare($emailCheckQuery);
            $emailCheckStmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
            $emailCheckStmt->execute();

            if ($emailCheckStmt->fetchColumn() > 0) {
                array_push($errors, 'Email already exists!');
                return ['success' => false, 'errors' => $errors];
            }
    
            $hashedPassword = password_hash($user->password, PASSWORD_DEFAULT);

            $connection = $database->getConnection();
            $query = 'INSERT INTO user(fname, lname, email, password, role) VALUES(:fname, :lname, :email, :password, :role)';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':fname', $user->fname, PDO::PARAM_STR);
            $stmt->bindValue(':lname', $user->lname, PDO::PARAM_STR);
            $stmt->bindValue(':email', $user->email, PDO::PARAM_STR);
            $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindValue(':role', $user->role, PDO::PARAM_STR);
            
            if($stmt->execute()){
                return ['success' => true, 'errors' => []];
            }

            array_push($errors, 'Something went wrong !');
            return ['success' => false, 'errors' => $errors];
        }catch(PDOException $e){
            Logger::error_log($e->getMessage());
            array_push($errors, 'Something went wrong !');
            return ['success' => false, 'errors' => $errors];
        }
    }
}