<?php
include '../imageMaker.class.php';

$image = new ImageMaker(512, 200, 'solid', '#fff', '');

$image->imageBlur = $image->makeImage($image->imageBlur, 512, 100);
$image->fillSolid($image->imageBlur, array('fff'));
$image->text('Not Blur!', $image->imageBlur, 20, array(20,0), array('top','left'), '#000');
$image->text("Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna", $image->imageBlur, 8, array(40,0), array('top','left'), '#000','../images/font.ttf');
$image->text("aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut ", $image->imageBlur, 8, array(50,0), array('top','left'), '#000','../images/font.ttf');
$image->text(" lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse", $image->imageBlur, 8, array(60,0), array('top','left'), '#000','../images/font.ttf');
$image->text("aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, v", $image->imageBlur, 8, array(70,0), array('top','left'), '#000','../images/font.ttf');
$image->text("el illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum", $image->imageBlur, 8, array(80,0), array('top','left'), '#000','../images/font.ttf');
      $image->positionImage($image->image, $image->imageBlur, 512, 100);

      $image->imageBlur = $image->makeImage($image->imageBlur, 512, 100);
$image->fillSolid($image->imageBlur, array('fff'));
$image->text('Blur!', $image->imageBlur, 20, array(20,0), array('top','left'), '#000');
$image->text("Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna", $image->imageBlur, 8, array(40,0), array('top','left'), '#000','../images/font.ttf');
$image->text("aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut ", $image->imageBlur, 8, array(50,0), array('top','left'), '#000','../images/font.ttf');
$image->text(" lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse", $image->imageBlur, 8, array(60,0), array('top','left'), '#000','../images/font.ttf');
$image->text("aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, v", $image->imageBlur, 8, array(70,0), array('top','left'), '#000','../images/font.ttf');
$image->text("el illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum", $image->imageBlur, 8, array(80,0), array('top','left'), '#000','../images/font.ttf');

            $image->blur($image->imageBlur);
    $image->positionImage($image->image, $image->imageBlur, 512, 100,array(100,0));

$image->show($image->image);

?>
