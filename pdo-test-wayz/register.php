<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

<?php

    if (isset($_POST["email"]) && $_POST["email"] != "") {
        if (isset($_POST["password"]) && $_POST["password"] != "" && strlen($_POST["password"]) > 7) {
            if ($_POST["password"] == $_POST["password_retype"]) {
                if (isset($_POST["pseudo"]) && $_POST["pseudo"] != "") {

                        // TOUT EST OK //
                        require_once "core/user.php";

                        $newUser = new User();
                        $options = ["cost" => 12];

                        $newUser-> email = $_POST["email"];
                        $newUser-> password = password_hash($_POST["password"], PASSWORD_BCRYPT, $options) ;
                        $newUser-> pseudo = $_POST["pseudo"];
                        
                        if (User::exists($_POST["email"])) {

                        } else {
                            $newUser-> creerCompte();
                        }

                } else {
                    // Pas de prénom renseigné !
                    require_once "core/alert.class.php";
                    $msg = new Alert();
                    $msg -> setTitle("Error");
                    $msg -> setBody("You need to choose a pseudo.");
                }

            } else {
                // Les mots de passe ne correspondent pas!
                require_once "core/alert.class.php";
                $msg = new Alert();
                $msg -> setTitle("Error");
                $msg -> setBody("Passwords doesn't match !");
            }

        } else {
            // Pas de mot de passe rensigné !
            require_once "core/alert.class.php";
            $msg = new Alert();
            $msg -> setTitle("Error");
            $msg -> setBody("You need to set a password.");

        }

    } else {
        // Pas de mail rensigné !
        require_once "core/alert.class.php";
        $msg = new Alert();
        $msg -> setTitle("Error");
        $msg -> setBody("You need to set an email.");

    }






















    if (isset($_GET['reg_err'])) {
        $err = htmlspecialchars($_GET['reg_err']);

        switch ($err) {
            case 'success':
    ?>
                <div class="alert alert-success">
                    <strong>Succès </strong>inscription réussie !
                </div>
            <?php
                break;

            case 'password':
            ?>
                <div class="alert alert-danger">
                    <strong>Erreur </strong>mot de passe différent
                </div>
            <?php
                break;

            case 'email':
            ?>
                <div class="alert alert-danger">
                    <strong>Erreur </strong>email non valide
                </div>
            <?php
                break;

            case 'email_length':
            ?>
                <div class="alert alert-danger">
                    <strong>Erreur </strong>email trop long
                </div>
            <?php
                break;

            case 'pseudo_length':
            ?>
                <div class="alert alert-danger">
                    <strong>Erreur </strong>pseudo trop long
                </div>
            <?php
                break;

            case 'already':
            ?>
                <div class="alert alert-danger">
                    <strong>Erreur </strong>compte déjà existant
                </div>
    <?php
        }
    }
    ?>

    <div class="containerLogo">
        <img id="logo" src="img/g8.svg" alt="">
    </div>
    <div class="registerTitle">
        <h1 class="h1Reg">Sign up to start working and share music with your friends </h1>
    </div>
    <form action="register_conf.php" method="post" class="loginForm2">
        <input class="text-input1" type="text" id="pseudo" name="pseudo" placeholder="Pseudo" required>
        <input class="text-input3" type="text" id="email" name="email" placeholder="Email" required>
        <input class="text-input3" type="password" id="password" name="password" placeholder="Password" required>
        <input class="text-input3" type="password" id="confirm_password" name="password_retype" placeholder="Confirm password" required>
        <!-- <input type="submit" value="SIGN UP" class="btn3"> -->
        <!-- <input type="button" value="BACK" onclick="window.location.href='index.php';" class="btn3"> -->
        <nav>
            <ul>
                <button type="submit" class="invisibleBtn">
                    <li>sign up<span></span>
                                <span></span>
                                <span></span>
                                <span></span>   
                    </li>
                </button><br>
                <button class="invisibleBtn" onclick="window.location.href='index.php';">
                    <li>back<span></span>
                            <span></span>
                            <span></span>
                            <span></span>   
                    </li>
                </button>
            </ul>
        </nav>
    </form>

</body>

</html>