<?php

$processingError = false;

try {
    if ($_POST) {
        print_r($_POST);
    }
} catch (Exception $e) {
    $processingError = true;
}
?>