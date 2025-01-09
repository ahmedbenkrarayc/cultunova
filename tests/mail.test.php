<?php 

require_once __DIR__.'/../classes/Mailer.php';

$mailer = new Mailer('test', 'ahmed.benkrara11@gmail.com', 'test code', '<h1>hello world</h1>');

echo $mailer->send();