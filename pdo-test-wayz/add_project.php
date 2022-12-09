<?php

require 'core/init.php'; // ajout connexion bdd 
require_once 'header.php';
require_once 'core/project.class.php';

// phpinfo();

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

$test_orig_image = "http://developer-assets.ws.sonos.com/doc-assets/portalDocs-sonosApp-defaultArtAlone.png";
$my_deafult_image = "http://developer-assets.ws.sonos.com/doc-assets/portalDocs-sonosApp-defaultArtAlone.png";
$checkImg = User::check_image_exists($test_orig_image, $my_deafult_image = 'default.png');

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/landing.css">
    <script src="js/photo.js" defer></script>

</head>

<body>

    <?php
    if (isset($_POST["name"]) && $_POST["name"] != "") {
        if (isset($_FILES['cover'])) {

            // TOUT EST OK //

            require_once 'core/project.class.php';

            $pieceJointe = "";
            // Traitement de l'upload des pièces jointes si nécessaire ...
            if ($_FILES["cover"]["name"] != "") {
                $uploadDir = "projects/covers/";

                // Création d'un dossier pour les téléversements si inexistant...
                if (!file_exists($uploadDir))
                    mkdir($uploadDir);

                // chemin vers le fichier téléversé temporaire
                $tmpFile = $_FILES["cover"]["tmp_name"];
                $trueName = $_FILES["cover"]["name"];
                $pieceJointe = $uploadDir . basename($trueName);
                
                // Les types de fichiers autorisés...
                $autorizedTypes = ["image/png", "image/jpg", "image/jpeg", "application/pdf"];
                $typeMime = strtolower(mime_content_type($tmpFile));

                // Vérifier le type de fichier envoyé avant de le déplacer vers son emplacement
                if (in_array($typeMime, $autorizedTypes)) {
                    if (is_uploaded_file($tmpFile)) {
                        if ($_FILES["cover"]["size"] < 10000000) {
                            move_uploaded_file($tmpFile, $pieceJointe);
                        } else {
                            echo "Your file is too big !";
                            die();
                        }
                    }
                } else {
                    echo "Invalid format !";
                    die();
                }
            }

            $project = new Project();

            $project->name = $_POST["name"];
            $project->cover = $pieceJointe;
            $project->user = $user_data->id;

            $project->addProject();

            header('Location: projects.php');

        } else {

            require_once "core/alert.class.php";
            $msg = new Alert();
            $msg->setTitle("Error");
            $msg->setBody("Invalid cover.");
            die();
        }
    }
    ?>

    <h1 class="subtitle">NEW PROJECT</h1>
    <form class="projectForm" action="" method="post" enctype="multipart/form-data">
        <div class="projectDiv">
            <label for="file" class="-label">
                <span class="glyphicon glyphicon-camera"></span>
                <span>Project Cover</span>
            </label>
            <input id="file" type="file" name="cover" onchange="loadFile(event);" />
            <img class="imgProject" src="<?= $checkImg; ?>" id="output" width="150px" />
        </div>

        <input class="text-input1" type="text" name="name" placeholder="Title" required>
        <nav>
            <ul>
                <button type="submit" class="invisibleBtn">
                    <li>create<span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </li>
                </button><br>
            </ul>
        </nav>
    </form>
    <script src="js/img.js"></script>
</body>

</html>