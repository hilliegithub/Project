<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("connect.php");
$inputError = false;
$errorMessage = '';
$loginMessage = '';
$sorting = ['date_created', 'make', 'year'];
$sortOption = '';

if (isset($_SESSION['user_id']) && isset($_COOKIE['loginMessage'])) {
    $loginMessage = filter_var($_COOKIE['loginMessage'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}


try {
    if (!isset($_COOKIE['sort']) || !isset($_SESSION['user_id'])) {
        setcookie('sort', $sorting[0], time() + 3600);
        $sortOption = $sorting[0];
    } else {
        $sortOption = filter_var($_COOKIE['sort'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!in_array($sortOption, $sorting)) {
            $sortOption = $sorting[0];
            setcookie('sort', $sorting[0], time() + 3600);
        }
    }
    $posts = getPostList($sortOption, $db);
} catch (Exception $e) {
    // Handle exception
    $inputError = true;
    $errorMessage = $e->getMessage();
}

function getPostList($option, $obj)
{
    $query = "SELECT * FROM Bikepost ORDER BY " . $option . " DESC";
    $statement = $obj->prepare($query);
    $result = $statement->execute();
    if (!$result)
        throw new Exception("Error while retrieving data. Please try again later.");
    return $statement->fetchAll();

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
    <title>All Motorcycle Posts</title>
</head>

<body>
    <main>
        <?php if ($loginMessage): ?>
        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
            <?= $loginMessage ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif ?>
        <?php include("navigation.php") ?>
        <div class="container mt-2">
            <h2>Bike Posts</h2>
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="d-flex justify-content-center">
                <p class="sort mr-2">Sort By:</p>
                <form class="d-inline-flex">
                    <div class="mr-3">
                        <input type="radio" name="sort" value="date_created"
                            <?= $sortOption === 'date_created' ? 'checked' : '' ?> />
                        <label for="date_created">Post Date</label>
                    </div>
                    <div class="mr-3">
                        <input type="radio" name="sort" value="make" <?= $sortOption === 'make' ? 'checked' : '' ?> />
                        <label for="make">Manufacture</label>
                    </div>
                    <div class="mr-3">
                        <input type="radio" name="sort" value="year" <?= $sortOption === 'year' ? 'checked' : '' ?> />
                        <label for="year">Model Year</label>
                    </div>
                </form>
            </div>
            <div class="dropdown-divider"></div>
            <?php endif ?>
            <!-- loop through database and print posts  -->
            <ul>
                <div class="card-deck mx-auto">
                    <?php foreach ($posts as $post): ?>
                    <li style=" list-style: none;">
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
                                <p class="card-text">
                                    <small><em>Posted: <?= date('F j, Y', strtotime($post['date_created'])) ?>
                                        </em>
                                    </small>
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
        </div>
    </main>
    <script type="text/javascript" src="scripts/allPosts.js"></script>
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