<?php
/*
* Author : Amir Mustafa and Hanzhang Bai
* Subject : Crop photo using PHP and jQuery
*/

// Target siz
$targ_w = $_POST['targ_w'];
$targ_h = $_POST['targ_h'];
// quality
$jpeg_quality = 90;
$png_compress = 9;
// photo path
$src = $_POST['photo_url'];
if($exif_imagetype($src)==IMAGETYPE_PNG) {
  $img_r = imagecreatefrompng($src);
}else if($exif_imagetype($src)==IMAGETYPE_JPEG){
  $img_r = imagecreatefromjpeg($src);
}else{
echo "<script>alert(\"CL only supports PNG and JPEG format. \");</script>";
}
// create new jpeg image based on the target sizes
$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
// crop photo
imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'], $targ_w,$targ_h,$_POST['w'],$_POST['h']);
// create the physical photo
if($exif_imagetype($src)==IMAGETYPE_PNG) {
  imagepng($dst_r,$src,$png_compress);
}else if($exif_imagetype($src)==IMAGETYPE_JPEG){
  imagejpeg($dst_r,$src,$jpeg_quality);
}
// display the  photo - "?time()" to force refresh by the browser
echo '<img src="'.$src.'?'.time().'">';
exit;
?>
