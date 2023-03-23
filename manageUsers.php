<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once("connect.php");
$processingError = false;
$errorMessage = '';
$loginMessage = '';

//Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
    header("Location: login.php");
    exit();
} else {
    if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === 1) {
        // print_r($_SESSION);

        if (isset($_COOKIE['loginMessage']))
            $loginMessage = filter_var($_COOKIE['loginMessage'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    } else {
        $processingError = true;
        $errorMessage = "This page is only for authorized users only. Please contact your adminstrator for assistance.";
    }
}

try {
    $lquery = "SELECT userID, username, email, isAdmin FROM user";
    $stmt = $db->prepare($lquery);
    $result = $stmt->execute();
    if (!$result) {
        throw new Exception("Error checking username and password");
    }
    $users = $stmt->fetchAll();
    // print_r($users);
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Users</title>
</head>

<body>
    <main>
        <?php if ($loginMessage): ?>
        <div>
            <p>
                <?= $loginMessage ?>
            </p>
        </div>
        <?php endif ?>
        <?php include("navigation.php") ?>
        <?php if ($processingError): ?>
        <p>
            <?= $errorMessage ?>
        </p>
        <p><a href="index.php">Back To Home Page</a></p>
        <?php else: ?>
        <h1>Manage All Users</h1>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Administrator</th>
                    <ht></ht>
                    <ht></ht>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr id="<?= $user['userID'] ?>">
                    <td>
                        <?php echo $user['username']; ?>
                    </td>
                    <td>
                        <?php echo $user['email']; ?>
                    </td>
                    <td>
                        <?php if ($user['isAdmin'] === 1): ?>
                        true
                        <?php else: ?>
                        false
                        <?php endif ?>
                    </td>
                    <td><button id="editbtn<?= $user['userID'] ?>">Edit</button></td>
                    <td><button id="removebtn<?= $user['userID'] ?>">Remove</button></td>
                </tr>
                <?php endforeach ?>
            </tbody>

        </table>
        <?php endif ?>
    </main>
</body>

</html>