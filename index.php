<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("connect.php");
$inputError = false;
$errorMessage = '';
$loginMessage = '';
// $sorting = ['date_created', 'make', 'year'];
// $sortOption = '';

if (isset($_SESSION['user_id']) && isset($_COOKIE['loginMessage'])) {
    $loginMessage = filter_var($_COOKIE['loginMessage'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}


try {
    // throw new Exception('Holy guacamole!');
    $query = "SELECT * FROM Bikepost ORDER BY date_created DESC LIMIT 3";
    $statement = $db->prepare($query);
    $result = $statement->execute();
    if (!$result)
        throw new Exception("Error while retrieving data. Please try again later.");

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/styles.css?1">
    <title>Hylton's Bike Club</title>
</head>

<body class="hmm">
    <main>
        <?php if ($loginMessage): ?>
        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
            <?= $loginMessage ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif ?>
        <?php if ($inputError): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <strong>Fatal Error!</strong>
            <?= $errorMessage ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif ?>
        <?php include("navigation.php") ?>
        <div class="container mt-3">
            <h4 class="text-center text-white">Welcome to Hylton's Bike Club</h4>
            <h1 class="text-uppercase text-white text-center font-weight-light mt-3 mb-3 mx-auto"
                style="max-inline-size: 500px;">
                JOIN OUR
                COMMUNITY
                AND
                REVIEW
                WINNIPEG'S crème de la crème
                Bikes.</h1>
            <?php if (!isset($_SESSION['user_id'])): ?>
            <p><a class="mx-auto btn text-white btn-secondary col-md-4 btn-lg btn-block" href="register.php">Join</a>
            </p>
            <?php endif ?>
            <p class="text-white mt-5" style="text-align: right;">Hylton’s Bike Club is Winnipeg based not-for-profit
                organization
                that posts official
                motorcycle reviews.
                Recent
                studies have shown that there is an increase in motorcycle owners in Winnipeg and the motorcycle
                community is
                dispersed to various social media platforms. Gathering consensus on motorcycle in the city is hard to
                do. </p>
            <div class="d-flex flex-column flex-lg-row">
                <?php foreach ($posts as $post): ?>
                <div class="card m-2 mx-auto" style="width: 18rem;">
                    <?php if ($post['image_url']): ?>
                    <img class="card-img-top" src=<?= $post['image_url'] ?> alt="<?= $post['make'] ?>" width="300px">
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
                        <p><a class="btn btn-primary" href="editBikePost.php?id=<?= $post['id'] ?>">
                                Edit This Post
                            </a></p>
                        <?php endif ?>
                        <a class="card-link" href="post.php?id=<?= $post['id'] ?>">
                            View This Post
                        </a>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </main>
    <!-- <script type="text/javascript" src="scripts/index.js"></script> -->
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