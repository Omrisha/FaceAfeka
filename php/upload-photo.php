<?php
    if($_FILES["file"]["name"] != ''){
        $test = explode(".", $_FILES["file"]["name"]);
        $extenstion = end($test);
        $name = rand(100, 999) . "." . $extenstion;
        $location = '/upload/'.$name;
        move_uploaded_file($_FILES["file"]["tmp_name"], $location);

        //echo $location;
        echo '<div class="col-lg-3 col-md-4 col-6"><img id="'.$_GET['id'].'" src="'.$location.'" height=100 width=175 class="img-thumbnail" /></div>';
    }

?>
