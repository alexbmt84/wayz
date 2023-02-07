<!DOCTYPE html>
<html lang="fr">

  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Bienvenue sur Wayz</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      <link rel="stylesheet" href="css/landing.css">
  </head>

  <body>  <!-----------------------------------------------------------------NAVBAR----------------------------------------------------------------------->
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
          <li><a id="style-2" href="landing.php" data-replace="PROFILE">PROFILE</a></li>
          <li><a id="style-2" href="feed.php" data-replace="FEED" href="">FEED</a></li>
          <li><a id="style-2" href="projects.php" data-replace="PROJECTS" href="">PROJECTS</a></li>
          <li><a id="style-2" href="notifications.php" data-replace="FRIENDS" href="">FRIENDS</a></li>
          <li><a id="style-2" data-replace="DECONNEXION" href="logout.php">DECONNEXION</a></li> 
          <div class="globalsearch">
            <li>
              <form class="formsearch" method="get" action="search.php" onsubmit="return validateField()"> <!-- Ensure there are no enter escape characters.-->
                  <!-- <select name="location" id="mydropdown">
                  <option value="">CATEGORIES</option>
                  <option class="option" value="emails">EMAILS</option>
                  <option class="option" value="names">NAMES</option>
                  <option class="option" value="pseudos">PSEUDOS</option>
                  <option class="option" value="posts">POSTS</option> -->
                  <!--</select>-->
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