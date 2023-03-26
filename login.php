<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("connect.php");
$inputError = false;
$errorMessage = '';

try {

    if ($_POST) {
        // print_r($_POST);

        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = $_POST['password'];

            $lquery = "SELECT * FROM user WHERE username = :username";
            $stmt = $db->prepare($lquery);
            $stmt->bindValue('username', $username);
            $result = $stmt->execute();
            if (!$result) {
                throw new Exception("Error checking username and password");
            }
            // throw new Exception('Testing Image');
            $result = $stmt->fetch();
            // print_r($result);
            if (password_verify($password . $result['salt'], $result['password'])) {
                // echo "<br> match";
                $_SESSION['user_id'] = $result['userID'];
                $_SESSION['user'] = $result['username'];
                $_SESSION['isAdmin'] = $result['isAdmin'];
                setcookie("loginMessage", "Welcome back " . $result['username'] . "! You have successfully logged in.", time() + 5);
            } else {
                unset($_SESSION['user_id']);
                unset($_SESSION['user']);
                unset($_SESSION['isAdmin']);
                throw new Exception('Username or Password is incorrect');
            }
        }
    }
    // if (!isset($_SESSION['user_id'])) {
    //     // Redirecting to the login page
    //     header('Location: login.php');
    //     exit;
    // }

    if (isset($_SESSION['user_id']) && isset($_SESSION['user'])) {
        if (isset($_SESSION['redirect_url'])) {
            header('Location: ' . $_SESSION['redirect_url']);
            unset($_SESSION['redirect_url']);
            exit;
        }

        // If no stored URL, redirect the user to a default page
        header('Location: index.php');
        exit;
    }

} catch (Exception $e) {
    $inputError = true;
    $errorMessage = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,  shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Login</title>
</head>

<body>
    <main class="container mt-2">
        <h2>Log into account</h2>
        <?php if ($inputError): ?>
        <div class="username-taken alert alert-danger col-md-6" role="alert">
            <?= $errorMessage ?>
        </div>
        <?php endif ?>
        <form action="login.php" method="post">
            <div class="form-row">
                <div class="form-group col-12 col-md-6">
                    <label for="username">Username</label>
                    <input type="text" id="username" class="form-control" placeholder="Username" name="username"
                        required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-12 col-md-6">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="Password" name="password"
                        required>
                </div>
            </div>
            <button id="submit" class="btn btn-primary" name="submit" type="submit">Login</button>
        </form>
        <p class="mt-2">
            <a class="mt-2" href="index.php">Go Back To The Home Page</a>
        </p>
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