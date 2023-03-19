<?php

require("connect.php");

print_r($_POST);


try {

    if ($_POST) {
        $command = filter_input(INPUT_POST, 'command', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $command = strtolower($command);
        $processingError = false;

        switch ($command) {
            case "update":
                echo "update";
                break;
            case "delete":

                break;
        }
    } else {
        header("Location: index.php");
        exit;
    }

} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}

?>