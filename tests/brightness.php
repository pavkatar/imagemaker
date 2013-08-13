<?php
include '../imageMaker.class.php';

$image = new ImageMaker(512, 384, 'solid', '#fff', '../images/flower.jpg');
 
    $image->brightness($image->image, 50);
$image->show($image->image);

?>
