<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="css/main.css" type="text/css" rel="stylesheet">

        <title>Img-r-us</title>
        <script src="http://code.jquery.com/jquery-latest.min.js">
        </script>
        <script type="text/javascript">
        $.ajax({
    type:'POST',
    url:'uploadForm.php',
    data:'',
    success: function(data){
            $('#uploadSection').html(data);
    }
});
        </script>
    </head>
    <body>
        <div class="left" id="uploadDiv"><h1>Upload Files </h1>
            <h2>Upload Limits:</h2>
                
            File types = jpg/png/gif<br/>
            Maximum file size = <?php echo ini_get('upload_max_filesize'); ?>
            <br/> Maximum number of files = <?php echo ini_get('max_file_uploads'); ?><br/>
            <div id="uploadSection">
                
            </div>
        </div>
        <div>
            <ul class="gal-1">
                <?php
                ini_set('max_execution_time', 300);

                $documentRoot = $_SERVER['DOCUMENT_ROOT'];
                $imageDir = "/images";
                $htmlImageDir = "images";
                $relativeImgPath = "";
                $imgExt = "";
                $exists = False;
                $thumbsDir = "." . $imageDir . "/thumbs";
                $quality = 100;
                $squareSize = 150;
                $totalImages = 20;
                
                //$requestTime = round(microtime(true) * 1000);
                
                $imageSet = glob('.' . $imageDir . '/*.*');

                shuffle($imageSet);
                $randomElements = array_slice($imageSet, 0, $totalImages);
                //$randomElements=  seeded_shuffle($imageSet);
                //glob is used to search the image directory for filename
                foreach ($randomElements as $key => $relativeImagePath) {
                    //echo $relativeImagePath."<br/>";
                    if (strcasecmp(mime_content_type($relativeImagePath), "image/jpeg") == 0 || strcasecmp(mime_content_type($relativeImagePath), "image/jpg") == 0 || strcasecmp(mime_content_type($relativeImagePath), "image/png") == 0 || strcasecmp(mime_content_type($relativeImagePath), "image/gif") == 0) {
                        $exists = True;
                        $relativeImgPath = $relativeImagePath;
                        //echo "Filename: " . $relativeImagePath . "<br />";
                        $path_parts = pathinfo($relativeImagePath);
                        //echo "Basename= ".$path_parts['basename']."<br/>";
                        //echo "Filename= ".$path_parts['filename']."<br/>";
                        $imageName = $path_parts['filename'];
                        $imgExt = '.' . $path_parts['extension'];
                        $htmlPath = $htmlImageDir . "/" . $imageName . $imgExt;
                        if ($exists) {
                            create_thumbnail($imageName . $imgExt, "." . $imageDir, $thumbsDir, $squareSize, $quality);
                            if (file_exists($thumbsDir . "/" . $imageName . $imgExt)) {
                                ?>
                <a href="view.php?id=<?php echo $imageName?>"><li><img src="<?php echo $thumbsDir . "/" . $imageName . $imgExt; ?>" alt=""></li></a>

                                <?php
                            } else {
                                echo "\nProblem creating thumbnail";
                            }
                        } else {
                            print "\nID does not exist";
                        }
                    }
                }

                function create_thumbnail($file, $photos_dir, $thumbs_dir, $square_size, $quality) {
                    $pp = pathinfo($file);
                    $imgExt = $pp['extension'];
                    //check if thumb exists
                    if (!file_exists($thumbs_dir . "/" . $file)) {
                        //get image info
                        list($width, $height, $type, $attr) = getimagesize($photos_dir . "/" . $file);

                        //set dimensions
                        if ($width > $height) {
                            $width_t = $square_size;
                            //respect the ratio
                            $height_t = round($height / $width * $square_size);
                            //set the offset
                            $off_y = ceil(($width_t - $height_t) / 2);
                            $off_x = 0;
                        } elseif ($height > $width) {
                            $height_t = $square_size;
                            $width_t = round($width / $height * $square_size);
                            $off_x = ceil(($height_t - $width_t) / 2);
                            $off_y = 0;
                        } else {
                            $width_t = $height_t = $square_size;
                            $off_x = $off_y = 0;
                        }
                        if (strcasecmp($imgExt, "jpg") == 0 || strcasecmp($imgExt, "jpeg") == 0)
                            $thumb = imagecreatefromjpeg($photos_dir . "/" . $file);
                        elseif (strcasecmp($imgExt, "gif") == 0)
                            $thumb = imagecreatefromgif($photos_dir . "/" . $file);
                        elseif (strcasecmp($imgExt, "png") == 0)
                            $thumb = imagecreatefrompng($photos_dir . "/" . $file);

                        $thumb_p = imagecreatetruecolor($square_size, $square_size);
                        //default background is black
                        $bg = imagecolorallocate($thumb_p, 255, 255, 255);
                        imagefill($thumb_p, 0, 0, $bg);
                        imagecopyresampled($thumb_p, $thumb, $off_x, $off_y, 0, 0, $width_t, $height_t, $width, $height);
                        imagejpeg($thumb_p, $thumbs_dir . "/" . $file, $quality);
                        /*
                          if(strcasecmp($imgExt,"jpg")==0 || strcasecmp($imgExt,"jpeg")==0)                         imagejpeg($thumb_p,$thumbs_dir."/".$file,$quality);
                          elseif(strcasecmp($imgExt,"gif")==0 )
                          imagegif($thumb_p,$thumbs_dir."/".$file,$quality);
                          elseif(strcasecmp($imgExt,"png")==0 ){
                          imagealphablending($thumb_p,false);
                          imagesavealpha($thumb_p,true);
                          imagepng($thumb_p,$thumbs_dir."/".$file,$quality);
                          } */
                    }
                }
                ?>
            </ul>
        </div>
    </body>
</html>
