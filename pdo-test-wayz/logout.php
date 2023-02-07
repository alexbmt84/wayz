<?php

require_once('core/init.php');

$db_obj = new Database();
$db_connection = $db_obj->dbConnection();
$user_id = $_SESSION["user-id"];
$sql2 = "UPDATE utilisateurs SET online = 0 WHERE id = :user_id";

$stmt2 = $db_connection->prepare($sql2);
$stmt2->execute(array(':user_id' => $user_id));

session_destroy();
header('location: index.php');
exit;
?>