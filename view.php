<html>
<body>
<?php 

ini_set('max_execution_time', 300);

$documentRoot=$_SERVER['DOCUMENT_ROOT'];
$projectRoot='/week4task1-2';
$imageDir="/images";
$htmlImageDir="images";
$imageName=$_GET['id'];

$imgExt="";
$exists=False;

if ( empty($imageName) || !isset($imageName) ) {
     echo "\nYou didn't specify an ID";
     exit;
}
//glob is used to search the image directory for filename
foreach(glob('.'.$imageDir.'/'.$imageName.'.*') as $relativeImagePath)
        {               
                 if( strcmp(mime_content_type($relativeImagePath),"image/jpeg") == 0 || strcmp(mime_content_type($relativeImagePath),"image/jpg") == 0 || strcmp(mime_content_type($relativeImagePath),"image/png") == 0 || strcmp(mime_content_type($relativeImagePath),"image/gif") == 0  ) {
                $exists=True; 
                echo "Filename: " . $relativeImagePath . "<br />";
                $path_parts = pathinfo($relativeImagePath); 
                $imgExt='.'.$path_parts['extension'];      
                } 
        }


$fqImagePath=$documentRoot.$projectRoot.$imageDir."/".$imageName.$imgExt;
$htmlPath=$htmlImageDir."/".$imageName.$imgExt;
//echo $htmlPath;
if($exists){

     ?>
         <img src="<?php echo $htmlPath; ?>"/>
     <?php
    
}else{
    print "\nID does not exist";
}


?>
</body>
</html>
