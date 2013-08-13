<?php
include '../imageMaker.class.php';

$image = new ImageMaker(512, 384, 'solid', '#fff', '../images/flower.jpg');

    $image->contrast($image->image, -20);
$image->show($image->image);

?>
