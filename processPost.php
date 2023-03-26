<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require("connect.php");
require("utils.php");

// if (!$db) {
//     echo 'No Connection!';
//     die();
// }
$inputError = false;
$errorMessage = '';

try {
if ($_POST) {
        print_r($_POST);
    // Check that post is not empty and sanitize
        
    if (
        !empty($_POST['make']) && !empty($_POST['model']) && !empty($_POST['engine']) &&
            !empty($_POST['year']) && !empty($_POST['displacement'])
        ) {

            if (!empty($_FILES['image']['tmp_name']) && !($_FILES['image']['error'] > 0)) {
                $image_storageFld = getImageUrl($_FILES['image']['name'], $_FILES['image']['tmp_name']);
                throw new Exception('Testing Image');
            } else {
                $image_storageFld = '';
            }
            

        //Sanitize inputs
        $make = filter_input(INPUT_POST, 'make', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $engine = filter_input(INPUT_POST, 'engine', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $displacement = filter_input(INPUT_POST, 'displacement', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $displacement = filter_var($displacement, FILTER_VALIDATE_FLOAT);
        $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
            flagError($year);
            flagError($displacement);
        $datecreated = new DateTime();
            $imageURL = $image_storageFld;

            $userid = filter_var($_POST['userid'], FILTER_SANITIZE_NUMBER_INT);
            // print_r($_POST);
            // throw new Exception("SOMETHING");

            $insertQuery = "INSERT INTO BikePost (make , model , year, engine, 
        displacement_ccm, image_url, userID, date_created) VALUES (:make, :model, :year, :engine, 
        :displacement, :image_url, :userid, :datecreated)";

            $statement = $db->prepare($insertQuery);
            $all_bind_values = [
                'make' => $make,
                'model' => $model,
                'year' => $year,
                'engine' => $engine,
                'displacement' => $displacement,
                'image_url' => $imageURL,
                'userid' => $userid,
                'datecreated' => $datecreated->format('Y-m-d H:i:s')
            ];

            $result = $statement->execute($all_bind_values);
            if (!$result) {
                die('Error executing query: ' . $statement->errorInfo()[2]);
            }
            header("Location: index.php");
            exit;

    } else {
        throw new Exception('Invalid input. Please fill all the required fields and upload a valid image.');
    }
    } else {
        header("Location: index.php");
        exit;
    }
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
    <title>Error</title>
</head>

<body>
    <main>
        <?php if ($inputError): ?>
        <p>
            <?= $errorMessage ?>
        </p>
        <?php endif ?>
        <a href="index.php">Back To Home Page</a>
    </main>
</body>

</html>