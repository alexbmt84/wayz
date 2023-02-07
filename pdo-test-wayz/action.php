<?php

    require 'core/init.php'; // ajout connexion bdd 
    require_once 'header.php';
    require_once 'core/post.class.php';
    require_once 'core/like.class.php';

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

    $posts = Post::findFriendsPosts($user_data->id);

    $get_all_friends = $frnd_obj->get_all_friends($_SESSION['user-id'], true);



    $test_orig_image = "https://www.nicepng.com/png/detail/73-730154_open-default-profile-picture-png.png";
    $my_deafult_image = "https://www.nicepng.com/png/detail/73-730154_open-default-profile-picture-png.png";
    $checkImg = User::check_image_exists($test_orig_image, $my_deafult_image = 'default.png');

	if(isset($_GET['t'],$_GET['id']) AND !empty($_GET['t']) AND !empty($_GET['id'])) {

        $db_obj = new Database();
        $db_connection = $db_obj->dbConnection();

        $getid = (int) $_GET['id'];
        $gett = (int) $_GET['t'];
        $sessionid = $user_data->id;

        $check = $db_connection->prepare('SELECT post_id FROM posts WHERE post_id = ?');
        $check->execute(array($getid));

        if($check->rowCount() == 1) {

            if($gett == 1) {

                $check_like = $db_connection->prepare('SELECT id FROM likes WHERE id_post = ? AND user_id = ?');
                $check_like->execute(array($getid,$sessionid));

                $del = $db_connection->prepare('DELETE FROM dislikes WHERE id_post = ? AND user_id = ?');
                $del->execute(array($getid,$sessionid));

                if($check_like->rowCount() == 1) {

                    $del = $db_connection->prepare('DELETE FROM likes WHERE id_post = ? AND user_id = ?');
                    $del->execute(array($getid,$sessionid));

                } else {

                    $ins = $db_connection->prepare('INSERT INTO likes (id_post, user_id) VALUES (?, ?)');
                    $ins->execute(array($getid, $sessionid));

                }
                
                
	        }
            
	        header('Location: feed.php#like');
            
	    } else {
            
            exit('Erreur fatale. <a href="feed.php">Revenir à l\'accueil</a>');
            
	    }
        
	} else {
        
        exit('Erreur fatale. <a href="feed.php">Revenir à l\'accueil</a>');
        
	}
    // IF USING A DISLIKE BUTTON...
    
    // } elseif($gett == 2) {

    //     $check_like = $bdd->prepare('SELECT id FROM dislikes WHERE id_article = ? AND id_membre = ?');
    //     $check_like->execute(array($getid,$sessionid));

    //     $del = $bdd->prepare('DELETE FROM likes WHERE id_article = ? AND id_membre = ?');
    //     $del->execute(array($getid,$sessionid));

    //     if($check_like->rowCount() == 1) {

    //         $del = $bdd->prepare('DELETE FROM dislikes WHERE id_article = ? AND id_membre = ?');
    //         $del->execute(array($getid,$sessionid));

    //     } else {

    //         $ins = $bdd->prepare('INSERT INTO dislikes (id_article, id_membre) VALUES (?, ?)');
    //         $ins->execute(array($getid, $sessionid));

    //     }