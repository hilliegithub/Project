<?php
require('connect.php');
$processingError = false;

try {
    if ($_GET && !empty($_GET['id'])) {
        $postid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        // Minimun valid integer number is 0
        if (filter_var($postid, FILTER_VALIDATE_INT, ["option" => ["min_range" => 1]]) !== false) {
            $query = "SELECT * FROM BikePost WHERE id = :id LIMIT 1";
            $stmt = $db->prepare($query);

            $stmt->bindValue('id', $postid, PDO::PARAM_INT);
            $result = $stmt->execute();
            if (!$result) {
                throw new Exception('Error processing request. Please try again later');
            }

            if ($stmt->rowCount() != 1) {
                throw new Exception('Error processing request. Please try again later');
            }
            $post = $stmt->fetch();
            // print_r($post);
        } else {
            throw new Exception('Invalid Post');
        }
    } else {
        header("Location: index.php");
        exit;
    }
} catch (Exception $e) {
    $processingError = true;
    $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $post['make'] ?>
        <?= $post['model'] ?>
    </title>
</head>

<body>
    <main>
        <?php include("navigation.php") ?>
        <div>
            <h1>
                <?= $post['make'] ?>
                <?= $post['model'] ?>
            </h1>
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
            </div>
        </div>
    </main>
</body>

</html>