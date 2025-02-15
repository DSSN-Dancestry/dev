<?php

// Target siz
$targ_w = $_POST['targ_w'];
$targ_h = $_POST['targ_h'];
$sw=$_POST['sw'];
$sy=$_POST['sh'];
// quality
$jpeg_quality = 90;
$png_compress = 9;
// photo path
$src = $_POST['photo_url'];
echo $src;
$file_format = strpos($src, '.png');
if($file_format) {
  $img_r = imagecreatefrompng($src);
}else{
  $img_r = imagecreatefromjpeg($src);
}
// create new jpeg image based on the target sizes


// crop photo
$crop_rec=['x' => $_POST['x'], 'y' => $_POST['y'], 'width' => $_POST['w'], 'height' => $_POST['h']];
$original_x=imagesx($img_r);
$original_y=imagesy($img_r);
$rx=$original_x/$sw;
$ry=$original_y/$sy;
$crop_rec['x']=intval ($crop_rec['x']*$rx);
$crop_rec['y']=intval ($crop_rec['y']*$ry);

$crop_rec['width']=intval ($crop_rec['width']*$rx);
$crop_rec['height']=intval ($crop_rec['height']*$ry);



$crop_img=imagecrop($img_r,$crop_rec);
$dst_r=imagescale($crop_img,$targ_w,$targ_h);

if($file_format) {
  imagepng($dst_r,$src,$png_compress);
}else{
  imagejpeg($dst_r,$src,$jpeg_quality);
}
// display the  photo - "?time()" to force refresh by the browser
echo '<img src="'.$src.'?'.time().'">';

exit;
?>
