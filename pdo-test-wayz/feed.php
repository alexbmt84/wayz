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

    <!-- <h1 class="subtitle">FEED</h1> -->

    <form class="postForm" action="add_post.php" method="post" enctype="multipart/form-data">

        <div class="postFormHeader">

            <?php 
            
                $image = $user_data->avatar;
    
            if (empty($image)) { ?>
    
                <div class="divAvatar6">
                    <a href=""><img src="<?= $checkImg; ?>" class="avatar6" alt="" id="output" width="150px" /></a>
                </div>
    
            <?php
    
            } else { ?>
    
                <div class="divAvatar6">
                    <a href="" class="friendLink2"><img class="avatar6" src="membres/avatars/<?= $user_data->avatar; ?>" link="" width="150px"></a>
                </div>
    
            <?php } ?>

            <h2 class="postPseudo"><?= $user_data->pseudo; ?></h2>

        </div>


        <textarea class="text-post" name="message" placeholder="Write a post..." ></textarea>
        
        <div class="postR">
            
            <!-- <button type="button" class="multifile" name="" onclick="importData()"><i id="multIcon" class="fa-solid fa-photo-film"></i></button> -->
            <input id="file" type="file" name="attachment" onchange=""/>

            <nav>
                <ul>
                    <button id="postBtn" type="submit" class="invisibleBtn">

                        <li><i class="fa-regular fa-paper-plane"></i><span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </li>

                    </button><br>

                </ul>
            </nav>
        </div>
    </form>

    <!-- Affichage des Posts -->

    <?php

        foreach($posts as $post) {     
    ?>
    
            <div class="posts">

                <div class="postHeader">

                    <?php 
    
                        $image = $post->avatar;
    
                        if (empty($image)) { ?>
    
                            <div class="divAvatar6">
                                <a href=""><img src="<?= $checkImg; ?>" class="avatar6" alt="" id="output" width="150px" /></a>

                                <?php 

                                    if ($post->online == 1) { ?>

                                        <div class="online-dot2"></div>

                                    <?php 

                                    } else { 

                                    ?>

                                    <div class="offline-dot2"></div>

                                    <?php 

                                    } 

                                ?>
                            </div>
    
                    <?php
    
                        } else { ?>
    
                            <div class="divAvatar6">
                                <a href="" class="friendLink2"><img class="avatar6" src="membres/avatars/<?= $post->avatar; ?>" link="" width="150px"></a>

                                <?php 

                                    if ($post->online == 1) { ?>

                                        <div class="online-dot2"></div>

                                    <?php 

                                    } else { 

                                    ?>

                                    <div class="offline-dot2"></div>

                                    <?php 

                                    } 

                                ?>

                            </div>
    
                    <?php } ?>
    
                    <h2 class="postPseudo"><?= $post->pseudo; ?></h2>

                    <i id="more" class="fa-solid fa-ellipsis"></i>

                </div>
                
                <div class="textContent">

                    <h2 class="postMessage"><?= $post->message; ?></h2>

                </div>

                <?php

                    if ($post->attachment) { ?>

                        <div class="postImgContainer">
                            
                            <img class="postImg" src="<?= $post->attachment; ?>" alt="" width="150px">

                        </div>

                    <?php 
                        }
                    ?>

                <hr id="line"/>

                <div class="postReactions">

                    <?php

                        $sql = "SELECT COUNT(*) FROM likes WHERE user_id = :user_id AND id_post = :id_post;";
                        $sql2 = "SELECT COUNT(*) FROM likes WHERE id_post = :id_post;";

                        $id = $user_data->id;
                        $post_id = $post->post_id;

                        $check_like = $db_connection->prepare($sql);
                        $count_like = $db_connection->prepare($sql2);

                        $check_like-> bindParam(":user_id", $id, PDO::PARAM_INT);
                        $check_like-> bindParam(":id_post", $post_id, PDO::PARAM_INT);                        
                        $count_like-> bindParam(":id_post", $post_id, PDO::PARAM_INT);                        

                        $check_like-> execute();
                        $count_like-> execute();

                        $like_count = $check_like->fetchColumn();
                        $likesNumber = $count_like->fetchColumn();

                        if ($likesNumber == 0) {
                            $likesNumber = "";
                        }

                        if ($like_count == 1) {
                    
                    ?>
                            <div class="likesContainer">

                                <a href="action.php?t=1&id=<?= $post->post_id ?>"><i id="like2" class="like fa-solid fa-heart"></i></a>
                                <h3 id="likeNumber"><?= $likesNumber; ?></h3>

                            </div>
                    <?php 
                        
                        } else { 
                    
                    ?>

                            <div class="likesContainer">

                                <a href="action.php?t=1&id=<?= $post->post_id ?>"><i id="like" class="like fa-regular fa-heart" onclick=""></i></a>
                                <h3 id="likeNumber"><?= $likesNumber; ?></h3>

                            </div>
                    <?php

                        }

                    ?>
                    
                <i id="comment" tabindex="1" class="comment-icon fa-regular fa-comment"></i>
                <i id="share" class="fa-regular fa-share-from-square"></i>
                <i id="collaboration" class="fa-regular fa-handshake"></i>

                </div>
                
                <input class="comment-input" type="text">
                <button class="replies">Read comments</button>

                <b><p class="postDate"><?= $post->postedAt->format('d-m-Y H:i:s'); ?></p></b>

                
            </div>

        <?php

        }

        ?>
    <!-- <script src="js/like.js"></script> -->
    <script src="js/fileTwo.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <script type="text/javascript">
        // jQuery Document
        $(document).ready(function() {
            $(".comment-icon").click(function() {
            $(".comment-input").toggleClass("show-comment-input");
            });
        });
    </script>
</body>

</html>