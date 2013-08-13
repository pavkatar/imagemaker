<?php
include '../imageMaker.class.php';

$image = new ImageMaker(533, 400, 'solid', 'fff', '');
$image->new = $image->buildImage('../images/bird.png');

$image->positionImage($image->image,$image->new,533,400, array(0,0),array('center','center'));
$image->image = $image->flipHorizontal($image->image);
$image->show($image->image);
?>
