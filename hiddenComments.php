<?php
require_once("connect.php");

header('Content-Type: application/json');
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the JSON data from the request body
    $data = json_decode(file_get_contents("php://input"));

    // Check if the JSON data was decoded successfully
    if ($data !== null) {

        // Accessing the commentid and hidden properties of the decoded JSON data
        $commentid = $data->commentid;
        $hidden = $data->hidden;

        //Filtering Request
        $commentid = filter_var($commentid, FILTER_VALIDATE_INT);
        $hidden = filter_var($hidden, FILTER_VALIDATE_BOOL);


        // Updating comment query
        $query = "UPDATE Comments SET hidden = :hidden WHERE commentID = :commentid";
        $stmt = $db->prepare($query);
        $stmt->bindValue('hidden', $hidden);
        $stmt->bindValue('commentid', $commentid);

        $result = $stmt->execute();
        if (!$result) {
            echo json_encode(['status' => 'error', 'message' => 'Database Failed To Process Request']);
        }
        // Send a response back to the client
        echo json_encode(['status' => 'success', 'commentid' => $commentid, 'hidden' => $hidden]);

    } else {
        // Handle the case where the JSON data could not be decoded
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
    }

} else {
    // Handle the case where the request method is not POST
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

?>