
<html>
<head>
<title>Image Upload Week 5</title>
</head>
<?php
ini_set('max_execution_time', 300);
?>
<body>
<form enctype="multipart/form-data" action="upload.php" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="return_bytes(ini_get('upload_max_filesize'))" />
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="upfile" type="file" />
    <input type="submit" value="Send File" />
    <br/> Maximum file size allowed = 16MB <br/>
</form>
</body>
</html>
<?php
//echo return_bytes(ini_get('upload_max_filesize'));
//echo return_bytes(ini_get('max_execution_time'));

$UPLOAD_DIR="./images";
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}
try {
   
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['upfile']['error']) ||
        is_array($_FILES['upfile']['error'])
    ) {
        //echo ini_get('upload_max_filesize');
        //echo ini_get('max_execution_time');
        throw new RuntimeException('Please choose an Image file (jpg,jpeg,png,gif) to upload.');
    }

    // Check $_FILES['upfile']['error'] value.
    switch ($_FILES['upfile']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // check filesize here.
    if ($_FILES['upfile']['size'] > return_bytes(ini_get('upload_max_filesize'))) {
        throw new RuntimeException('Exceeded filesize limit 16MB.');
    }

    // Check MIME Type.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['upfile']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    )) {
        throw new RuntimeException('Invalid file format.');
    }

    // upload binary data.
   set_time_limit(300); 
   //echo $UPLOAD_DIR."/".$_FILES['upfile']['name'];

   if (!move_uploaded_file(
        $_FILES['upfile']['tmp_name'],
        sprintf('./images/%s',
            $_FILES['upfile']['name'])
    ))
     {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    echo 'File '.$_FILES['upfile']['name'].' is uploaded successfully.';

} catch (RuntimeException $e) {

    echo $e->getMessage();

}

?>
