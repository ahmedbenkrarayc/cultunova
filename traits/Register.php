<?php
require_once __DIR__.'/../classes/Database.php';
require_once __DIR__.'/../classes/User.php';
require_once __DIR__.'/../utils/Logger.php';
require_once __DIR__.'/../classes/Mailer.php';

trait Register{
    public function register(){
        $nullvalue = false;
        $errors = [];
        try{
            if($this->getFname() == null){
                array_push($errors, 'First name is required !');
                $nullvalue = true;
            }

            if($this->getLname() == null){
                array_push($errors, 'Last name is required !');
                $nullvalue = true;
            }

            if($this->getEmail() == null){
                array_push($errors, 'Email is required !');
                $nullvalue = true;
            }
            
            if($this->getPassword() == null){
                array_push($errors, 'Password is required !');
                $nullvalue = true;
            }

            if($this->getRole() == null){
                array_push($errors, 'Role is required !');
                $nullvalue = true;
            }

            if($nullvalue)
                return ['success' => false, 'errors' => $errors];

            $connection = $this->database->getConnection();
            $emailCheckQuery = 'SELECT COUNT(*) FROM user WHERE email = :email';
            $emailCheckStmt = $connection->prepare($emailCheckQuery);
            $emailCheckStmt->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $emailCheckStmt->execute();

            if ($emailCheckStmt->fetchColumn() > 0) {
                array_push($errors, 'Email already exists!');
                return ['success' => false, 'errors' => $errors];
            }
    
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

            $query = 'INSERT INTO user(fname, lname, email, password, role) VALUES(:fname, :lname, :email, :password, :role)';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':fname', htmlspecialchars($this->fname), PDO::PARAM_STR);
            $stmt->bindValue(':lname', htmlspecialchars($this->lname), PDO::PARAM_STR);
            $stmt->bindValue(':email', htmlspecialchars($this->email), PDO::PARAM_STR);
            $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindValue(':role', htmlspecialchars($this->role), PDO::PARAM_STR);
            
            if($stmt->execute()){
                if($this->role == 'author'){
                    $welcomeEmail = '
                    <!DOCTYPE html>
                    <html lang="fr">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Bienvenue</title>
                    </head>
                    <body style="font-family: Arial, sans-serif; color: #333; text-align: center; background-color: #f9f9f9; padding: 20px;">
                        <h2 style="color: #444;">Bienvenue sur [Nom de la plateforme]</h2>
                        <p>Bonjour,</p>
                        <p>Nous sommes ravis de vous compter parmi nos auteurs. Partagez vos idées et inspirez notre communauté dès maintenant.</p>
                        <a href="http://cultunova.local/" style="background-color: #007bff; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px;">Publier un article</a>
                        <p style="margin-top: 20px;">À bientôt,<br>L’équipe CultuNova</p>
                    </body>
                    </html>';
                }else{
                    $welcomeEmail = '
                    <!DOCTYPE html>
                    <html lang="fr">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Explorez</title>
                    </head>
                    <body style="font-family: Arial, sans-serif; color: #333; text-align: center; background-color: #f9f9f9; padding: 20px;">
                        <h2 style="color: #444;">Explorez et Interagissez</h2>
                        <p>Bonjour,</p>
                        <p>Découvrez des articles inspirants, laissez des commentaires, et ajoutez vos favoris.</p>
                        <a href="http://cultunova.local/" style="background-color: #007bff; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px;">Commencer</a>
                        <p style="margin-top: 20px;">L’équipe CultuNova</p>
                    </body>
                    </html>';
                }

                $mailer = new Mailer($this->fname.' '.$this->lname, htmlspecialchars($this->email), 'Welcome to cultunova', $welcomeEmail);
                $mailer->send();
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