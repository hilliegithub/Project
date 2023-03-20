<?php
require("connect.php");

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
        <h1>User Sign Up</h1>
        <div class="username-available">
            Username is available. Please continue to fill out the form.
        </div>
        <div class="username-taken error">
            Sorry this username is taken. Please choose a different username.
        </div>
        <form action="processedRegister.php" method="post">
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
                    <input id="submit" name="submit" type="submit" />
                </li>
            </ul>
        </form>
    </main>
    <script src="register/register.js"></script>
</body>

</html>