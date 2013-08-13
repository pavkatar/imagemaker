<?php
include '../imageMaker.class.php';

$image = new ImageMaker(512, 384, 'solid', '#fff', '../images/bird.png');
    $image->imagegreyscale($image->image,1);   
$image->show($image->image);

?>
