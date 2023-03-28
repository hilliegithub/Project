<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once("connect.php");
include("constants.php");
$loginMessage = '';

//Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
    header("Location: login.php");
    exit();
} else {
    if (isset($_COOKIE['loginMessage']))
        $loginMessage = filter_var($_COOKIE['loginMessage'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

$invalidRequest = false;
$errorMessage = '';

try {
    if ($_GET && !empty($_GET['id'])) {
        $postid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if (filter_var($postid, FILTER_VALIDATE_INT)) {
            $query = "SELECT * FROM BikePost WHERE id = :id LIMIT 1";
            $statement = $db->prepare($query);

            // Bind the :id to the id of the post from the GET superglobal
            $statement->bindValue('id', $postid, PDO::PARAM_INT);
            $result = $statement->execute();
            if (!$result) {
                throw new Exception('Error processing request. Try again Later.');
            }

            $post = $statement->fetch();
            if ($statement->rowCount() !== 1) {
                throw new Exception('No post found.');
            }

            // echo "<br>" . print_r($post);
        } else {
            throw new Exception('Invalid Post ID');
        }
    } else {
        throw new Exception('Invalid Url.');
    }
} catch (Exception $e) {
    $invalidRequest = true;
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

    <title>
        <?= $post['make'] ?>
        <?= $post['model'] ?>
    </title>
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
        <div class="container">

            <?php if ($invalidRequest): ?>
            <p>
                <?= $errorMessage ?>
            </p>
            <p><a href="index.php">Back To Home Page</a></p>
            <?php else: ?>
            <form method="post" action="processeditPost.php" enctype="multipart/form-data">
                <fieldset>
                    <legend>Modify <?= $post['make'] ?>
                        <?= $post['model'] ?>
                    </legend>
                    <?php if ($post['image_url']): ?>
                    <img class="rounded img-thumbnail" src="<?= $post['image_url'] ?>" alt="<?= $post['make'] ?>">
                    <?php endif ?>

                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">

                            <label for="make">Bike Make</label>
                            <input id="make" name="make" type="text" value="<?= $post['make'] ?>" class="form-control"
                                required maxlength="<?= BIKEMAKE_MAX_LENGTH ?>" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="model">Bike Model</label>
                            <input id="model" name="model" class="form-control" value="<?= $post['model'] ?>"
                                type="text" required maxlength="<?= BIKEMODEL_MAX_LENGTH ?>" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="engine">Engine</label>
                            <input id="engine" name="engine" type="text" value="<?= $post['engine'] ?>"
                                class="form-control" required maxlength="<?= BIKE_ENGINE_MAX_LENGTH ?>" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="year">Year</label>
                            <input id="year" name="year" class="form-control" value="<?= $post['year'] ?>" type="number"
                                min="1900" max="2099" required />
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="displacement">Displacement (ccm)</label>
                            <input id="displacement" value="<?= $post['displacement_ccm'] ?>" name="displacement"
                                class="form-control" type="text" required />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12 col-md-6"">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <label for="
                            file-input">Choose
                            file:</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input id="image" name="image" class="custom-file-input" type="file"
                                        accept=".png, .jpg, .jpeg" />
                                    <label class="custom-file-label" for="image">Choose an Image</label>
                                </div>
                            </div>

                        </div>
                        <input id="imageOld" name="imageOld" type="text" hidden value="<?= $post['image_url'] ?>">
                    </div>

                    <?php if ($post['image_url']): ?>
                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="checkbox" class="form-control" id="removeimage" name="removeimage">
                                        <!-- <input type="checkbox" aria-label="Checkbox for following text input"> -->
                                    </div>
                                </div>

                                <label for="removeimage" class="form-control">Do you wish to delete the
                                    Image?</label>
                            </div>

                        </div>
                    </div>
                    <?php endif ?>
                    <!-- <li>
                        <label for="image">Bike Image</label>
                        <input id="image" name="image" type="file" accept=".png, .jpg, .jpeg" />

                        <label for="removeimage">Do you want to just delete the Image?</label>
                        <input type="checkbox" id="removeimage" name="removeimage"
                            onclick="confirm('Are you sure you wish to delete this image?')">

                        <input id="imageOld" name="imageOld" type="text" hidden value="<?= $post['image_url'] ?>">
                    </li> -->

                    <input type="hidden" name="id" value="<?= $post['id'] ?>">
                    <!-- <input id="submit" type="submit" name="command" value="update" />
                    <input id="submit" type="submit" name="command" value="delete"
                        onclick="return confirm('Are you sure you wish to delete this post?')" /> -->

                    <?php if (($_SESSION['isAdmin'] === 1) || ($post['userID'] === $_SESSION['user_id'])): ?>
                    <button class="btn btn-secondary" id="submit" name="command" type="submit"
                        value="update">Update</button>
                    <button class="btn btn-danger" id="submit" name="command" type="submit" value="delete"
                        onclick="return confirm('Are you sure you wish to delete this post?')">Delete</button>
                    <?php else: ?>
                    <small><em>Only the Administrator or Post creator may edit this post.</em></small>
                    <?php endif ?>
                </fieldset>
            </form>
            <?php endif ?>
        </div>
    </main>
    <script type="text/javascript" src="scripts/createPost.js"></script>
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