<?php

require("connect.php");
require("utils.php");


try {

    if ($_POST) {
        $command = filter_input(INPUT_POST, 'command', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $command = strtolower($command);
        $processingError = false;

        switch ($command) {
            case "update":
                if (
                    !empty($_POST['make']) && !empty($_POST['model']) && !empty($_POST['engine']) &&
                    !empty($_POST['year']) && !empty($_POST['displacement'])
                ) {

                    if (isset($_POST['removeimage']) && $_POST['removeimage'] === 'on') {
                        $oldimage = filter_input(INPUT_POST, 'imageOld', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        deleteFile($oldimage);
                        $image_storageFld = null;
                    } else {
                        // If an image is present delete the current one then replace it with the new one.
                        if (!empty($_FILES['image']['tmp_name'])) {
                            $oldimage = filter_input(INPUT_POST, 'imageOld', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                            deleteFile($oldimage);
                            // Move new image
                            $image_storageFld = getImageUrl($_FILES['image']['name'], $_FILES['image']['tmp_name']);
                        }
                    }

                    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                    $make = filter_input(INPUT_POST, 'make', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $engine = filter_input(INPUT_POST, 'engine', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $displacement = filter_input(INPUT_POST, 'displacement', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $displacement = filter_var($displacement, FILTER_VALIDATE_FLOAT);
                    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
                    flagError($year);
                    flagError($displacement);

                    if (!empty($_FILES['image']['tmp_name']) || $_POST['removeimage'] === 'on') {
                        $updateQuery = "UPDATE BikePost SET make = :make, model = :model, year = :year, engine = :engine,
                        displacement_ccm = :displacement, image_url = :imageurl
                        WHERE id = :id";
                        $all_bind_values = [
                            'make' => $make,
                            'model' => $model,
                            'year' => $year,
                            'engine' => $engine,
                            'displacement' => $displacement,
                            'imageurl' => $image_storageFld,
                            'id' => $id
                        ];
                    } else {
                        $updateQuery = "UPDATE BikePost SET make = :make, model = :model, year = :year, engine = :engine,
                        displacement_ccm = :displacement WHERE id = :id";
                        $all_bind_values = [
                            'make' => $make,
                            'model' => $model,
                            'year' => $year,
                            'engine' => $engine,
                            'displacement' => $displacement,
                            'id' => $id
                        ];
                    }

                    $statement = $db->prepare($updateQuery);
                    $result = $statement->execute($all_bind_values);
                    if (!$result) {
                        throw new Exception('Error while processing request. Please try again later.');
                    }
                    header("Location: index.php");
                    exit;
                }


                break;
            case "delete":
                if (!empty($_POST['id'])) {

                    $postid = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                    $selectquery = "SELECT image_url FROM BikePost WHERE id = :id";
                    $statement = $db->prepare($selectquery);
                    $statement->bindValue(':id', $postid, PDO::PARAM_INT);
                    $result = $statement->execute();
                    if (!$result) {
                        throw new Exception('Could not find post record. Try Again Later.');
                    }
                    $file = $statement->fetch()['image_url'];
                    if (file_exists($file)) {
                        if (!unlink($file)) {
                            // if ($_POST) {
                            //     // print_r($_POST);

                            //     throw new Exception('testing');
                            //     $content = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);
                            //     $commenter = filter_input(INPUT_POST, 'commenter', FILTER_SANITIZE_SPECIAL_CHARS);
                            //     $userid = filter_input(INPUT_POST, 'userid', FILTER_VALIDATE_INT);
                            //     $postid = filter_input(INPUT_POST, 'postid', FILTER_VALIDATE_INT);
                            //     $datecreated = new DateTime();
                            //     if ($userid === false || $userid === null) {
                            //         $anony = true;
                            //         $userid = null;
                            //     } else {
                            //         $anony = 0;
                            //     }
                            //     echo "<br>" . $userid;
                            //     echo "<br>" . $anony;
                            //     $query = "INSERT INTO comments (content, date_created, is_anonymous, commenter, userID, BikePostID)
                            //     VALUES (:content, :datecreated, :anony, :commenter, :userid, :postid)";

                            //     $all_bind_values = [
                            //         'content' => $content,
                            //         'datecreated' => $datecreated->format('Y-m-d H:i:s'),
                            //         'anony' => $anony,
                            //         'commenter' => $commenter,
                            //         'userid' => $userid,
                            //         'postid' => $postid
                            //     ];
                            //     // print_r($all_bind_values);
                            //     // throw new Exception('Testing');
                            //     $stmt = $db->prepare($query);
                            //     $result = $stmt->execute($all_bind_values);
                            //     if (!$result) {
                            //         throw new Exception('Error processing this request');
                            //     }
                            //     header("Location: post.php?id=" . $postid);
                            //     exit;
                            // }
                        }
                    }
                    // else {
                    //     throw new Exception('Error while deleting post. No image found.');
                    // }
                   
                    //Build query to delete record
                    $query = "DELETE FROM BikePost WHERE id = :id LIMIT 1";
                    $statement = $db->prepare($query);
                    $statement->bindValue(':id', $postid, PDO::PARAM_INT);
                    $result = $statement->execute();
                    if (!$result) {
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
    $processingError = true;
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