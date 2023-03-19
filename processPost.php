<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require("connect.php");

// if (!$db) {
//     echo 'No Connection!';
//     die();
// }
$inputError = false;
$errorMessage = '';

try {
if ($_POST) {
        // print_r($_POST);
    // Check that post is not empty and sanitize
        
    if (
        !empty($_POST['make']) && !empty($_POST['model']) && !empty($_POST['engine']) &&
            !empty($_POST['year']) && !empty($_POST['displacement']) && !empty($_FILES['image']['tmp_name']) && !($_FILES['image']['error'] > 0)
    ) {

        $image_filename = $_FILES['image']['name'];
        $temporary_image_path = $_FILES['image']['tmp_name'];

        $image_storageFld = get_image_original(basename($image_filename));
            // echo "<br>" . $image_storageFld;
        if (file_is_an_image($temporary_image_path, $image_storageFld)) {
            move_uploaded_file($temporary_image_path, $image_storageFld);
        } else {
                throw new Exception('File Failed to upload. Try again later');
            }

        //Sanitize inputs
        $make = filter_input(INPUT_POST, 'make', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $engine = filter_input(INPUT_POST, 'engine', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
            flagError($year);
        $displacement = filter_input(INPUT_POST, 'displacement', FILTER_VALIDATE_FLOAT);
            flagError($displacement);
        $datecreated = new DateTime();
            $imageURL = $image_storageFld;

            // MODIFY FOR USERID!!!!!!!

            $insertQuery = "INSERT INTO BikePost (make , model , year, engine, 
        displacement_ccm, image_url, userID, date_created) VALUES (:make, :model, :year, :engine, 
        :displacement, :image_url, 1, :datecreated)";

            $statement = $db->prepare($insertQuery);
            $all_bind_values = [
                'make' => $make,
                'model' => $model,
                'year' => $year,
                'engine' => $engine,
                'displacement' => $displacement,
                'image_url' => $imageURL,
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

function get_image_original($original_name)
{
    // echo "<br>" . $original_name;
    // $len = strlen(join(DIRECTORY_SEPARATOR, [dirname(__FILE__), BIKEFOLDER, $original_name]));

    // if ($len > 100) {
    //     $shortName = join(DIRECTORY_SEPARATOR, [dirname(__FILE__), BIKEFOLDER, substr($original_name, 0, 10) . substr($original_name, -4)]);
    //     return $shortName;
    // }

    // return join(DIRECTORY_SEPARATOR, [dirname(__FILE__), BIKEFOLDER, $original_name]);
    return join(DIRECTORY_SEPARATOR, [BIKEFOLDER, $original_name]);
}

function file_is_an_image($temporary_path, $new_path)
{
    $allowed_mime_types = ['image/jpeg', 'image/png'];
    $allowed_file_extensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];

    $actual_file_extension = pathinfo($new_path, PATHINFO_EXTENSION);
    $actual_mime_type = getimagesize($temporary_path)['mime'];

    $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
    $mime_type_is_valid = in_array($actual_mime_type, $allowed_mime_types);

    return $file_extension_is_valid && $mime_type_is_valid;
}

function flagError($value)
{
    if ($value === false || $value === null) {
        $inputError = true;
        throw new Exception('Invalid input. Please enter valid data in required fields.');
    }
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