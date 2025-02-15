<?php
/*
* Author : Amir Mustafa
* Email : amirengg15@gmail.com
* Subject : Crop photo using PHP and jQuery
*/

// get the tmp url 															//Cropped photo is saved in images folder
$photo_src = $_FILES['photo']['tmp_name'];
if ($_FILES['photo']['type'] == 'image/png') {
	$photo_format = '.png';
}else{
	$photo_format = '.jpg';
}
// test if the photo realy exists
if (is_file($photo_src)) {
	// photo path in our example
	$photo_dest = 'images/photo_'.time().$photo_format;
	// copy the photo from the tmp path to our path
	copy($photo_src, $photo_dest);
	// call the show_popup_crop function in JavaScript to display the crop popup
	echo '<script type="text/javascript">window.top.window.show_popup_crop("'.$photo_dest.'")</script>';
}
?>
