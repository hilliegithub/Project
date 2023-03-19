<?php

define('BIKEFOLDER', 'bikeimages');
function getImageUrl($image_name, $image_tmp_name)
{
    $image_storageFld = get_image_original(basename($image_name));
    if (file_is_an_image($image_tmp_name, $image_storageFld)) {
        move_uploaded_file($image_tmp_name, $image_storageFld);
    } else {
        throw new Exception('File Failed to upload. Try again later');
    }
    return $image_storageFld;
}

function get_image_original($original_name)
{
    return join(DIRECTORY_SEPARATOR, [BIKEFOLDER, substr($original_name, 0, strlen($original_name) - 4) . '_' . time() . substr($original_name, -4)]);
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