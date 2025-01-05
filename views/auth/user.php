<?php
require_once './../../classes/User.php';
if(isset($_COOKIE['id'])){
    $user = new User($_COOKIE['id'], null, null, null, null, null, null, null);
    $authUser = $user->getUser();
} 
?>