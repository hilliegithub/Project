<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("connect.php");

$response = [
    'success' => false,
    'usernameAvailable' => false
];

header('Content-Type: application/json');

if ($_GET) {
    try {

        if (isset($_GET['username']) && (strlen($_GET['username']) !== 0)) {
            $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $query = "SELECT username FROM user WHERE username = :username";
            $stmt = $db->prepare($query);
            $stmt->bindValue('username', $username);
            $result = $stmt->execute();
            if (!$result) {
                throw new Exception('Error processing request');
            }
            // echo json_encode(['count' => $stmt->rowCount()]);
            if ($stmt->rowCount() === 0) {
                $response['usernameAvailable'] = true; //!(in_array($username, $stmt->fetchAll()));
                $response['success'] = true;
            } else {
                $response['success'] = true;
            }
            echo json_encode($response);
        } else {
            throw new Exception('Invalid Request. Empty param.');
        }

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'status' => 'error', 'message' => 'Invalid Request']);
}




?>