<?php
define("DS",DIRECTORY_SEPARATOR);
define("ROOT","/opt/lampp/htdocs/ecounter");
	include(ROOT.DS.'class'.DS.'imageMaker.class.php');

        $image = new ImageMaker(88, 31, 'solid', 'fff', '');
        $image->new = $image->buildImage("../images/bird.jpg");
        $image->positionImage($image->image,$image->new,88,31, array(0,0),array('top','left'),false);
        $image->show($image->image);