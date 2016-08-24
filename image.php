<?php
header( "Content-type: image/png" );
if( $_GET["t"]){
  $time = $_GET["t"]/1000 .' s';
} else {
  $time = " something \ngone wrong";
}

$my_img = imagecreatefrompng('sources/shareImageClean.png');
$text_colour = imagecolorallocate( $my_img, 152, 157, 148 );
imagettftext( $my_img, 130, 0, 275, 300, $text_colour, 'sources/Roboto-Regular.ttf', $time);

imagepng( $my_img );
imagecolordeallocate( $text_color );
imagedestroy( $my_img );
?>
