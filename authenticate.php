<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// require_once('connect.php');

// if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
//     header('HTTP/1.1 401 Unauthorized');
//     header('WWW-Authenticate: Basic realm="Enter your credentials"');
//     exit("Access Denied: Username and password required.");
// }

// $username = filter_input(INPUT_SERVER, 'PHP_AUTH_USER', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
// $password = $_SERVER['PHP_AUTH_PW'];

// // Prepare the query to check if the user exists in the database
// $query = "SELECT * FROM user WHERE username = :username";
// $stmt = $db->prepare($query);
// $stmt->bindParam(':username', $username);
// $stmt->execute();
// $user = $stmt->fetch(PDO::FETCH_ASSOC);

// if (!$user) {
//     header('HTTP/1.1 401 Unauthorized');
//     header('WWW-Authenticate: Basic realm="Enter your credentials"');
//     exit("Access Denied: Invalid username or password.");
// }

// if (!password_verify($password, $user['password'])) {
//     header('HTTP/1.1 401 Unauthorized');
//     header('WWW-Authenticate: Basic realm="Enter your credentials"');
//     exit("Access Denied: Invalid username or password.");
// }

// // Authentication succeeded
// echo "Welcome, " . $_SERVER['PHP_AUTH_USER'] . "!";

?>