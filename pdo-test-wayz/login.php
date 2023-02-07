<?php
session_start();
require_once "core/database.class.php";
?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/main.css">

    <?php
        if (isset($_POST["email"]) && isset($_POST["password"]) && $_POST["email"] != "" && $_POST["password"] != "") {
            require_once "./core/user.php";

            $email = $_POST["email"];
            $password = $_POST["password"];
            $email = trim($email);
            $email = strtolower($email); // email transformé en minuscule
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $currentUser = User::login($email, $password);

                if ($currentUser->id > 0) {
                    $_SESSION["user-id"] = $currentUser->id;
                    $_SESSION["user"] = $currentUser->token;

                    // L'utilisateur est connecté...

                    $db_obj = new Database();
                    $db_connection = $db_obj->dbConnection();
                    $user_id = $_SESSION["user-id"];

                    $sql = "UPDATE utilisateurs SET online = 1 WHERE id = :user_id";

                    $stmt = $db_connection->prepare($sql);
                    $stmt->execute(array(':user_id' => $user_id));

                    header('Location: landing.php?page=home');

                } else {

                    header('Location: login.php?login_err=password');

                } 

            } else {

                echo "Pas le bon format d'email";

            }
        }
    ?>

    </head>

    <body>
        <?php
            if (isset($_GET['login_err'])) {
                $err = htmlspecialchars($_GET['login_err']);

                switch ($err) {
                    case 'password':
        ?>
                    <div class="alert alert-danger">
                        <strong>Erreur</strong> mot de passe incorrect
                    </div>
                    <?php
                        break;

                        case 'email':
                    ?>
                    <div class="alert alert-danger">
                        <strong>Erreur</strong> email incorrect
                    </div>
                    <?php
                        break;

                        case 'already':
                    ?>
                    <div class="alert alert-danger">
                        <strong>Erreur</strong> compte non existant
                    </div>
                    <?php
                        break;
                }
            }
                    ?>

        <div class="containerLogo">
            <img id="logo" src="img/g8.svg" alt="">
        </div>
        <form class="loginForm" action="" method="post">
            <input class="text-input1" type="email" name="email" placeholder="Email" required>
            <input class="text-input2" type="password" name="password" placeholder="Password" required>
            <nav>
            <ul>
                <button type="submit" class="invisibleBtn">
                    <li>login<span></span>
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