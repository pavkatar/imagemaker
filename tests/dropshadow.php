<?php
include '../imageMaker.class.php';

$image = new ImageMaker(533, 400, 'solid', '#fff', '../images/bird.jpg');

$image->image=$image->drawShadow($image->image);

$image->show($image->image);


?>
