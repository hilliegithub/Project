<?php

// print_r($_POST);
// // Retrieve data from the POST request
// $userId = $_POST['userId'];
// $updatedUsername = $_POST['username'];
// $updatedEmail = $_POST['email'];

// Update user in the database
// ...

$myresponse = [
    'message' => 'TestING',
    'success' => true,
];


// Return JSON response
header('Content-Type: application/json');
echo json_encode($myresponse);


?>