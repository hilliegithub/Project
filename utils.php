<?php

define('BIKEFOLDER', 'bikeimages');
define('RESIZE_VALUE', 300);
require('./php-image-resize-master/lib/ImageResize.php');
use \Gumlet\ImageResize;
function deleteFile($file)
{
    if (file_exists($file)) {
        unlink($file);
    }
}

function getImageUrl($image_name, $image_tmp_name)
{
    $image_storageFld = get_image_original(basename($image_name));
    if (file_is_an_image($image_tmp_name, $image_storageFld)) {
        // move_uploaded_file($image_tmp_name, $image_storageFld);
        $image = new ImageResize($image_tmp_name);
        $image->resizeToWidth(RESIZE_VALUE);
        $image->save($image_storageFld);
    } else {
        throw new Exception('Please note that you should upload a valid image file (jpg or png). File Failed to upload. Try again later');
    }
    return $image_storageFld;
}

function get_image_original($original_name)
{
    return join(DIRECTORY_SEPARATOR, [BIKEFOLDER, substr($original_name, 0, strpos($original_name, ".")) . '_' . time() . substr($original_name, -1 * (strlen($original_name) - strpos($original_name, ".")))]);
}

function file_is_an_image($temporary_path, $new_path)
{
    $allowed_mime_types = ['image/jpeg', 'image/png'];
    $allowed_file_extensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];

    $actual_file_extension = pathinfo($new_path, PATHINFO_EXTENSION);
    $actual_mime_type = getimagesize($temporary_path)['mime'];
    // echo "<br>" . $actual_mime_type . "<br> " . $actual_file_extension . "<br>" . $new_path;
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