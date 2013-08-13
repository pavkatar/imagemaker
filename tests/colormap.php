<?php
include '../imageMaker.class.php';


$image = new ImageMaker(400, 380, 'solid', '#fff', '');
$image->new = $image->buildImage('../images/color.jpg');
$image->new = $image->crop($image->new, array(60,100), 400, 410);
$image->positionImage($image->image, $image->new, 400, 410);
$color = $image->imageColors($image->new, 4, 5);

$mainImage = new ImageMaker(400, 100, 'solid', '#fff');

$mainImage->newImage1 = $mainImage->makeImage($mainImage->newImage1, 100, 100);

$mainImage->fillSolid($mainImage->newImage1, array($color[0]));

$mainImage->positionImage($mainImage->image,$mainImage->newImage1,100,100, array(0,0),array('top','left')); //позиционираме снимка


$mainImage->newImage1 = $mainImage->makeImage($mainImage->newImage1, 100, 100);

$mainImage->fillSolid($mainImage->newImage1, array($color[1]));

$mainImage->positionImage($mainImage->image,$mainImage->newImage1,100,100, array(0,100),array('top','left')); //позиционираме снимка


$mainImage->newImage1 = $mainImage->makeImage($mainImage->newImage1, 100, 100);

$mainImage->fillSolid($mainImage->newImage1, array($color[2]));

$mainImage->positionImage($mainImage->image,$mainImage->newImage1,100,100, array(0,200),array('top','left')); //позиционираме снимка



$mainImage->newImage1 = $mainImage->makeImage($mainImage->newImage1, 100, 100);

$mainImage->fillSolid($mainImage->newImage1, array($color[3]));

$mainImage->positionImage($mainImage->image,$mainImage->newImage1,100,100, array(0,300),array('top','left')); //позиционираме снимка

$image->positionImage($image->image, $mainImage->image, 410, 100, array(-5,-5), array('bottom','left'));
$image->drawLine($image->image, 0, 281, 410, 281, '#fff');

$image->image = $image->drawShadow($image->image);

$image->show($image->image);//показване



?>
