<?php
require 'core/init.php';
if (isset($_SESSION['user']) && isset($_SESSION['user-id'])) {
  if (isset($_GET['id'])) {

    $user_data = $user_obj->findByiD($_GET['id']); // ou $_GET 'id'
    $userProjects = Project::findUserProject($user_data->id);
    $countFriends = Friend::countFriends($user_data->id);


    if ($user_data ===  false) {
      header('Location: landing.php');
      exit;
    } else {
      if ($user_data->id == $_SESSION['user-id']) {
        header('Location: landing.php');
        exit;
      }
    }
  }
} else {
  header('Location: logout.php');
  exit;
}

$test_orig_image = "https://www.nicepng.com/png/detail/73-730154_open-default-profile-picture-png.png";
$my_deafult_image = "https://www.nicepng.com/png/detail/73-730154_open-default-profile-picture-png.png";
$checkImg = User::check_image_exists($test_orig_image, $my_deafult_image = 'default.png');


// CHECK FRIENDS
$is_already_friends = $frnd_obj->is_already_friends($_SESSION['user-id'], $user_data->id);
//  IF I AM THE REQUEST SENDER
$check_req_sender = $frnd_obj->am_i_the_req_sender($_SESSION['user-id'], $user_data->id);
// IF I AM THE REQUEST RECEIVER
$check_req_receiver = $frnd_obj->am_i_the_req_receiver($_SESSION['user-id'], $user_data->id);
// TOTAL REQUESTS
$get_req_num = $frnd_obj->request_notification($_SESSION['user-id'], false);
// TOTAL FRIENDS
$get_frnd_num = $frnd_obj->get_all_friends($_SESSION['user-id'], false);

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

<body>
  <!-----------------------------------------------------------------NAVBAR----------------------------------------------------------------------->
  <?php
  if (isset($_SESSION['user'])) {
  ?>

    <section class="top-nav">
      <img src="img/g10.svg" class="lgWayz" alt="">
      <div>
        <!-- <h2 class="wayz">Wayz</h2> -->
      </div>
      <input id="menu-toggle" type="checkbox" />
      <label class='menu-button-container' for="menu-toggle">
        <div class='menu-button'></div>
      </label>
      <ul class="menu">
        <li><a id="style-2" href="landing.php" data-replace="PROFILE">PROFILE</a></li>
        <li><a id="style-2" href="profile_edit.php" data-replace="FEED" href="">FEED</a></li>
        <li><a id="style-2" href="projects.php" data-replace="PROJECTS" href="">PROJECTS</a></li>
        <li><a id="style-2" href="notifications.php" data-replace="FRIENDS" href="">FRIENDS</a></li>
        <li><a id="style-2" href="./?page=logout" data-replace="DECONNEXION" href='logout.php'>DECONNEXION</a></li>
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
        <a href=""><img src="<?= $checkImg; ?>" class="avatar" alt="" id="output" width="150px" /></a>
      </div>
    <?php
    } else {
    ?>

      <div class="divAvatar">
        <a href=""><img class="avatar" src="membres/avatars/<?= $user_data->avatar; ?>" link="" width="150px"></a>
      </div>
    <?php } ?>


    <h1 class="h1Landing"><?= $user_data->prenom; ?></h1>
    <h2 class="h2Landing"><?= $user_data->pseudo; ?></h2>

    <div class="actions">
      <?php
      if ($is_already_friends) {
        echo '<a class="friendLink2" href="functions.php?action=unfriend_req&id=' . $user_data->id . '" class="req_actionBtn unfriend">Unfriend</a>';
      } elseif ($check_req_sender) {
        echo '<a class="friendLink2" href="functions.php?action=cancel_req&id=' . $user_data->id . '" class="req_actionBtn cancleRequest">Cancel Request</a>';
      } elseif ($check_req_receiver) {
        echo '<a class="friendLink2" href="functions.php?action=ignore_req&id=' . $user_data->id . '" class="req_actionBtn ignoreRequest">Ignore</a>&nbsp;
            <a href="functions.php?action=accept_req&id=' . $user_data->id . '" class="req_actionBtn acceptRequest">Accept</a>';
      } else {
        echo '<a class="friendLink2" href="functions.php?action=send_req&id=' . $user_data->id . '" class="req_actionBtn sendRequest">Send Request</a>';
      }
      ?>

    </div>

    <?php
    if ($is_already_friends) {
      echo '<a href="messages.php?&id=' . $user_data->id . '" class="req_actionBtn acceptRequest">Message</a>';
    }
    ?>



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
          <i id="arrow" class="fa-solid fa-arrow-left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>
          <i id="arrow" class="fa-solid fa-arrow-right">&nbsp;</i>
        </div>
      </div>

      <div class="publications" id="pub1">
        <div class="photo"><i class="fa-solid fa-circle-plus fa-2x"></i>
          <h3 class="icon-pub">NEW PROJECT</h3>
        </div>
        <div class="photo"><i class="fa-solid fa-file-audio fa-2x"></i>
          <h3 class="icon-pub">MANAGE</h3>
        </div>
        <?php
        if ($is_already_friends) {
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
            <div class="photo2"><i class="fa-solid fa-music fa-2x"></i><h3 class="icon-pub">PROJECT_1</h3></div>
            <div class="photo2"><i class="fa-solid fa-music fa-2x"></i><h3 class="icon-pub">PROJECT_2</h3></div>
          <?php
          
          }
          
          } else { ?>
            <div class="photo2"><i class="fa-solid fa-music fa-2x"></i><h3 class="icon-pub">PROJECT_1</h3></div>
            <div class="photo2"><i class="fa-solid fa-music fa-2x"></i><h3 class="icon-pub">PROJECT_2</h3></div>
          <?php
          }
        ?>
      </div>

      <div class="publications-header">
        <h3>TRACKS</h3>
        <div class="arrows">
          <i id="arrow" class="fa-solid fa-arrow-left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>
          <i id="arrow" class="fa-solid fa-arrow-right">&nbsp;</i>
        </div>
      </div>

      <div class="publications" id="pub2">
        <div class="photo"><i class="fa-solid fa-circle-play fa-3x"></i></div>
        <div class="photo"><i class="fa-solid fa-circle-play fa-3x"></i></div>
        <div class="photo2"><i class="fa-solid fa-circle-play fa-3x"></i></div>
        <div class="photo2"><i class="fa-solid fa-circle-play fa-3x"></i></div>
      </div>

      <div class="publications-header">
        <h3>PHOTOS</h3>
        <div class="arrows">
          <i id="arrow" class="fa-solid fa-arrow-left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>
          <i id="arrow" class="fa-solid fa-arrow-right">&nbsp;</i>
        </div>
      </div>

      <div class="publications" id="#pub3">
        <div class="photo"><img class="imgFriend" src="img/g8.svg" alt=""></div>
        <div class="photo"><img class="imgFriend" src="img/g8.svg" alt=""></div>
        <div class="photo2"><img class="imgFriend" src="img/g8.svg" alt=""></div>
        <div class="photo2"><img class="imgFriend" src="img/g8.svg" alt=""></div>
      </div>
  </main>
  <script src="js/search.js"></script>
  <script src="js/toogle.js"></script>
  </div>
</body>

</html>