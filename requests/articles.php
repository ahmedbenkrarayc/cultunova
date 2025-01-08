<?php

require_once __DIR__.'/../classes/Article.php';

$article = new Article(null, null, null, null, null, null, null, null, null, null);
$articles = $article->getAll();

if($articles !== null){
    echo json_encode([
        'success' => true,
        'data' => $articles
    ]);
}else{
    echo json_encode([
        'success' => false
    ]);
}