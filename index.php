<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("connect.php");
$inputError = false;
$errorMessage = '';

try {

    if ($_POST) {
        // print_r($_POST);

        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = $_POST['password'];

            $lquery = "SELECT password FROM user WHERE username = :username";
            $stmt = $db->prepare($lquery);
            $stmt->bindValue('username', $username);
            $result = $stmt->execute();
            if (!$result) {
                throw new Exception("Error checking username and password");
            }
            // throw new Exception('Testing Image');
            $result = $stmt->fetch();
            print_r($result);
            if (!password_verify($password, $result['password'])) {
                throw new Exception('Your password is incorrect');
            }
        }
    }


    $query = "SELECT * FROM Bikepost ORDER BY date_created DESC";
    $statement = $db->prepare($query);
    $statement->execute();
    $posts = $statement->fetchAll();


} catch (Exception $e) {
    // Handle exception
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
    <link rel="stylesheet" type="text/css" href="globalstyle.css">
    <title>Hylton's Bike Club</title>
</head>

<body>
    <main>
        <?php include("navigation.php") ?>
        <!-- Button to open the modal -->
        <button id="loginBtn">Login</button>

        <!-- The Modal -->
        <div id="loginModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <span class="close">&times;</span>
                <form action="index.php" method="post">
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
            </div>
        </div>

        <h1>Bike Posts</h1>
        <div id="all-posts">
            <!-- loop through database and print posts  -->
            <ul>
                <?php foreach ($posts as $post): ?>
                <li>
                    <?php if ($post['image_url']): ?>
                    <img src=<?= $post['image_url'] ?> alt="<?= $post['make'] ?>" width="300px">
                    <?php endif ?>
                    <div>Make:
                        <?= $post['make'] ?>
                    </div>
                    <div>Model:
                        <?= $post['model'] ?>
                    </div>
                    <div>Year:
                        <?= $post['year'] ?>
                    </div>
                    <div>Engine:
                        <?= $post['engine'] ?>
                    </div>
                    <div>
                        <a href="editBikePost.php?id=<?= $post['id'] ?>">
                            Edit This Post
                        </a>
                        <a href="post.php?id=<?= $post['id'] ?>">
                            View This Post
                        </a>
                    </div>
                </li>
                <?php endforeach ?>
            </ul>
        </div>
    </main>
    <script type="text/javascript" src="globalJs.js"></script>
</body>

</html>