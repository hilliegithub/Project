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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/register.css?1">
    <title>Register Now</title>
</head>

<body>
    <main>
        <?php include("navigation.php") ?>
        <div class="container">
            <h2>User Sign Up</h2>
            <div class="username-available alert alert-primary col-md-6" role="alert">
                Username is available. Please continue to fill out the form.
            </div>
            <div class="username-taken alert alert-danger col-md-6" role="alert">
                Sorry this username is taken. Please choose a different username.
            </div>
            <form id="registerform" action="register.php" method="post" class="pb-2">
                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="username">Username</label>
                        <input type="text" id="username" class="form-control" placeholder="Username" name="username"
                            aria-describedby="usernameHelp" required>
                        <small id="usernameHelp" class="form-text text-muted">Choosen usernames are unique</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control" placeholder="user@email" name="email"
                            required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="password">Password</label>
                        <input type="password" id="password" class="form-control" placeholder="Password" name="password"
                            required>
                        <small id="passwordHelpBlock" class="form-text text-muted">
                            Your password must be 8-20 characters long.
                        </small>
                        <div class="alert alert-danger error" id="password_error" role="alert">Password must
                            be 8
                            - 20 characters</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="confirmpassword">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmpassword" placeholder="Confirm Password"
                            name="confirmpassword" required>
                        <div class="alert alert-danger error" id="confirmpassword_error" role="alert">Passwords
                            must
                            match!</div>
                    </div>
                </div>
                <button id="submit" class="btn btn-primary" name="submit" type="submit">Register</button>
            </form>
            <?php if ($processingError): ?>
            <p>
                <?= $errorMessage ?>
            </p>
            <p><a href="index.php">Back To Home Page</a></p>
            <?php endif ?>
        </div>
    </main>
    <script src="scripts/register.js?1"></script>
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