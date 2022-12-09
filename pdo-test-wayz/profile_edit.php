<?php

require 'core/init.php';// ajout connexion bdd 

if(isset($_SESSION['user-id']) && isset($_SESSION['user'])){
    $user_data = $user_obj->findByiD($_SESSION['user-id']);
    if($user_data ===  false){
        header('Location: logout.php');
        exit;
    }
    // FETCH ALL USERS WHERE ID IS NOT EQUAL TO MY ID
    $all_users = $user_obj->all_users($_SESSION['user-id']);
}
else{
    header('Location: logout.php');
    exit;
}

// On récupere les données de l'utilisateur
$connexion = new PDO("mysql:host=localhost;port=3306;dbname=testwayz;charset=utf8","root","");
$req = $connexion->prepare('SELECT * FROM utilisateurs WHERE token = ?');
$req->execute(array($_SESSION['user']));
$user = $req->fetch();

if(isset($_SESSION['user'])) {

    require_once 'header.php';

$test_orig_image = "https://www.nicepng.com/png/detail/73-730154_open-default-profile-picture-png.png";
$my_deafult_image = "https://www.nicepng.com/png/detail/73-730154_open-default-profile-picture-png.png";
$checkImg = User::check_image_exists($test_orig_image, $my_deafult_image = 'default.png');


    // Changement de pseudo


    if (isset($_POST['newpseudo']) and !empty($_POST['newpseudo']) and $_POST['newpseudo'] != $user['pseudo']) {
        $pseudo = $user['pseudo'];
        $id = $_SESSION['user-id'];
        $newpseudo = htmlspecialchars($_POST['newpseudo']);
        $pseudolength = strlen($newpseudo); 

        if ($pseudolength <= 255) {
            $reqpseudo = $connexion->prepare("SELECT * FROM utilisateurs WHERE pseudo = ? AND id != ?");
            // $reqpseudo->execute(array($pseudo));
            $reqpseudo->execute(array($pseudo, $id));
            $pseudoexist = $reqpseudo->rowCount();

            if ($pseudoexist == 0) {
                $insertpseudo = $connexion->prepare('UPDATE utilisateurs SET pseudo = ? WHERE id = ?');
                $insertpseudo->execute(array($newpseudo, $id));
                header('Location: landing.php?id=' . $id);
            } else {
                $msg = "Ce pseudo est déjà pris.";
            }
        } else {
            $msg = "Votre pseudo ne doit pas dépasser 100 caractères.";
        }
    }
}

if (isset($_POST['newmail']) and !empty($_POST['newmail']) and $_POST['newmail'] != $user['email']) {
    $email = $user['email'];
    $id = $_SESSION['user-id'];
    $newmail = htmlspecialchars($_POST['newmail']);
    $newmail = strtolower($newmail);

    if (filter_var($newmail, FILTER_VALIDATE_EMAIL)) {
        $reqmail = $connexion->prepare("SELECT * FROM utilisateurs WHERE email = ? AND id!= ?");
        $reqmail->execute(array($mail, $id));
        $mailexist = $reqmail->rowCount();

        if ($mailexist == 0) {
            $insertmail = $connexion->prepare('UPDATE utilisateurs SET email = ? WHERE id = ?');
            $insertmail->execute(array($newmail, $id));
            header('Location: landing.php?id=' . $id);
        } else {
            $msg = "Cette adresse mail est déjà utilisée !";
        }
    } else {
        $msg = "Votre email n'est pas valide.";
    }
}


// NOM //


if (isset($_POST['firstname']) and !empty($_POST['firstname'])) {
    $nom = $user['prenom'];
    $id = $_SESSION['user-id'];
    $firstName = htmlspecialchars($_POST['firstname']);

    if ($firstName <= 200) {
        $reqFirstname = $connexion->prepare("SELECT * FROM utilisateurs WHERE prenom = ? AND id != ?");
        // $reqpseudo->execute(array($pseudo));
        $reqFirstname->execute(array($nom, $id));
        $firstNameexist = $reqFirstname->rowCount();

        $insertFirstname = $connexion->prepare('UPDATE utilisateurs SET prenom = ? WHERE id = ?');
            $insertFirstname->execute(array($firstName, $id));
            header('Location: landing.php?id=' . $id);
    } else {
        $msg = "Votre prénom ne peut pas dépasser 200 caractères.";
    }
}


// Prenom //


if (isset($_POST['lastname']) and !empty($_POST['lastname'])) {
    $prenom = $user['nom'];
    $id = $_SESSION['user-id'];
    $lastName = htmlspecialchars($_POST['lastname']);

    if ($lastName <= 200) {
        $reqLastname = $connexion->prepare("SELECT * FROM utilisateurs WHERE nom = ? AND id != ?");
        // $reqpseudo->execute(array($pseudo));
        $reqLastname->execute(array($prenom, $id));
        $LastNameExist = $reqLastname->rowCount();

        $insertLastname = $connexion->prepare('UPDATE utilisateurs SET nom = ? WHERE id = ?');
            $insertLastname->execute(array($lastName, $id));
            header('Location: landing.php?id=' . $id);
    } else {
        $msg = "Votre nom ne peut pas dépasser 200 caractères.";
    }
}


// PAYS //


if (isset($_POST['pays']) and !empty($_POST['pays'])) {
    $location = $user['pays'];
    $id = $_SESSION['user-id'];
    $pays = htmlspecialchars($_POST['pays']);

    if ($pays <= 100) {
        $reqPays = $connexion->prepare("SELECT * FROM utilisateurs WHERE pays = ? AND id != ?");
        // $reqpseudo->execute(array($pseudo));
        $reqPays->execute(array($location, $id));
        $paysExist = $reqPays->rowCount();

        $insertPays = $connexion->prepare('UPDATE utilisateurs SET pays = ? WHERE id = ?');
            $insertPays->execute(array($pays, $id));
            header('Location: landing.php?id=' . $id);
    } else {
        $msg = "Désolé, impossible de dépasser 100 caractères.";
    }
}


// Téléphone // 


if (isset($_POST['telephone']) and !empty($_POST['telephone'])) {
    $numero = $user['telephone'];
    $id = $_SESSION['user-id'];
    $telephone = htmlspecialchars($_POST['telephone']);

    if ($telephone <= 200000000000) {
        $reqTel = $connexion->prepare("SELECT * FROM utilisateurs WHERE telephone = ? AND id != ?");
        // $reqpseudo->execute(array($pseudo));

        $insertTel = $connexion->prepare('UPDATE utilisateurs SET telephone = ? WHERE id = ?');
            $insertTel->execute(array($telephone, $id));
            header('Location: profile_edit.php?id=' . $id);
    } else {
        $msg = "Désolé, impossible de dépasser 200 caractères.";
    }
}


// Ville //


if (isset($_POST['ville']) and !empty($_POST['ville'])) {
    $city = $user['ville'];
    $id = $_SESSION['user-id'];
    $ville = htmlspecialchars($_POST['ville']);

    if ($ville <= 200) {
        $reqVille = $connexion->prepare("SELECT * FROM utilisateurs WHERE ville = ? AND id != ?");
        // $reqpseudo->execute(array($pseudo));
        $reqVille->execute(array($city, $id));
        $villeExist = $reqVille->rowCount();

        $insertVille = $connexion->prepare('UPDATE utilisateurs SET ville = ? WHERE id = ?');
            $insertVille->execute(array($ville, $id));
            header('Location: landing.php?id=' . $id);
    } else {
        $msg = "Désolé, impossible de dépasser 200 caractères.";
    }
}


// Change password


if(isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2'])) {
    $password = $user['password'];
    $id = $_SESSION['user-id'];
    $cost = ['cost' => 12];
    $mdp1 = ($_POST['newmdp1']);
    $mdp2 = ($_POST['newmdp2']);

    if($mdp1 == $mdp2) {
        $mdp1 = password_hash($mdp1, PASSWORD_BCRYPT, $cost);
        $insertmdp = $connexion->prepare("UPDATE utilisateurs SET password = ? WHERE id = ?");
        $insertmdp->execute(array($mdp1, $id));
        header('Location: landing.php?id='.$id);
    } else {
       echo "<p>Les mots de passe doivent être identiques.</p>" ;
    }
}


// Change profile picture


if(!empty($_FILES) && isset($_FILES['avatar'])) {
    $tailleMax = 2097152;
    $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
    $id = $_SESSION['user-id'];

    if ($_FILES['avatar']['error'] == UPLOAD_ERR_OK AND $_FILES['avatar']['size'] <= $tailleMax) {


        $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
        if (in_array($extensionUpload, $extensionsValides)) {
            $chemin = "membres/avatars/".$_SESSION['user-id'].".".$extensionUpload;
            $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
            if($resultat) {
                $updateAvatar = $connexion->prepare('UPDATE utilisateurs SET avatar = :avatar WHERE id = :id');
                $updateAvatar->execute(array(
                    "avatar" => $_SESSION['user-id'].".".$extensionUpload,
                    "id" => $_SESSION['user-id']
                ));
            header('Location: landing.php?id='.$id);
            } else {
                echo "<p class='err'>Erreur durant l'importation de la photo de profil.";
            }
        } else {
            echo "<p class='red'>Pas le bon format d'image.</p>";
        }
    } else {
        echo "<p class='err'>Votre photo de profil n'est pas valide.</p>";
    }
}


// Changement de bio

if (isset($_POST['bio']) and !empty($_POST['bio'])) {
    $bio = $user['bio'];
    $id = $_SESSION['user-id'];
    $bio = htmlspecialchars($_POST['bio']);

    if ($bio <= 200) {
        $reqBio = $connexion->prepare("SELECT * FROM utilisateurs WHERE bio = ? AND id != ?");
        // $reqpseudo->execute(array($pseudo));
        $reqBio->execute(array($bio, $id));
        $bioExist = $reqBio->rowCount();

        $insertBio = $connexion->prepare('UPDATE utilisateurs SET bio = ? WHERE id = ?');
            $insertBio->execute(array($bio, $id));
            header('Location: landing.php?id=' . $id);
    } else {
        $msg = "Désolé, impossible de dépasser 200 caractères.";
    }
}


// Change Birthday Date


if (isset($_POST['date']) and !empty($_POST['date'])) {
    $date_n = $user['date'];
    $id = $_SESSION['user-id'];
    $date = htmlspecialchars($_POST['date']);

    if ($date <= 200) {
        $reqDate = $connexion->prepare("SELECT * FROM utilisateurs WHERE date_naissance = ? AND id != ?");
        // $reqpseudo->execute(array($pseudo));
        $reqDate->execute(array($date_n, $id));
        $dateExist = $reqDate->rowCount();

        $insertDate = $connexion->prepare('UPDATE utilisateurs SET date_naissance = ? WHERE id = ?');
            $insertDate->execute(array($date, $id));
            header('Location: landing.php?id=' . $id);
    } else {
        $msg = "Désolé, impossible de dépasser 200 caractères.";
    }
}

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
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js' defer></script><script  src="./script.js" defer></script>
    <script src="js/photo.js" defer></script>


    
</head>
<body>
    <form class="formEdit" action="" method="POST" enctype="multipart/form-data">
        <?php 
            if(!empty($user['avatar'])) { 
        ?>     
        
        <div class="profile-pic">
            <label for="file" class="-label">
                <span class="glyphicon glyphicon-camera"></span>
                <span>Change Image</span>
            </label>
            <input id="file" type="file" name="avatar" onchange="loadFile(event);"/>
            <img src="membres/avatars/<?php echo $user['avatar']; ?>" id="output" width="150px" />
        </div>

            <?php } else { ?>
                <div class="profile-pic">
            <label for="file" class="-label">
                <span class="glyphicon glyphicon-camera"></span>
                <span>Change Image</span>
            </label>
            <input id="file" type="file" name="avatar" onchange="loadFile(event);"/>
            <img src="<?= $checkImg; ?>" id="output" width="150px" />
        </div>

        <?php } ?>
        <!-- <button type="button" class="btn3">
            <label for="file" class="label-file">Choose an image</label>
        </button> -->
        
        <!-- <input id="file" class="inputFile" type="file" name="avatar"> -->
        <input class="text-input1" type="text" name="newpseudo" placeholder="Pseudo" value="<?php echo $user['pseudo'];?>">
        <input class="text-input3" type="email" name="newmail" placeholder="Email" value="<?php echo $user['email'];?>">
        <input class="text-input3" type="password" name="newmdp1" placeholder="Password">
        <input class="text-input3" type="password" name="newmdp2" placeholder="Confirm your password">
        <input class="text-input3" type="text" name="firstname" placeholder="First name" value="<?php echo $user['prenom'];?>">
        <input class="text-input3" type="text" name="lastname" placeholder="Last name" value="<?php echo $user['nom'];?>">
        <input class="text-input3" type="date" name="date">
        <input class="text-input3" type="text" name="pays" placeholder="Pays" value="<?php echo $user['pays'];?>">
        <input class="text-input3" type="text" name="ville" placeholder="Ville" value="<?php echo $user['ville'];?>">
        <input class="text-input3" type="text" name="telephone" placeholder="Téléphone" value="<?php echo $user['telephone'];?>">
        <input class="text-input3" type="text" name="bio" placeholder="<?php echo $user['bio'];?>">
        <input class="inputSubmit" type="submit" value="Update">  

<!--  
        <div class="container">
            <div class="avatar-upload">
                <div class="avatar-edit">
                    <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" />
                    <label for="imageUpload"></label>
                </div>
                <div class="avatar-preview">
                    <img class="avatar" id="imagePreview" src="membres/avatars/<?php echo $user['avatar']; ?>" width="150px">
                </div>
            </div>
        </div> -->
    </form>

    <script src="js/img.js"></script>
</body>

</html>