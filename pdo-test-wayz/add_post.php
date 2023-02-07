<?php

require 'core/init.php'; // ajout connexion bdd 
require_once 'header.php';

// phpinfo();

if (isset($_SESSION['user']) && isset($_SESSION['user-id'])) {
    $user_data = $user_obj->findByiD($_SESSION['user-id']);
    if ($user_data ===  false) {
        header('Location: logout.php');
        exit;
    }
}

if (isset($_POST["message"]) && $_POST["message"] != "") {

    // TOUT EST OK //
    require_once 'core/post.class.php';

    $attachment = "";
    $hasAttachment = !empty($_FILES["attachment"]["name"]);

    // Traitement de l'upload des pièces jointes si nécessaire ...

    if ($hasAttachment) {
        
        $uploadDir = "posts/";

        // Création d'un dossier pour les téléversements si inexistant...

        if (!file_exists($uploadDir))
            mkdir($uploadDir);

        // chemin vers le fichier téléversé temporaire

        $tmpFile = $_FILES["attachment"]["tmp_name"];
        $trueName = $_FILES["attachment"]["name"];
        $attachment = $uploadDir . basename($trueName);

        // Les types de fichiers autorisés...

        $autorizedTypes = ["image/png", "image/jpg", "image/jpeg", "application/pdf"];
        $typeMime = strtolower(mime_content_type($tmpFile));

        // Vérifier le type de fichier envoyé avant de le déplacer vers son emplacement

        if (in_array($typeMime, $autorizedTypes)) {
            if (is_uploaded_file($tmpFile)) {
                if ($_FILES["attachment"]["size"] < 2000000) {
                    move_uploaded_file($tmpFile, $attachment);
                } else {
                    echo "Fichier trop gros !";
                }
            }
        } else {
            echo "Type de fichier non autorisé !";
        }
    }

    $newPost = new Post();
    
    $newPost-> message = $_POST["message"];
    $newPost-> attachment = $attachment;
    $newPost-> id = $user_data->id;


    $newPost-> publishPost();

    header("Location: feed.php"); 

} else {
// Pas de date de fin renseignée
require_once "core/model/alert.class.php";
$msg = new Alert();
$msg -> setTitle("Error");
$msg -> setBody("You need to set an event name.");

}

?>