<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require("connect.php");

if (!$db) {
    echo 'No Connection!';
    die();
}


if ($_POST) {
    // Check that post is not empty and sanitize
    $make = $_POST['make'];
    $model = $_POST['model'];
    $engine = $_POST['engine'];
    $year = $_POST['year'];
    $displacement = $_POST['displacement'];
    $datecreated = new DateTime();
    $imageURL = 'bikesimages/suzuki.jpg';

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
}


?>