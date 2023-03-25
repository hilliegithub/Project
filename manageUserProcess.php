<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once("connect.php");
$processingError = false;
$errorMessage = '';

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

    if ($_POST) {
        // print_r($_POST);

        if ($_POST['submit'] == 'remove') {
            $userid = filter_input(INPUT_POST, 'userid', FILTER_VALIDATE_INT);
            //Build query to delete record
            $query = "DELETE FROM user WHERE userid = :id LIMIT 1";
            $statement = $db->prepare($query);
            $statement->bindValue(':id', $userid, PDO::PARAM_INT);
            $result = $statement->execute();
            if (!$result) {
                throw new Exception('Error while deleting user. Try Again Later.');
            }
            header("Location: manageUsers.php");
            exit;
        }

        if (!empty($_POST['username']) && !empty($_POST['email'])) {

            if (isset($_POST['changePassword'])) {
                if (empty($_POST['password']) || empty($_POST['confirmpassword'])) {
                    throw new Exception('You must enter a password.');
                } else {
                    if ($_POST['password'] !== $_POST['confirmpassword']) {
                        throw new Exception('Your passwords do not match.');
                    }
                    $password = $_POST['password'];
                }
            }

            $userRecord = filter_input(INPUT_POST, 'userid', FILTER_VALIDATE_INT);
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $isadmin = filter_input(INPUT_POST, 'isadmin', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (isset($_POST['changePassword'])) {
                $updatequery = "UPDATE user SET username = :username, email = :email, isAdmin = :isAdmin, password = :password, salt = :salt WHERE userID = :userrecord";

                //Generate random salt for password
                $salt = bin2hex(random_bytes(16));

                $password_hashed = password_hash($password . $salt, PASSWORD_DEFAULT);

                $all_bind_values = [
                    'username' => $username,
                    'email' => $email,
                    'password' => $password_hashed,
                    'salt' => $salt,
                    'userrecord' => $userRecord,
                    'isAdmin' => strcasecmp($isadmin, 'False') === 0 ? 0 : 1
                ];
            } else {
                $updatequery = "UPDATE user SET username = :username, email = :email, isAdmin = :isAdmin WHERE userID = :userrecord";
                $all_bind_values = [
                    'username' => $username,
                    'email' => $email,
                    'userrecord' => $userRecord,
                    'isAdmin' => strcasecmp($isadmin, 'False') === 0 ? 0 : 1
                ];
            }
            // print_r($all_bind_values);

            $statement = $db->prepare($updatequery);
            $result = $statement->execute($all_bind_values);
            if (!$result) {
                throw new Exception('Error while updating user record. Please try again later.');
            }
            header('Location: manageUsers.php');
            exit;
        } else {
            throw new Exception('Username and Email address are required.');
        }
    }



    $userid = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($userid) {
        $lquery = "SELECT userID, username, email, isAdmin FROM user WHERE userID = :userid";
        $stmt = $db->prepare($lquery);
        $stmt->bindValue('userid', $userid);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception("Error retrieving record");
        }
        $user = $stmt->fetch();

    } else {
        throw new Exception('Invalid Request!');
    }
} catch (Exception $e) {
    $processingError = true;
    $errorMessage = $e->getMessage();
}

// print_r($_GET);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/styles.css" />
    <title>Manage
        <?= $user['username'] ?>
    </title>
</head>

<body>
    <main>
        <?php include("navigation.php") ?>
        <?php if ($processingError): ?>
        <p>
            <?= $errorMessage ?>
        </p>
        <p><a href="index.php">Back To Home Page</a></p>
        <?php else: ?>
        <h1>Edit
            <?= $user['username'] ?>'s Record
        </h1>
        <form action="manageUserProcess.php" method="post">
            <ul>
                <li>
                    <input name="userid" value="<?= $user['userID'] ?>" hidden>
                    <label for="username">Username</label>
                    <input type="text" id="username" placeholder="Username" name="username"
                        value="<?= $user['username'] ?>" required>
                </li>
                <li>
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="user@email" name="email" value="<?= $user['email'] ?>"
                        required>
                </li>
                </li>
                <li>
                    <label for="isadmin">Assign adminstrator role?</label>
                    <select id="isadmin" name="isadmin">
                        <option>
                            <?php if ($user['isAdmin'] === 1): ?>
                            True
                            <?php else: ?>
                            False
                            <?php endif ?>
                        </option>
                        <option>
                            <?php if ($user['isAdmin'] === 1): ?>
                            False
                            <?php else: ?>
                            True
                            <?php endif ?>
                        </option>
                    </select>
                </li>
                <li>
                    <label for="changePassword">Do you wish to change the password?</label>
                    <input type="checkbox" id="changePassword" name="changePassword">
                <li>
                <li class="passwordlist">
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="Password" name="password">
                <li>
                <li class="passwordlist">
                    <label for="confirmpassword">Confirm Password</label>
                    <input type="password" id="confirmpassword" placeholder="Password" name="confirmpassword">
                <li>
                    <button id="submit" name="submit" type="submit" value="update"
                        onclick="confirm('Are you sure you wish make these changes?')">Update</button>
                    <button id="submit" name="submit" type="submit" value="remove"
                        onclick="confirm('Are you sure you wish to delete <?= $user['username'] ?>?')">Delete</button>
                </li>
            </ul>
        </form>
        <?php endif ?>
    </main>
    <script src=" scripts/manageUsers.js?1"></script>
</body>

</html>