<?php
require_once "core/init.php";
require_once "header.php";

if (isset($_SESSION['user']) && isset($_SESSION['user-id'])) {
    if (isset($_GET['id'])) {
        $user_data = $user_obj->findByiD($_GET['id']); // ou $_GET 'id'
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

    $user = $user_obj->findByiD($_SESSION['user-id']);
    if ($user ===  false) {
        header('Location: logout.php');
        exit;
    }

    /**********************************************************************************************************************************************/

    if (isset($_POST["destinataire"]) && $_POST["destinataire"] != "" && is_numeric($_POST["destinataire"])) {
        // Récupération des données du destinataire
        $destinataire = User::findById($_POST["destinataire"]);

        // Mise en session de l'id du destinataire pour éviter de le faire transiter par
        // le front, en input hidden du formulaire, et donc le rendre inaccessible au front
        $_SESSION["destinataire"] = $_POST["destinataire"];

        // Si un objet et un message ont été postés...
        if (isset($_POST["usermsg"]) && $_POST["usermsg"] != "") {


            // Eventuellement pour les pièces jointes ...
            $pieceJointe = "";
            // Traitement de l'upload des pièces jointes si nécessaire ...
            if ($_FILES["piece-jointe"]["name"] != "") {
                $uploadDir = "uploads/";

                // Création d'un dossier pour les téléversements si inexistant...
                if (!file_exists($uploadDir))
                    mkdir($uploadDir);

                // chemin vers le fichier téléversé temporaire
                $tmpFile = $_FILES["piece-jointe"]["tmp_name"];
                $trueName = $_FILES["piece-jointe"]["name"];
                $pieceJointe = $uploadDir . basename($trueName);

                // Les types de fichiers autorisés...
                $autorizedTypes = ["image/png", "image/jpg", "image/jpeg", "application/pdf"];
                $typeMime = strtolower(mime_content_type($tmpFile));

                // Vérifier le type de fichier envoyé avant de le déplacer vers son emplacement
                if (in_array($typeMime, $autorizedTypes)) {
                    if (is_uploaded_file($tmpFile)) {
                        if ($_FILES["piece-jointe"]["size"] < 2000000) {
                            move_uploaded_file($tmpFile, $pieceJointe);
                        } else {
                            echo "Fichier trop gros !";
                        }
                    }
                } else {
                    echo "Type de fichier non autorisé !";
                }
            }

            // Génération et stockage du message
            $message = new Message();
            $message->message = htmlspecialchars($_POST["usermsg"], ENT_QUOTES, 'UTF-8');
            $message->attachment = $pieceJointe;
            $message->_from = $user->id;
            $message->_to = $destinataire->id;

            $message->send();

            // Messages d'erreurs ou succés éventuels...
            // non traité ici...
            $alert = null;
        }
    } else if (isset($_SESSION["destinataire"])) {
        $destinataire = User::findById($_SESSION["destinataire"]);
    }

    // Quelle boîte est active (boîte de réception ou messages envoyés ?)
    if (isset($_GET["boite"])) {
        $boite = $_GET["boite"];
    } else {
        // Boîte par défaut:
        $boite = "boite-reception";
    }

    $messagesS = Message::findByFromAndTo($user->id, $user_data->id);
    $messagesR = Message::findByFromAndTo($user_data->id, $user->id);

    $tousLesMessages = Message::findMessageByFromAndTo($user->id, $user_data->id);

    // switch ($boite) {
    //     case "boite-reception":
    //         $messagesR = Message::findByFromAndTo($user_data-> id, $user-> id);
    //         break;
    //     case "messages-envoyes":
    //         $messageS = Message::findByFromAndTo($user_data-> id, $destinataire-> id);
    //         break;
    // }

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

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Messages</title>
    <link type="text/css" rel="stylesheet" href="css/msg.css" />
</head>

<div id="wrapper">
    <div id="chatbox">
        <div class="userBox">
            <a href="user_profile.php?id=<?= $user_data->id; ?>"><i id="arrowIcon" class="fa-solid fa-chevron-left"></i></a>
            <?php $image = $user_data->avatar;
            if (empty($image)) { ?>
                <div class="divAvatar5">
                    <a href="profile_edit.php"><img src="<?= $checkImg; ?>" class="avatar5" alt="" id="output" width="150px" /></a>
                </div>
            <?php
            } else {
            ?>
                <div class="divAvatar5">
                    <a href="profile_edit.php"><img class="avatar5" src="membres/avatars/<?= $user_data->avatar; ?>" link="" width="150px"></a>
                </div>
            <?php } ?>
            <h2 class="h2UserBox" href="user_profile.php?id=<?= $user_data->id; ?>"><?= $user_data->pseudo; ?></h2>
            <div class="userBox2">
                <a href=""><i id="plusIcon" class="fa-solid fa-plus"></i></a>
                <a href=""><i id="dotsIcon" class="fa-solid fa-ellipsis"></i></a>
            </div>
        </div>
        <hr>
        <div class="showMsg">
            <div class="showMsgL <?= $boite == "boite-reception" ? "active" : ""; ?>" aria-current="page" href="?boite=boite-reception">

                <table>
                    <?php foreach ($tousLesMessages as $message) : ?>
                        <tr>
                            <?php if ($message->_from == $user_data->id) : ?>
                                <td class="mainMsg2">
                                    <div class="mainMsg">
                                        <?php $image = $user_data->avatar;
                                        if (empty($image)) { ?>
                                            <div class="divAvatar3">
                                                <a href="profile_edit.php"><img src="<?= $checkImg; ?>" class="avatar3" alt="" id="output" width="150px" /></a>
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <div class="divAvatar3">
                                                <a href="profile_edit.php"><img class="avatar3" src="membres/avatars/<?= $user_data->avatar; ?>" link="" width="150px"></a>
                                            </div>
                                        <?php } ?>
                                        <div class="msgGrey">
                                            <div class="message">
                                                <p class="message"><?= $message->message; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="date">
                                        <p class="date">
                                            <?= $message->dateEnvoi->format("d/m/Y à H:i"); ?>
                                        </p>
                                    </div>
                                </td>

                                <td>&nbsp;</td>
                            <?php else : ?>
                                <td>&nbsp;</td>
                                <td class="mainmsg">
                                    <div class="mainMsg3">
                                        <?php $image = $user->avatar;
                                        if (empty($image)) { ?>
                                            <div class="divAvatar4">
                                                <a href="profile_edit.php"><img src="<?= $checkImg; ?>" class="avatar4" alt="" id="output" width="150px" /></a>
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <div class="divAvatar4">
                                                <a href="profile_edit.php"><img class="avatar4" src="membres/avatars/<?= $user->avatar; ?>" link="" width="150px"></a>
                                            </div>
                                        <?php } ?>
                                        <div class="msgBlue">
                                            <div class="message2">
                                                <p class="message2"><?= $message->message; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="date">
                                        <p class="date">
                                            <?= $message->dateEnvoi->format("d/m/Y à H:i"); ?>
                                        </p>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="destinataire" value="<?= $user_data->id; ?>">
        <input name="usermsg" type="text" id="usermsg" size="63" />
        <!-- <input class="piece-jointe" id="send" type="file" name="piece-jointe" id="piece-jointe"> -->
        <button class="multifile" name="piece-jointe" onclick="importData()"><i id="multIcon" class="fa-solid fa-photo-film"></i></button>
        <button name="submitmsg" type="submit" id="submitmsg" value="Send"><i id="sendbtn" class="fa-solid fa-paper-plane"></i></button>
    </form>
</div>
<script src="js/file.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript">
    // jQuery Document
    $(document).ready(function() {

    });
</script>
</body>

</html>