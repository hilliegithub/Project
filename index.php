<?php

require("connect.php");
$inputError = false;
$errorMessage = '';

try {
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
    <!-- stylesheet -->
    <title>Hylton's Bike Club</title>
</head>

<body>
    <?php include("navigation.php") ?>
    <main>
        <h1>Bike Posts</h1>
        <div id="all-posts">
            <!-- loop through database and print posts  -->
            <ul>
                <?php foreach ($posts as $post): ?>
                <li>
                    <img src=<?= $post['image_url'] ?> alt="<?= $post['make'] ?>" width="100%">
                    <?= $post['image_url'] ?>
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
                </li>
                <?php endforeach ?>
            </ul>
        </div>
    </main>
</body>

</html>