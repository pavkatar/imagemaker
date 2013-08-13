<?php
include '../imageMaker.class.php';

$image = new ImageMaker(533, 400, 'solid', 'fff', '');
$image->image = $image->rotate($image->image, 150);

$image->new = $image->buildImage('../images/bird.png');

$image->new = $image->rotate($image->new, 0);

$image->positionImage($image->image,$image->new,150,140, array(0,0),array('center','center'));

$image->show($image->image);


?>
