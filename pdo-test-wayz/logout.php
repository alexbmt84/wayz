<?php
session_start();

require_once('core/init.php');

session_destroy();
header('location: index.php');
exit;
?>