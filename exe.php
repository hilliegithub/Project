<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("connect.php");
$salt = bin2hex(random_bytes(16));
$password = password_hash('gorgonzola7!' . $salt, PASSWORD_DEFAULT);
$query = "SELECT part  FROM Comments";
$stmt = $db->prepare($query);
// $stmt->bindValue(':password', $password);
// $stmt->bindValue(':salt', $salt);

$result = $stmt->execute();

echo $result;

print_r($stmt->fetch());
?>