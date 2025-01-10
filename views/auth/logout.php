<?php
require_once './../../classes/User.php';

if(!User::verifyAuth()){
    header('Location: ./../login.php');
}

User::logout();