<?php
include '../imageMaker.class.php';

$image = new ImageMaker(509, 688, 'solid', '#fff', '../images/flower.jpg');
$image->new = $image->buildImage('../images/transperant.png');
$image->positionImage($image->image,$image->new,509,688, array(0,0),array('top','left'));
$image->show($image->image);

?>
