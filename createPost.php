<?php
session_start();
require_once("constants.php");


//Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
    header("Location: login.php");
    exit();
}

// print_r($_SESSION);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a Bike Post</title>
</head>

<body>
    <?php include("navigation.php") ?>
    <main>
        <form action="processPost.php" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Upload all bike details</legend>
                <ul>
                    <li>
                        <label for="make">Bike Make</label>
                        <input id="make" name="make" type="text" required maxlength="<?= BIKEMAKE_MAX_LENGTH ?>" />
                    </li>
                    <li>
                        <label for="model">Bike Model</label>
                        <input id="model" name="model" type="text" required maxlength="<?= BIKEMODEL_MAX_LENGTH ?>" />
                    </li>
                    <li>
                        <label for="engine">Engine</label>
                        <input id="engine" name="engine" type="text" required
                            maxlength="<?= BIKE_ENGINE_MAX_LENGTH ?>" />
                    </li>
                    <li>
                        <label for="year">Year</label>
                        <input id="year" name="year" type="number" min="1900" max="2099" required />
                    </li>
                    <li>
                        <label for="displacement">Displacement (ccm)</label>
                        <input id="displacement" name="displacement" type="text" required />
                    </li>
                    <li>
                        <label for="image">Bike Image</label>
                        <input id="image" name="image" type="file" accept=".png, .jpg, .jpeg" />
                    </li>
                    <li>
                        <input name="userid" hidden value="<?= $_SESSION['user_id'] ?>" /> <input id="reset"
                            name="reset" type="reset" />
                        <input id="submit" name="submit" type="submit" />
                    </li>
                </ul>
            </fieldset>
        </form>
    </main>

</body>

</html>