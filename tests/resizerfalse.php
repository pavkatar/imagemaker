<?php
include '../imageMaker.class.php';

$image = new ImageMaker(533, 200, 'solid', '#fff', '');
$image->new = $image->buildImage('../images/flower.jpg');
$image->positionImage($image->image,$image->new,533,200, array(0,0),array('top','left'),true);
$image->show($image->image);

?>
