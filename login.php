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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <main>
        <?php if ($inputError): ?>
        <?= $errorMessage ?>
        <?php endif ?>
        <form action="login.php" method="post">
            <ul>
                <li>
                    <label for="username">Username</label>
                    <input type="text" id="username" placeholder="Username" name="username" required>
                    <!-- <p class="error" id="invalid_username">* Invalid Username.</p> -->
                </li>
                <li>
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="Password" name="password" required>
                </li>
                <li>
                    <button id="submit" name="submit" type="submit">Login</button>
                </li>
            </ul>
        </form>
    </main>
</body>

</html>