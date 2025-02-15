<?php
$cropped_data = $_POST['cropped_image'];

$photo_url = $_POST['photo_url'];

list($type, $cropped_data) = explode(';', $cropped_data);
list(, $cropped_data) = explode(',', $cropped_data);
$cropped_data = base64_decode($cropped_data);

unlink($photo_url);

file_put_contents($photo_url, $cropped_data);

echo $photo_url;
?>
