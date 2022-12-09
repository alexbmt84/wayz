<?php 

require 'core/init.php'; // Securiser plus ????

    // Si les variables existent et qu'elles ne sont pas vides
    if(!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_retype']))
    {
        // Patch XSS
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $password_retype = htmlspecialchars($_POST['password_retype']);

        // On vérifie si l'utilisateur existe
        $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");
        $check = $connexion->prepare('SELECT pseudo, email, password FROM utilisateurs WHERE email = ?');
        $check->execute(array($email));
        $data = $check->fetch();
        $row = $check->rowCount();

        $email = strtolower($email); // on transforme toute les lettres majuscule en minuscule pour éviter que Foo@gmail.com et foo@gmail.com soient deux compte différents ..
        
        // Si la requete renvoie un 0 alors l'utilisateur n'existe pas 
        if($row == 0){ 
            if(strlen($pseudo) <= 100){ // On verifie que la longueur du pseudo <= 100
                if(strlen($email) <= 100){ // On verifie que la longueur du mail <= 100
                    if(filter_var($email, FILTER_VALIDATE_EMAIL)){ // Si l'email est de la bonne forme
                        if($password === $password_retype){ // si les deux mdp saisis sont bon

                            // On hash le mot de passe avec Bcrypt, via un coût de 12
                            $cost = ['cost' => 12];
                            $password = password_hash($password, PASSWORD_BCRYPT, $cost);
                            
                            // On stock l'adresse IP
                            $ip = $_SERVER['REMOTE_ADDR']; 
                             /*
                              ATTENTION
                              Verifiez bien que le champs token est présent dans votre table utilisateurs, il a été rajouté APRÈS la vidéo
                              le .sql est dispo pensez à l'importer ! 
                              ATTENTION
                            */
                            // On insère dans la base de données
                            $insert = $connexion->prepare('INSERT INTO utilisateurs(pseudo, email, password, token) VALUES(:pseudo, :email, :password, :token)');
                            $insert->execute(array(
                                'pseudo' => $pseudo,
                                'email' => $email,
                                'password' => $password,
                                'token' => bin2hex(openssl_random_pseudo_bytes(64))
                            ));

                            
                        $conn = mysqli_connect("localhost", "root", "", "testwayz");

                        
                        // $sql = "SELECT * FROM utilisateurs WHERE pseudo=':pseudo' AND email=':email'";
                        //     $result = mysqli_query($conn, $sql);

                        //     if (mysqli_num_rows($result) > 0) {
                        //         while ($row = mysqli_fetch_assoc($result)) {
                        //             $userid = $row['id'];
                        //             $sql = "INSERT INTO profileimg (userid, status)
                        //             VALUES ('$userid', 1)";
                        //         }
                        //     }

                            // On redirige avec le message de succès
                            header('Location:register.php?reg_err=success');
                            die();
                        }else{ header('Location: register.php?reg_err=password'); die();}
                    }else{ header('Location: register.php?reg_err=email'); die();}
                }else{ header('Location: register.php?reg_err=email_length'); die();}
            }else{ header('Location: register.php?reg_err=pseudo_length'); die();}
        }else{ header('Location: register.php?reg_err=already'); die();}
    }