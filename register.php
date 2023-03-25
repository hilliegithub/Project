<?php
require("connect.php");

try {
    $processingError = false;
    $errorMessage = '';
    if ($_POST) {
        // print_r($_POST);
        // throw new Exception('Testing Posting');
        if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirmpassword'])) {
            // print_r($_POST);

            if ($_POST['password'] !== $_POST['confirmpassword']) {
                throw new Exception('Passwords Do not match! Please try again');
            }
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST["password"];

            //Generate random salt for password
            $salt = bin2hex(random_bytes(16));

            $password_hashed = password_hash($password . $salt, PASSWORD_DEFAULT);

            $query = "INSERT INTO user (username, email, password, salt, isAdmin) VALUES (:username, :email, :password_hashed, :salt, :isadmin)";

            $all_bind_values = [
                'username' => $username,
                'email' => $email,
                'password_hashed' => $password_hashed,
                'salt' => $salt,
                'isadmin' => 0
            ];

            $statement = $db->prepare($query);
            $result = $statement->execute($all_bind_values);
            if (!$result) {
                throw new Exception('Error while processing registration. Please try again later.');
            }

        }
    }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register/register.css?1">
    <title>Register Now</title>
</head>

<body>
    <main>
        <?php include("navigation.php") ?>
        <h1>User Sign Up</h1>
        <div class="username-available">
            Username is available. Please continue to fill out the form.
        </div>
        <div class="username-taken error">
            Sorry this username is taken. Please choose a different username.
        </div>
        <form action="register.php" method="post">
            <ul>
                <li>
                    <label for="username">Username</label>
                    <input type="text" id="username" placeholder="Username" name="username" required>
                    <!-- <p class="error" id="invalid_username">* Invalid Username.</p> -->
                </li>
                <li>
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="user@email" name="email" required>
                    <!-- <p class="error" id="invalid_username">* Invalid Username.</p> -->
                </li>
                <li>
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="Password" name="password" required>
                </li>
                <li>
                    <label for="confirmpassword">Confirm Password</label>
                    <input type="password" id="confirmpassword" placeholder="Confirm Password" name="confirmpassword"
                        required>
                </li>
                <li>
                    <p class="error" id="password_not_match">* Passwords do not match.</p>
                </li>
                <li>
                    <button id="submit" name="submit" type="submit">Register</button>
                </li>
            </ul>
        </form>
        <?php if ($processingError): ?>
        <p><?= $errorMessage ?></p>
        <p><a href="index.php">Back To Home Page</a></p>
        <?php endif ?>
    </main>
    <script src="scripts/register.js?1"></script>
</body>

</html>