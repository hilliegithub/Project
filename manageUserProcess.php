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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/styles.css?1">
    <title>Manage
        <?= $user['username'] ?>
        <?php isset($user['username']) ? $user['username'] : 'User' ?>
    </title>
</head>

<body>
    <main>
        <?php include("navigation.php") ?>
        <?php if ($processingError): ?>
        <div class="username-taken alert alert-danger" role="alert">
            <?= $errorMessage ?>
            <p><a class="alert-link" href="index.php">Back To Home Page</a></p>
        </div>
        <?php else: ?>
        <div class="container mt-2">
            <h2>Edit
                <?= $user['username'] ?>'s Record
            </h2>
            <form action="manageUserProcess.php" method="post">
                <!-- <ul>
                    <li style=" list-style: none;"> -->
                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="username">Username</label>
                        <input name="userid" value="<?= $user['userID'] ?>" hidden>
                        <input type="text" id="username" class="form-control" placeholder="Username" name="username"
                            value="<?= $user['username'] ?>" required>
                    </div>
                </div>

                <!-- </li>
                                                    <listyle=" list-style: none;"> -->
                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control" placeholder="user@email" name="email"
                            value="<?= $user['email'] ?>" required>
                    </div>
                </div>

                <div class="form-row col-md-6 p-0 mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="isadmin">Assign adminstrator role?</label>
                        </div>
                        <select class="custom-select" id="isadmin" name="isadmin">
                            <?php
                                        $options = [
                                            ['value' => 'True', 'label' => 'True'],
                                            ['value' => 'False', 'label' => 'False']
                                        ];

                                        foreach ($options as $option) {
                                            $selected = ($option['value'] === ($user['isAdmin'] === 1 ? 'True' : 'False')) ? 'selected' : '';
                                            echo "<option value=\"{$option['value']}\" {$selected}>{$option['label']}</option>";
                                        }
                                        ?>
                        </select>
                    </div>
                </div>
                <!-- </li>
                        <li> -->
                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="changePassword">Do you wish to change the password?</label>
                        <input type="checkbox" class="form-control" id="changePassword" name="changePassword">
                    </div>
                </div>

                <!-- <li>
                        <li class="passwordlist"> -->
                <div class="form-row passwordlist">
                    <div class="form-group col-12 col-md-6">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password"
                            name="password">
                    </div>
                </div>
                <!-- <li>
                        <li class="passwordlist"> -->
                <div class="form-row passwordlist">
                    <div class="form-group col-12 col-md-6">
                    </div>
                    <label for="confirmpassword">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmpassword" placeholder="Password"
                        name="confirmpassword">
                </div>
                <!-- <li> -->
                <div class="form-row mt-3">
                    <button class="btn btn-secondary mr-2" id="submit" name="submit" type="submit" value="update"
                        onclick="confirm('Are you sure you wish make these changes?')">Update</button>
                    <button class="btn btn-danger" id="submit" name="submit" type="submit" value="remove"
                        onclick="confirm('Are you sure you wish to delete <?= $user['username'] ?>?')">Delete</button>
                </div>
                <!-- </li>
                </ul> -->
            </form>
        </div>
        <?php endif ?>
    </main>
    <script src=" scripts/manageUsers.js?1"></script>
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