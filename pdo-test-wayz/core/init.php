<?php
session_start();
session_regenerate_id(true);

require 'core/database.class.php';
require 'core/user.php';
require 'core/friend.class.php';
require 'core/message.class.php';
require 'core/project.class.php';

// DATABASE CONNECTIONS
$db_obj = new Database();
$db_connection = $db_obj->dbConnection();

// USER OBJECT
$user_obj = new User($db_connection);
// FRIEND OBJECT
$frnd_obj = new Friend($db_connection);
// MSG OBJ
$msg_obj = new Message($db_connection);
// Project OBJ
$project_obj = new Project($db_connection);
?>