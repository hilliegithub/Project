<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("connect.php");
$salt = bin2hex(random_bytes(16));
$password = password_hash('gorgonzola7!' . $salt, PASSWORD_DEFAULT);
$query = "UPDATE user SET email = 'admin@bikepost.com', password = :password, salt = :salt WHERE userID = 1";
$stmt = $db->prepare($query);
$stmt->bindValue(':password', $password);
$stmt->bindValue(':salt', $salt);

// $stmt->execute();

echo "Done!";
?>