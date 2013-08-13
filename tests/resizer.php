<?php
include '../imageMaker.class.php';

$image = new ImageMaker(533, 400, 'solid', '#fff', '');
$image->new = $image->buildImage('../images/flower.jpg');
$image->positionImage($image->image,$image->new,533,200, array(0,0),array('top','left'),false);
$image->show($image->image);
//$image->Saver('png', '../images/bird.png', 100);
?>
