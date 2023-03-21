<?php
require_once("connect.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if ($_POST) {
        // print_r($_POST);

        $content = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);
        $userid = filter_input(INPUT_POST, 'userid', FILTER_VALIDATE_INT);
        $postid = filter_input(INPUT_POST, 'postid', FILTER_VALIDATE_INT);
        $datecreated = new DateTime();
        if ($userid === false || $userid === null) {
            $anony = true;
        } else {
            $anony = false;
        }
        $query = "INSERT INTO comments (content, date_created,is_anonymous, userID, BikePostID)
        VALUES (:content, :datecreated, :anony, :userid, :postid)";

        $all_bind_values = [
            'content' => $content,
            'datecreated' => $datecreated->format('Y-m-d H:i:s'),
            'anony' => $anony,
            'userid' => $userid,
            'postid' => $postid
        ];
        print_r($all_bind_values);
        $stmt = $db->prepare($query);
        $result = $stmt->execute($all_bind_values);
        // throw new Exception('Testing');
        if (!$result) {
            throw new Exception('Error processing this request');
        }
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
    <title>Error Processing</title>
</head>

<body>
    <main>
        <?php if ($processingError): ?>
        <?= $errorMessage ?>
        <?php endif ?>
    </main>
</body>

</html>