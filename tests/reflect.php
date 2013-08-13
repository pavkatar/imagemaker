<?php
include '../imageMaker.class.php';

$image = new ImageMaker(512, 484, 'solid', '#fff', '');
    $image->backgroundImage = $image->buildImage('../images/flower.jpg');
    $image->positionImage($image->image,$image->backgroundImage,512,384, array(0,0),array('top','left')); //позиционираме снимка
        $reflection = $image->reflection($image->backgroundImage);
    $image->positionImage($image->image,$reflection,512,100, array(0,0),array('bottom','left')); //позиционираме снимка
$image->show($image->image);

?>
