<?php
require_once("connect.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the JSON data from the request body
    $data = json_decode(file_get_contents("php://input"));
    
    // Check if the JSON data was decoded successfully
    if ($data !== null) {

        try {
            // // Accessing the commentid and hidden properties of the decoded JSON data
            $commentid = $data->commentid;
            $hidden = $data->hidden;

            //Filtering Request
            $commentid = filter_var($commentid, FILTER_VALIDATE_INT);
            $hidden = filter_var($hidden, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $bool = strcasecmp($hidden, 'true') === 0 ? 1 : 0;
            // Updating comment query
            $query = "UPDATE Comments SET hidden = :hidden WHERE commentID = :commentid";
            $stmt = $db->prepare($query);
            $stmt->bindValue('hidden', $bool);
            $stmt->bindValue('commentid', $commentid);

            $result = $stmt->execute();
            if (!$result) {
                throw new Exception('Database Failed To Process Request');
            }

            // Send a response back to the client
            // echo json_encode(['status' => 'success', 'commentid' => $commentid, 'hidden' => $hidden]);
            echo json_encode(['status' => 'sucess', 'resID' => $commentid, 'boolean' => $bool, 'query' => $query]);

        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }

    } else {
        // Handle the case where the JSON data could not be decoded
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
    }
}
?>