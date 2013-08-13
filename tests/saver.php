<?php
include '../imageMaker.class.php';

$image = new ImageMaker(81, 31, 'solid', 'fff', '../images/counter.gif');

$image->show($image->image);
$image->Saver('png','/opt/lampp/htdocs/imageMaker/images/counter.png');
?>
