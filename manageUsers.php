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
    // throw new Exception('Testing');
    $result = $stmt->execute();
    if (!$result) {
        throw new Exception("Error checking username and password");
    }
    $users = $stmt->fetchAll();
    // print_r($users);
} catch (Exception $e) {
    $processingError = true;
    $errorMessage = $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Manager Users</title>
</head>

<body>
    <main>
        <?php if ($loginMessage): ?>
        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
            <?= $loginMessage ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif ?>
        <?php include("navigation.php") ?>
        <?php if ($processingError): ?>
        <div class="username-taken alert alert-danger" role="alert">
            <?= $errorMessage ?>
            <p><a class="alert-link" href="index.php">Back To Home Page</a></p>
        </div>
        <?php else: ?>
        <div class="container mt-2">
            <?php if (isset($users)): ?>
            <h1>Manage All Users</h1>
            <?php foreach ($users as $user): ?>
            <div class="p-2 m-4" style="border-bottom: 1px solid black;">

                <div class="input-group p-0 mt-3 mb-3 col-12 col-md-8">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Username:</span>
                    </div>
                    <input class="form-control" type="text" placeholder="<?= $user['username'] ?>" readonly>
                </div>
                <div class="input-group p-0 mt-3 mb-3 col-12 col-md-8">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Email Address:</span>
                    </div>
                    <input class="form-control" type="text" placeholder="<?= $user['email'] ?>" readonly>
                </div>
                <div class="input-group p-0 mt-3 mb-3 col-12 col-md-8">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Adminstrator?:</span>
                    </div>
                    <input class="form-control" type="text"
                        placeholder="<?php if ($user['isAdmin'] === 1): ?>True<?php else: ?>False<?php endif ?>"
                        readonly>
                </div>
                <a class="btn btn-secondary role=" button"
                    href="manageUserProcess.php?id=<?= $user['userID'] ?>">Edit</a>
            </div id="<?= $user['userID'] ?>">
            <?php endforeach ?>
            <?php endif ?>
        </div>
        <?php endif ?>
    </main>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>