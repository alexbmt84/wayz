<?php

require 'core/init.php'; // ajout connexion bdd 
require_once 'header.php';

if (isset($_SESSION['user']) && isset($_SESSION['user-id'])) {

    $user_data = $user_obj->findByiD($_SESSION['user-id']);
    $userProjects = Project::findUserProject($user_data->id);
    // $project = Project::findByiD($userProjects[0]->id);

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
    <h1 class="subtitle">MY PROJECTS</h1>

    <div class="publicationsProject" id="pub1">

        <a class="friendLink2" href="add_project.php">
            <div class="photo"><i class="fa-solid fa-circle-plus fa-2x"></i>
                <h3 class="icon-pub">NEW PROJECT</h3>
            </div>
        </a>
        <a class="friendLink2" href="manage_project.php">
            <div class="photo"><i class="fa-solid fa-file-audio fa-2x"></i>
                <h3 class="icon-pub">MANAGE</h3>
            </div>
        </a>
        <?php


        foreach ($userProjects as $item) { ?>
            <a class="friendLink2" href="user_project.php?id=<?= $item->id; ?>">
                <div class="photo2">
                    <!-- <i class="fa-solid fa-music fa-2x"></i> -->
                <img class="cover" src="<?= $item->cover; ?>" link="">
                    <h3 class="project-name"><?= $item->name; ?></h3>
                </div>
            </a>
        <?php
            }
        ?>
    </div>
    <!-- <select name="event" id="event">
        <?php


        foreach ($userProjects as $item) {
            echo "<option class='center-box' value='{$item->id}'>{$item->name}</option>";
        }
        ?>
    </select> -->
</body>

</html>