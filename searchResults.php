<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("connect.php");
$processingError = false;
$errorMessage = '';

try {
    if ($_GET) {

        $searchTerm = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $query = "SELECT * FROM BikePost WHERE LOWER(make) LIKE '%" . strtolower($searchTerm) . "%'";
        $stmt = $db->prepare($query);
        // $stmt->bindValue('term', strtolower($searchTerm));
        $result = $stmt->execute();
        if (!$result)
            throw new Exception('Could not process your request');
        $posts = $stmt->fetchAll();
        // echo $stmt->rowCount();
    } else {
        header('Location: index.php');
        exit;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/styles.css?1">
    <title>Search Results for "
        <?= $searchTerm ?>"
    </title>
</head>

<body>
    <main>
        <?php include("navigation.php") ?>
        <div class="container mt-2">
            <h2 class="text-white">Search Results</h2>
            <?php if ($processingError): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                <strong>Fatal Error!</strong>
                <?= $errorMessage ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php endif ?>
            <?php if ($stmt->rowCount() === 0): ?>
            <h3 class="text-white">Could Not Find Any Search Results...</h3>
            <?php else: ?>
            <!-- loop through database and print posts  -->
            <ul>
                <div class="card-deck mx-auto">
                    <?php foreach ($posts as $post): ?>
                    <li>
                        <div class="card m-2" style="width: 18rem;">
                            <?php if ($post['image_url']): ?>
                            <img class="card-img-top" src=<?= $post['image_url'] ?> alt="<?= $post['make'] ?>"
                                width="300px">
                            <?php endif ?>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= $post['make'] ?>
                                </h5>
                                <p class="card-text">
                                    <?= $post['model'] ?>,
                                    <?= $post['year'] ?>
                                </p>
                                <p class="card-text">
                                    <?= $post['engine'] ?>
                                </p>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                <a class="btn btn-primary" href="editBikePost.php?id=<?= $post['id'] ?>">
                                    Edit This Post
                                </a>
                                <?php endif ?>
                                <a class="card-link" href="post.php?id=<?= $post['id'] ?>">
                                    View This Post
                                </a>
                            </div>
                        </div>
                    </li>
                    <?php endforeach ?>
                </div>
            </ul>
            <?php endif ?>
        </div>
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