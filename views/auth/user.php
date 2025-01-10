<?php
require_once __DIR__.'/../../classes/User.php';
if(isset($_COOKIE['user_id'])){
    $user = new User($_COOKIE['user_id'], null, null, null, null, null, null, null);
    $authUser = $user->getUser();
}else{
    $authUser = null;
}
?>