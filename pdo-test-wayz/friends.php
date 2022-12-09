<?php
require 'core/init.php';
require_once 'header.php';

if(isset($_SESSION['user']) && isset($_SESSION['user-id'])){
    $user_data = $user_obj->findByiD($_SESSION['user-id']);
    if($user_data ===  false){
        header('Location: logout.php');
        exit;
    }
}
else{
    header('Location: logout.php');
    exit;
}
// TOTAL REQUESTS
$get_req_num = $frnd_obj->request_notification($_SESSION['user-id'], false);
// TOTLA FRIENDS
$get_frnd_num = $frnd_obj->get_all_friends($_SESSION['user-id'], false);
// GET MY($_SESSION['user_id']) ALL FRIENDS
$get_all_friends = $frnd_obj->get_all_friends($_SESSION['user-id'], true);
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Profile</title>
        <link rel="stylesheet" href="css/landing.css">
        
    </head>
    <body>
        <h1 class="subtitle">FRIENDS</h1>
        <h2 class="h2Landing">MY FRIENDS :</h2>
        <h2 class="h2Landing">FRIEND REQUESTS:</h2>
    </body>

</html>