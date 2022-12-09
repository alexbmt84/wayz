<?php
require 'core/init.php';

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
require_once 'header.php';

$test_orig_image = "https://www.nicepng.com/png/detail/73-730154_open-default-profile-picture-png.png";
$my_deafult_image = "https://www.nicepng.com/png/detail/73-730154_open-default-profile-picture-png.png";
$checkImg = User::check_image_exists($test_orig_image, $my_deafult_image = 'default.png');


// TOTAL REQUESTS
$get_req_num = $frnd_obj->request_notification($_SESSION['user-id'], false);
// TOTAL FRIENDS
$get_frnd_num = $frnd_obj->get_all_friends($_SESSION['user-id'], false);
$get_all_req_sender = $frnd_obj->request_notification($_SESSION['user-id'], true);

$get_all_friends = $frnd_obj->get_all_friends($_SESSION['user-id'], true);

$check_req_receiver = $frnd_obj->am_i_the_req_receiver($_SESSION['user-id'], $user_data->id);


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo  $user_data->username;?></title>
    <link rel="stylesheet" href="css/landing.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
</head>
    <body>
        <!-- <ul class="friendList">
                <li class="divList"><a href="notifications.php" rel="noopener noreferrer" class="friendLink">REQUESTS : <span class="badge <?php
                    if($get_req_num > 0){
                        echo 'redBadge';
                    }
                    ?>"><?php echo $get_req_num;?></span></a></li>
                <a class="friendLink"href="friends.php" rel="noopener noreferrer">FRIENDS : <span class="badge"><?php echo $get_frnd_num;?></span></a>
                </ul> -->
        <h1 class="subtitle">FRIENDS</h1>
        <div class="containerFriends">
                <div class="infos_container"></div>
            <div class="all_users">
                <h2 class="h2Landing">Friend requests : <?php echo $get_req_num;?></h2>
                <div class="usersWrapper">
                    <?php
                    if($get_req_num > 0){
                        foreach($get_all_req_sender as $row){
                            echo '<div class="user_box">
                                    <div class="divAvatar">
                                        <img class="avatar"src="membres/avatars/'.$row->avatar.'" alt="Profile image width="150px">
                                    </div>
                                    <div class="user_info">
                                        <span>'.$row->pseudo.'</span>
                                        <span><a href="user_profile.php?id='.$row->sender.'" class="see_profileBtn">See profile</a>
                                    </div>
                                </div>';
                        }
                    }
                    else{
                        echo '<h2 class="h2Friend">You have no friend requests!</h2>';
                    }
                    ?>


    <div class="all_users">
        <h2 class="h2Landing">All friends : <?php echo $get_frnd_num;?></h2>
        <div class="usersWrapper">

            <?php
                if($get_frnd_num > 0){
                    foreach($get_all_friends as $row){ 
            ?>

            <div class="user_box">
            <?php 
             if(!empty($row->avatar)) { ?>    
                <div class="divAvatar">
                <a href="user_profile.php?id=<?=$row->id;?>"><img class="avatar" src="membres/avatars/<?=$row->avatar;?>" alt="Profile image"></a>
                </div>
            <?php } else { ?>
                <div class="divAvatar">
                <a href="user_profile.php?id=<?=$row->id;?>"><img class="avatar" src="<?= $checkImg; ?>" alt="Profile image"></a>
                </div>
                <?php } ?>    
                <div class="user_info">
                    <a href="user_profile.php?id=<?=$row->id;?>" class="friendLink2"><h3><?= $row->pseudo; ?></h3></a>
                    <span><a class="friendLink2"href="user_profile.php?id=<?=$row->id;?>" class="see_profileBtn"></a>
                </div> 
            </div>
            
            <?php

                }

                } else {
                    echo '<h2 class="h2Friend">You have no friends!</h2>';
                }
            ?>

        </div>

    </div>

    </body>
</html>