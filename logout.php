<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
unset($_SESSION['user_id']);
unset($_SESSION['user']);
unset($_SESSION['isAdmin']);
unset($_SESSION['redirect_url']);
session_destroy();
// print_r($_SESSION);

header('Location: index.php');
exit();
?>