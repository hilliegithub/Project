<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once("constants.php");
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
// print_r($_SESSION);
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
    <title>Create a Bike Post</title>
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
        <?php include("navigation.php") ?>
        <div class="container">

            <form action="processPost.php" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>Enter Motorcycle details</legend>

                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">

                            <label for="make" class="text-white">Bike Make</label>
                            <input id="make" name="make" type="text" class="form-control" required
                                maxlength="<?= BIKEMAKE_MAX_LENGTH ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="model" class="text-white">Bike Model</label>
                            <input id="model" name="model" class="form-control" type="text" required
                                maxlength="<?= BIKEMODEL_MAX_LENGTH ?>">
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="engine" class="text-white">Engine</label>
                            <input id="engine" name="engine" type="text" class="form-control" required
                                maxlength="<?= BIKE_ENGINE_MAX_LENGTH ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="year" class="text-white">Year</label>
                            <input id="year" name="year" class="form-control" type="number" min="1900" max="2099"
                                required>
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="displacement" class="text-white">Displacement (ccm)</label>
                            <input id="displacement" name="displacement" class="form-control" type="text" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label class="text-white">Choose file:</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input id="image" name="image" class="custom-file-input" type="file"
                                        accept=".png, .jpg, .jpeg">
                                    <label class="custom-file-label" for="image">Choose an Image</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input name="userid" hidden value="<?= $_SESSION['user_id'] ?>">
                    <!-- <input id="reset" name="reset" type="reset" />
                                                                                                                                                                                            <input id="submit" name="submit" type="submit" /> -->
                    <button class="btn btn-secondary" name="reset" type="reset">Reset</button>
                    <button class="btn btn-primary" name="submit" type="submit">Create Post</button>
                </fieldset>
            </form>
        </div>
    </main>
    <script src="scripts/createPost.js"></script>
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