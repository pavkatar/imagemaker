<?php
//First we include the library
include '../imageMaker.class.php';
/*
 * Then initialize the class
 * Params: width, height, background_type, background_color, image_path
 * Then initialize the class
 */
$image = new ImageMaker(533, 400, 'solid', '#fff', '../images/flower.jpg');
//Build new image to create watermark. Param: image_path
$image->new = $image->buildImage('../images/logo.png');
/*
 * Then position the new image somewhere in the main photo
 * Params: main_image, new_image, width, height, postion_array, from where to count the posiotion(bottom/top, left/right)
 * Then initialize the class
 */
$image->positionImage($image->image,$image->new,150,40, array(-5,-5),array('bottom','right'));

//And finally show the image
$image->show($image->image);


?>
