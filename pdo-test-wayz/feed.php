<?php

require 'core/init.php'; // ajout connexion bdd 
require_once 'header.php';

if (isset($_SESSION['user']) && isset($_SESSION['user-id'])) {
    $user_data = $user_obj->findByiD($_SESSION['user-id']);
    if ($user_data ===  false) {
        header('Location: logout.php');
        exit;
    }
    // FETCH ALL USERS WHERE ID IS NOT EQUAL TO MY ID
    $all_users = $user_obj->all_users($_SESSION['user-id']);
} else {
    header('Location: logout.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Projects</title>
    <link rel="stylesheet" href="css/landing.css">

</head>

<body>
    <h1 class="subtitle">FEED</h1>
    <form class="postForm" action="" method="post">
        <div class="postL">

        </div>
        <div class="postR">
            <textarea class="text-post" name="message" placeholder="Write a post..." required></textarea>
            <nav>
                <ul>
                    <button type="submit" class="invisibleBtn">
                        <li>post<span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </li>
                    </button><br>
                </ul>
            </nav>
        </div>
    </form>
</body>

</html>