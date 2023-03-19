<?php
require("connect.php");

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
                $invalidRequest = true;
                throw new Exception('Error processing request. Try again Later.');
            }

            $post = $statement->fetch();
        } else {
            $invalidRequest = true;
            throw new Exception('Invalid Post ID');
        }
    } else {
        $invalidRequest = true;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $post['make'] ?>
        <?= $post['model'] ?>
    </title>
</head>

<body>
    <main>
        <?php if ($invalidRequest): ?>
        <p>$errorMessage</p>
        <?php else: ?>
        <form method="post" action="processeditPost.php" enctype="multipart/form-data">
            <fieldset>
                <legend>CHange THis Heading</legend>
                <img src="<?= $post['image_url'] ?>" alt="<?= $post['make'] ?>">
                <ul>
                    <li>
                        <label for="make">Bike Make</label>
                        <input id="make" name="make" type="text" value="<?= $post['make'] ?>" required maxlength="30" />
                    </li>
                    <li>
                        <label for="model">Bike Model</label>
                        <input id="model" name="model" type="text" value="<?= $post['model'] ?>" required
                            maxlength="20" />
                    </li>
                    <li>
                        <label for="engine">Engine</label>
                        <input id="engine" name="engine" type="text" value="<?= $post['engine'] ?>" required
                            maxlength="30" />
                    </li>
                    <li>
                        <label for="year">Year</label>
                        <input id="year" name="year" type="number" value="<?= $post['year'] ?>" min="1900" max="2099"
                            required />
                    </li>
                    <li>
                        <label for="displacement">Displacement (ccm)</label>
                        <input id="displacement" name="displacement" value="<?= $post['displacement_ccm'] ?>"
                            type="text" required />
                    </li>
                    <li>
                        <label for="image">Bike Image</label>
                        <input id="image" name="image" type="file" accept=".png, .jpg, .jpeg" />
                    </li>
                    <li>
                        <input type="hidden" name="id" value="<?= $post['id'] ?>">
                        <input id="submit" type="submit" name="command" value="update" />
                        <input id="submit" type="submit" name="command" value="delete"
                            onclick="return confirm('Are you sure you wish to delete this post?')" />
                    </li>
                </ul>
            </fieldset>
        </form>
        <?php endif ?>
    </main>
</body>

</html>