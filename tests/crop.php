<?php
include '../imageMaker.class.php';

$image = new ImageMaker(200, 200, 'solid', '#fff', '');
$image->new = $image->buildImage('../images/flower.jpg');
$image->new = $image->crop($image->new, array(160,0), 200, 200); //режем изображение
$image->positionImage($image->image,$image->new,200,200, array(0,0),array('center','center'),false);
$image->show($image->image);

?>
