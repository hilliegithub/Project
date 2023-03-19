<?php

require("connect.php");

print_r($_POST);


try {

    if ($_POST) {
        $command = filter_input(INPUT_POST, 'command', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $command = strtolower($command);
        $processingError = false;

        switch ($command) {
            case "update":
                echo "update";
                break;
            case "delete":
                if (!empty($_POST['id'])) {

                    $postid = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                    $selectquery = "SELECT image_url FROM BikePost WHERE id = :id";
                    $statement = $db->prepare($selectquery);
                    $statement->bindValue(':id', $postid, PDO::PARAM_INT);
                    $result = $statement->execute();
                    if (!$result) {
                        $processingError = true;
                        throw new Exception('Could not find post record. Try Again Later.');
                    }
                    $file = $statement->fetch()['image_url'];
                    if (file_exists($file)) {
                        if (!unlink($file)) {
                            $processingError = true;
                            throw new Exception('Error while deleting post. Image could not be deleted.');
                        }
                    } else {
                        $processingError = true;
                        throw new Exception('Error while deleting post. No image found.');
                    }

                    //Build query to delete record
                    $query = "DELETE FROM BikePost WHERE id = :id LIMIT 1";
                    $statement = $db->prepare($query);
                    $statement->bindValue(':id', $postid, PDO::PARAM_INT);
                    $result = $statement->execute();
                    if (!$result) {
                        $processingError = true;
                        throw new Exception('Error while deleting post. Try Again Later.');
                    }
                    header("Location: index.php");
                    exit;
                } else {
                    header("Location: index.php");
                    exit;
                }
                break;
        }
    } else {
        header("Location: index.php");
        exit;
    }

} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error While Processing</title>
</head>

<body>
    <main>
        <?php if ($processingError): ?>
        <p>
            <?= $errorMessage ?>
        </p>
        <?php endif ?>
        <a href="index.php">Back To Home Page</a>
    </main>
</body>

</html>