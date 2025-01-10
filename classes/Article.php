<?php
require_once __DIR__.'/Database.php';
require_once __DIR__.'/ArticleTag.php';
require_once __DIR__.'/../exceptions/InputException.php';
require_once __DIR__.'/../utils/Logger.php';
require __DIR__.'/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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
    private $articleTag;
    private $database;
    private $errors = [];

    public function __construct($id, $title, $description, $content, $cover, $status, $category, $author, $createdAt, $updatedAt, $articleTag = null){
        try{
            $this->setId($id);
            $this->setTitle($title);
            $this->setDescription($description);
            $this->setContent($content);
            $this->setCover($cover);
            $this->setStatus($status);
            $this->setCategoryId($category);
            $this->setAuthorId($author);
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
            if(strlen(trim($title)) <3)
                throw new InputException('Title should at least contain 3 characters !');
        }
        $this->title = $title;
    }

    public function setDescription($description){
        if($description != null){
            if(!is_string($description))
                throw new InputException('Description must be a string !');
            if(strlen(trim($description)) < 100)
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
    public function create($tags){
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
            $stmt->bindValue(':title', htmlspecialchars($this->title), PDO::PARAM_STR);
            $stmt->bindValue(':description', htmlspecialchars($this->description), PDO::PARAM_STR);
            $stmt->bindValue(':content', htmlspecialchars($this->content), PDO::PARAM_STR);
            $stmt->bindValue(':cover', htmlspecialchars($this->cover), PDO::PARAM_STR);
            $stmt->bindValue(':category_id', htmlspecialchars($this->category_id), PDO::PARAM_INT);
            $stmt->bindValue(':author_id', htmlspecialchars($this->author_id), PDO::PARAM_INT);
            if($stmt->execute()){
                $lastId = $connection->lastInsertId();
                foreach($tags as $item){
                    $tag = new ArticleTag($lastId, $item);
                    $tag->addArticleTag();
                }
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

    public function update($tags){
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
            $stmt->bindValue(':id', htmlspecialchars($this->id), PDO::PARAM_INT);
            $stmt->bindValue(':title', htmlspecialchars($this->title), PDO::PARAM_STR);
            $stmt->bindValue(':description', htmlspecialchars($this->description), PDO::PARAM_STR);
            $stmt->bindValue(':content', htmlspecialchars($this->content), PDO::PARAM_STR);
            $stmt->bindValue(':cover', htmlspecialchars($this->cover), PDO::PARAM_STR);
            $stmt->bindValue(':category_id', htmlspecialchars($this->category_id), PDO::PARAM_INT);
            $stmt->bindValue(':author_id', htmlspecialchars($this->author_id), PDO::PARAM_INT);
            $stmt->bindValue(':status', htmlspecialchars($this->status), PDO::PARAM_STR);
            if($stmt->execute()){
                $tag = new ArticleTag($this->id, null);
                $tag->detachAllArticleTags();
                foreach($tags as $item){
                    $tag = new ArticleTag($this->id, $item);
                    $tag->addArticleTag();
                }
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

    public function changeStatus(){
        try{
            $nullvalue = false;
            if($this->id == null){
                array_push($this->errors, 'Id is required !');
                $nullvalue = true;
            }

            if($this->status == null){
                array_push($this->errors, 'Status is required !');
                $nullvalue = true;
            }
            
            if($nullvalue)
                return false;

            $connection = $this->database->getConnection();
            $query = 'UPDATE article SET status = :status WHERE id = :id';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':id', htmlspecialchars($this->id), PDO::PARAM_INT);
            $stmt->bindValue(':status', htmlspecialchars($this->status), PDO::PARAM_STR);
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

    public function getAll(){
        try{
            $connection = $this->database->getConnection();
            $query = 'SELECT a.*, u.fname AS author_fname, u.lname AS author_lname FROM article a LEFT JOIN user u ON a.author_id = u.id LEFT JOIN author_details ad ON a.author_id = ad.author_id WHERE ad.author_id IS NULL OR ad.deleted = 0;';
            $stmt = $connection->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        }catch(PDOException $e){
            Logger::error_log($e->getMessage());
            array_push($this->errors, 'Something went wrong !');
            return null;
        }
    }

    public function getOne(){
        try{
            if($this->id == null){
                array_push($this->errors, 'Id is required !');
                return false;
            }

            $connection = $this->database->getConnection();
            $query = 'SELECT a.*, u.fname, u.lname FROM article a INNER JOIN user u ON a.author_id = u.id LEFT JOIN author_details ad ON a.author_id = ad.author_id WHERE (ad.author_id IS NULL OR ad.deleted = 0) AND a.id = :id';
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

    public function articleByAuthor(){
        try{
            if($this->author_id == null){
                array_push($this->errors, 'Author id is required !');
                return false;
            }

            $connection = $this->database->getConnection();
            $query = 'SELECT * FROM article WHERE author_id = :author_id';
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':author_id', htmlspecialchars($this->author_id), PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }catch(PDOException $e){
            Logger::error_log($e->getMessage());
            array_push($this->errors, 'Something went wrong !');
            return null;
        }
    }

    public static function generatePDF($article){
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $html = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Article PDF</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    margin: 20px;
                    padding: 0;
                    color: #333;
                }
                .container {
                    max-width: 800px;
                    margin: 0 auto;
                }
                header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                header h1 {
                    font-size: 24px;
                    margin: 0;
                    color: #0056b3;
                }
                header p {
                    font-size: 14px;
                    color: #555;
                }
                .author {
                    margin-top: 10px;
                    font-style: italic;
                    color: #666;
                }
                .description {
                    font-size: 16px;
                    margin: 20px 0;
                    padding: 10px;
                    background-color: #f9f9f9;
                    border-left: 4px solid #0056b3;
                }
                article {
                    font-size: 14px;
                    line-height: 1.8;
                }
                footer {
                    margin-top: 40px;
                    text-align: center;
                    font-size: 12px;
                    color: #888;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <header>
                    <h1>" . htmlspecialchars($article['title']) . "</h1>
                    <p class='author'>By " . htmlspecialchars($article['fname']) . " " . htmlspecialchars($article['lname']) . "</p>
                </header>
                <section class='description'>
                    <p>" . htmlspecialchars($article['description']) . "</p>
                </section>
                <article>
                    " . nl2br(htmlspecialchars($article['content'])) . "
                </article>
            </div>
        </body>
        </html>
        ";

        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("article.pdf", ["Attachment" => 1]);
    }

}
