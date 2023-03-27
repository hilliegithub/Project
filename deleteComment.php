<?php
require_once("connect.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $instr = json_decode(file_get_contents("php://input"));

    if ($instr !== null) {

        try {
            $commentid = $instr->commentid;
            $instruction = $instr->instruction;

            if ($instruction !== 'delete') {
                throw new Exception('Invalid instruction to api');
            }

            $commentid = filter_var($commentid, FILTER_VALIDATE_INT);
            $query = "DELETE FROM Comments WHERE commentID = :commentid LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->bindValue('commentid', $commentid);
            $result = $stmt->execute();

            if (!$result) {
                throw new Exception('Request could not be processed. Try again later.');
            }
            echo json_encode(['status' => 'sucess']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        // Handle the case where the JSON data could not be decoded
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
    }
}

?>