<?php
include '../imageMaker.class.php';

$image = new ImageMaker(533, 400, 'solid', '#fff', '../images/flower.jpg');
$image->new = $image->buildImage('../images/bird.png');

$image->drawBorder($image->image,'#ccff66',5);
$image->drawBorder($image->image,'#c2c2c2',2);
$image->drawBorder($image->new,'blu',10);
$image->positionImage($image->image,$image->new,150,140, array(150,200),array('bottom','right'));

$image->show($image->image);


?>
