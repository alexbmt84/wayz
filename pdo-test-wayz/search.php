<?php
require 'core/init.php'; // J'ai effacé core user pour le remplacer par init.php //
require 'header.php';

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

if (isset($_GET['query'])) {
    $resultats = User::queryUser($_GET["query"]);
}

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
    <title>Recherche</title>
    <link rel="stylesheet" href="">
</head>

<body>
    <h2 class="h2Landing">Search results for : <?= $_GET['query']; ?></h2>
    <?php

    if (isset($resultats)) :
        foreach ($resultats as $user) :
    ?>
            <?php $image = $user->avatar;
            if (empty($image)) { ?>
                <div class="divAvatar">
                    <a href="profile_edit.php"><img src="<?= $checkImg; ?>" class="avatar" alt="" id="output" width="150px" /></a>
                </div>
            <?php
            } else {
            ?>

                <div class="divAvatar">
                    <a href="profile_edit.php"><img class="avatar" src="membres/avatars/<?= $user->avatar; ?>" link="" width="150px"></a>
                </div>
            <?php } ?>
            <h3 class="profileInfos">
                <?= $user->prenom; ?> <?= $user->nom; ?><br>
                <?= $user->pseudo; ?><br>
                <?= $user->email; ?><br>
                <span><a class="friendLink2" href="user_profile.php?id=<?= $user->id; ?>" class="see_profileBtn">See profile</a>

            </h3>
    <?php
        endforeach;
    endif;
    ?>
</body>

</html>