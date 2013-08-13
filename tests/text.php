<?php
include '../imageMaker.class.php';

$image = new ImageMaker(512, 484, 'solid', '#fff', '../images/bird.jpg');

    $image->text("Top-Left", $image->image, 20, array(20,0), array('top','left'), 'red', '../images/font.ttf', 0);
    $image->text("Top-Center", $image->image, 20, array(20,20), array('top','center'), 'blu', '../images/font.ttf', 0);
    $image->text("Top-Right", $image->image, 20, array(20,-50), array('top','right'), 'fff', '../images/font.ttf', 0);

    $image->text("Center-Left", $image->image, 20, array(-90,0), array('center','left'), 'dadada', '../images/font.ttf', -45);
    $image->text("Center-Center", $image->image, 20, array(-50,50), array('center','center'), 'fff', '../images/font.ttf', 0);
    $image->text("Center-Right", $image->image, 20, array(30,-230), array('center','right'), 'cc44ff', '../images/font.ttf', 90);

    $image->text("Bottom-Left", $image->image, 20, array(100,0), array('bottom','left'), '000', '../images/font.ttf', 0);
    $image->text("Bottom-Center", $image->image, 20, array(120,50), array('bottom','center'), 'fff', '../images/font.ttf', -10);
    $image->text("Bottom-Right", $image->image, 20, array(100,-100), array('bottom','right'), 'fff', '../images/font.ttf', 15);

$image->show($image->image);

?>
