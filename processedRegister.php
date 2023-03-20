<?php
require("connect.php");
$processingError = false;

try {
    if ($_POST) {
        if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirmpassword'])) {
            print_r($_POST);

            if ($_POST['password'] !== $_POST['confirmpassword']) {
                throw new Exception('Passwords Do not match!');
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
                // die('Error executing query: ' . $statement->errorInfo()[2]);
                throw new Exception('Insert Failed');
            }
            throw new Exception('Testing');
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
    <title>Registering User</title>
</head>

<body>
    <?php if($processingError): ?>
    <?=$errorMessage?>
    <?php endif ?>
</body>

</html>