<?php
require 'core/init.php';
require_once 'core/post.class.php';

if (isset($_SESSION['user']) && isset($_SESSION['user-id'])) {

  $user_data = $user_obj->findByiD($_SESSION['user-id']);
  $userProjects = Project::findUserProject($user_data->id);
  $countFriends = Friend::countFriends($user_data->id);
  $posts = Post::findUserPosts($user_data->id);

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
// REQUEST NOTIFICATION NUMBER
$get_req_num = $frnd_obj->request_notification($_SESSION['user-id'], false);
// TOTAL FRIENDS
$get_frnd_num = $frnd_obj->get_all_friends($_SESSION['user-id'], false);

$test_orig_image = "https://www.nicepng.com/png/detail/73-730154_open-default-profile-picture-png.png";
$my_deafult_image = "https://www.nicepng.com/png/detail/73-730154_open-default-profile-picture-png.png";
$checkImg = User::check_image_exists($test_orig_image, $my_deafult_image = 'default.png');


?>

<!-- <h4 class="h4">Bonjour j'apparait</h4> -->

<!--------------------------------------------------------------- SEARCH-------------------------------------------------------------------------->
<!-- --------------------------------------------------------INFOS UTILISATEUR-------------------------------------------------------------------->
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?php echo  $user_data->pseudo; ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="css/landing.css">
</head>

<body> <!-----------------------------------------------------------------NAVBAR----------------------------------------------------------------------->
  <?php
  if (isset($_SESSION['user'])) {
  ?>

    <section class="top-nav">
      <a href="landing.php"><img src="img/g10.svg" class="lgWayz" alt=""></a>
      <div>
        <!-- <h2 class="wayz">Wayz</h2> -->
      </div>
      <input id="menu-toggle" type="checkbox" />
      <label class='menu-button-container' for="menu-toggle">
        <div class='menu-button'></div>
      </label>
      <ul class="menu">
        <li><a id="style-2" class="a" href="landing.php" data-replace="PROFILE">PROFILE</a></li>
        <li><a id="style-2" class="a" href="feed.php" data-replace="FEED" href="">FEED</a></li>
        <li><a id="style-2" class="a" href="projects.php" data-replace="PROJECTS" href="">PROJECTS</a></li>
        <li><a id="style-2" class="a" href="notifications.php" data-replace="FRIENDS" href="">FRIENDS</a></li>
        <li><a id="style-2" class="a" data-replace="DECONNEXION" href="logout.php">DECONNEXION</a></li>
        <div class="globalsearch">
          <li>
            <form class="formsearch" method="get" action="search.php" onsubmit="return validateField()">
              <input type="search" class="searchbar" placeholder="" name="query" id="query">
              <button name="submit" onclick="validateField();" type="submit" value="Search" id="querybutton">
                <i id="fa" class="fa fa-search"></i>
              </button>
            </form>
          </li>
        </div>
      </ul>
    </section>
  <?php
  }
  ?>
  <main>
    <?php $image = $user_data->avatar;
    if (empty($image)) { ?>
      <div class="divAvatar">
        <a href="profile_edit.php"><img src="<?= $checkImg; ?>" class="avatar" alt="" id="output" width="150px" /></a>
      </div>
    <?php
    } else {
    ?>

      <div class="divAvatar">
        <a href="profile_edit.php"><img class="avatar" src="membres/avatars/<?= $user_data->avatar; ?>" link="" width="150px"></a>
      </div>
    <?php } ?>

    <h1 class="h1Landing"><?= $user_data->prenom; ?></h1>
    <h2 class="h2Landing"><?= $user_data->pseudo; ?></h2>
    <ul class="following">
      <li class="list">
        <a class="text">SONGS</a>
        <p class="numbers">20</p>
      </li>
      <li class="list">
        <a class="text">FRIENDS</a>
        <p class="numbers"><?= $countFriends; ?></p>
      </li>
      <li class="list">
        <a class="text">COLLABS</a>
        <p class="numbers">15</p>
      </li>
    </ul>

    <!----------------------------------------------------------PROJETS, MORCEAUX ET PHOTOS UTILISATEUR-------------------------------------------------->
    <div class="content">
      <div class="publications-header">
        <h3>PROJECTS</h3>
        <div class="arrows">
          <i id="arrow1" class="fa-solid fa-arrow-left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>
          <i id="arrow" class="fa-solid fa-arrow-right">&nbsp;</i>
        </div>
      </div>

      <div class="publications" id="pub1">
        <a href="add_project.php" class="friendLink2">
          <div class="photo"><i class="fa-solid fa-circle-plus fa-2x"></i>
            <h3 class="icon-pub">NEW PROJECT</h3>
          </div>
        </a>
        <a href="manage_project.php" class="friendLink2">
          <div class="photo"><i class="fa-solid fa-file-audio fa-2x"></i>
            <h3 class="icon-pub">MANAGE</h3>
          </div>
        </a>
        <?php
        if ($userProjects) {
          foreach ($userProjects as $item) { ?>
            <a class="friendLink2" href="user_project.php?id=<?= $item->id; ?>">
              <div class="photo2">
                <!-- <i class="fa-solid fa-music fa-2x"></i> -->
                <img class="cover" src="<?= $item->cover; ?>" link="">
                <h3 class="project-name"><?= $item->name; ?></h3>
              </div>
            </a>
          <?php
          }
        } else { ?>
          <div class="photo2"><i class="fa-solid fa-music fa-2x"></i>
            <h3 class="icon-pub">PROJECT_1</h3>
          </div>
          <div class="photo2"><i class="fa-solid fa-music fa-2x"></i>
            <h3 class="icon-pub">PROJECT_2</h3>
          </div>
        <?php
        }
        ?>
      </div>

      <div class="publications-header">
        <h3 onclick="">TRACKS</h3>
        <div class="arrows">
          <i id="arrow1" class="fa-solid fa-arrow-left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>
          <i id="arrow" class="fa-solid fa-arrow-right">&nbsp;</i>
        </div>
      </div>

      <div class="publications" id="pub2">
        <div class="photo"><i class="fa-solid fa-circle-play fa-2x"></i></div>
        <div class="photo"><i class="fa-solid fa-circle-play fa-2x"></i></div>
        <div class="photo2"><i class="fa-solid fa-circle-play fa-2x"></i></div>
        <div class="photo2"><i class="fa-solid fa-circle-play fa-2x"></i></div>
      </div>

      <div class="publications-header">
        <h3>PHOTOS</h3>
        <div class="arrows">
          <i id="arrow1" class="fa-solid fa-arrow-left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>
          <i id="arrow" class="fa-solid fa-arrow-right">&nbsp;</i>
        </div>
      </div>

      <div class="publications">
        <div class="photo">
          <div class="bgImg"></div>
        </div>
        <div class="photo">
          <div class="bgImg2"></div>
        </div>
        <div class="photo2">
          <div class="bgImg3"></div>
        </div>
        <div class="photo2">
          <div class="bgImg4"></div>
        </div>
      </div>

      <div class="publications-header2">
        <h3>MY LAST POSTS</h3>
        <div class="arrows">
          <i id="arrow1" class="fa-solid fa-arrow-left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>
          <i id="arrow" class="fa-solid fa-arrow-right">&nbsp;</i>
        </div>
      </div>

      <?php

        foreach ($posts as $post) {

      ?>

        <div class="landingPosts">

          <div class="postHeader">

            <?php

            $image = $user_data->avatar;

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
                <a href="" class="friendLink2"><img class="avatar6" src="membres/avatars/<?= $user_data->avatar; ?>" link="" width="150px"></a>

                <?php

                if ($user_data->online == 1) { ?>

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

            <h2 class="postPseudo"><?= $user_data->pseudo; ?></h2>

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

          <hr id="line" />

          <div class="postReactions">

            <?php

            $sql = "SELECT COUNT(*) FROM likes WHERE user_id = :user_id AND id_post = :id_post;";
            $sql2 = "SELECT COUNT(*) FROM likes WHERE id_post = :id_post;";

            $id = $user_data->id;
            $post_id = $post->post_id;

            $check_like = $db_connection->prepare($sql);
            $count_like = $db_connection->prepare($sql2);

            $check_like->bindParam(":user_id", $id, PDO::PARAM_INT);
            $check_like->bindParam(":id_post", $post_id, PDO::PARAM_INT);
            $count_like->bindParam(":id_post", $post_id, PDO::PARAM_INT);

            $check_like->execute();
            $count_like->execute();

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

          <b>
            <p class="postDate"><?= $post->postedAt->format('d-m-Y H:i:s'); ?></p>
          </b>


        </div>

      <?php

      }

      ?>

      <div class="contentFriend">

    </div>
      <!---------------------------------------------------------------- PUBLICATIONS  --------------------------------------------------------------->

      <?php


      if ($all_users);
      foreach ($all_users as $row) {

      ?>

        <!-- <div class="userFriend">

              <div class="divAvatar">
              
                <img class="avatar" src="membres/avatars/<?= $row->avatar; ?>" alt="Profile image">

              </div>

              <div class="user-info"><span class="h2Friend"><?= $row->pseudo; ?></span><br>

                <span><a class="friendLink2" href="user_profile.php?id=<?= $row->id; ?>" class="see_profileBtn">See profile</a>

              </div>
            </div> -->

      <?php
      }
      ?>

    </div>
  </main>
  <script src="js/darkmode.js"></script>
  <script src="js/search.js"></script>
  <script src="js/toogle.js"></script>
  </div>
</body>

</html>