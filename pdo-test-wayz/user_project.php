<?php

require 'core/init.php'; // ajout connexion bdd 
require_once 'header.php';

$connexion = new PDO("mysql:host=localhost;port=3306;dbname=testwayz;charset=utf8","root","");


if(isset($_SESSION['user']) && isset($_SESSION['user-id'])){

    $user_data = $user_obj->findByiD($_SESSION['user-id']);
    $userProjects = Project::findUserProject($user_data->id);
    $project = Project::findByiD($_GET['id']);
    // $userProjects = $project_obj->findByiD($userProjects->id);

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
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Projects</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="css/landing.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" defer></script><script  src="js/reporting.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.1.0/chartjs-plugin-datalabels.min.js" integrity="sha512-Tfw6etYMUhL4RTki37niav99C6OHwMDB2iBT5S5piyHO+ltK2YX8Hjy9TXxhE1Gm/TmAV0uaykSpnHKFIAif/A==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js' defer></script><script  src="./script.js" defer></script>
        <script src="js/photo.js" defer></script>
        
    </head>
    <body>

    <?php 

        if (isset($_POST['genre']) and !empty($_POST['genre'])) {
            $genre = $project-> genre;
            $id = $_SESSION['user-id'];
            $newGenre = htmlspecialchars($_POST['genre']);

            if ($genre <= 200) {
                $reqGenre = $connexion->prepare("SELECT * FROM projects WHERE genre = ? AND user != ?");
                // $reqpseudo->execute(array($pseudo));
                $reqGenre->execute(array($genre, $id));
                $genreExist = $reqGenre->rowCount();

                $insertGenre = $connexion->prepare('UPDATE projects SET genre = ? WHERE user = ?');
                    $insertGenre->execute(array($newGenre, $id));
                    // header('Location: landing.php?id=' . $id);
            } else {
                $msg = "Désolé, impossible de dépasser 200 caractères.";
            }
        }

    ?>
        <h1 class="subtitle"><?= $project->name; ?></h1>
        <div class="projectCol">
            <form class="projectInfos" action="" method="post" enctype="multipart/form-data">
                <div class="projectDiv2">
                    <label for="file" class="-label">
                        <span class="glyphicon glyphicon-camera"></span>
                        <i id="cameraIcon" class="fa-solid fa-camera-retro fa-3x"></i>
                    </label>
                    <input id="file" type="file" name="cover" onchange="loadFile(event);" />
                    <img class="cover2" src="<?= $project->cover; ?>" id="output" />
                </div>
                <div class="projectDi">
                    <div class="divInput">
                        <label class="projectLabel" for="name">Project Title :</label>
                        <input class="projectInput" type="text" name="name" placeholder="<?= $project->name; ?>">
                    </div>
                    <div class="divInput">
                        <label class="projectLabel" for="genre">Genre :</label>
                        <input class="projectInput" type="text" name="genre" placeholder="<?= $project->genre; ?>">
                    </div>
                    <div class="divInput">
                        <label class="projectLabel" for="artist">Artist :</label>
                        <input class="projectInput2" type="text" name="artist" placeholder="">
                    </div>
                    <div class="divInput2">
                        <label class="projectLabel" for="name">Update :</label>
                        <div class="projectIcons">
                            <button name="submitmsg" type="submit" id="submitmsg" value="Send">Save<?="&nbsp&nbsp&nbsp&nbsp";?><i id="sendbtn" class="fa-solid fa-circle-check"></i></button>
                            <button name="submitmsg" type="" id="submitmsg" value="Send">Collabs<?="&nbsp&nbsp&nbsp&nbsp";?><i id="sendbtn" class="fa-solid fa-user-gear"></i></button>
                            <button name="submitmsg" type="" id="submitmsg" value="Send">Delete<?="&nbsp&nbsp&nbsp&nbsp";?><i id="sendbtn" class="fa-solid fa-trash-can"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="projectCharts">
            <section class="chart2" id="chartContainer">
                <figure class="chart__figure2" id="chart__figure2">
                          <canvas class="chart__canvas" id="chartCanvas2" width="450px" height="450px" aria-label="doughnutChart" role="img"></canvas>
                          <!-- <figcaption class="chart__caption">
                              Excellent ! <span>Vous êtes sur la bonne voie !</span>
                          </figcaption> -->
                </figure>
            </section>
            <section class="chart2" id="chartContainer">
            <figure class="chart__figure2" id="chart__figure">
                      <canvas class="chart__canvas canvas1" id="chartCanvas" width="450px" height="450px" aria-label="doughnutChart" role="img"></canvas>
                      <!-- <figcaption class="chart__caption">
                          Excellent ! <span>Vous êtes sur la bonne voie !</span>
                      </figcaption> -->
            </figure>
          </section>
          <section class="chart3" id="chartContainer">
            <figure class="chart__figure2" id="chart__figure4">
                      <canvas class="chart__canvas" id="chartCanvas4" width="450px" height="450px" aria-label="doughnutChart" role="img"></canvas>
                      <!-- <figcaption class="chart__caption">
                          Excellent ! <span>Vous êtes sur la bonne voie !</span>
                      </figcaption> -->
            </figure>
          </section>
        </div>
    </body>

</html>