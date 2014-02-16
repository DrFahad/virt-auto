
<html>
<head>
<title>Image Upload Week 5</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
 </script>
<script type="text/javascript">
     $(document).ready(function(){
 var cnt = 2;
 $("#anc_add").click(function(){
 if($('#tbl1 tr').length){
     $('#tbl1 tr').last().after('<tr><td>File ['+cnt+']</td><td><input name="upfile[]" type="file" multiple="multiple" /></td></tr>');
        cnt++;
    }else{
    cnt=1;    
    $('#tbl1').append('<tr><td>File ['+cnt+']</td><td><input name="upfile[]" type="file" multiple="multiple" /></td></tr>');
        cnt++;
    }
    
 });
 
$("#anc_rem").click(function(){
 $('#tbl1 tr:last-child').remove();
 cnt--;
 });
 
});
</script>

</head>
<?php
ini_set('max_execution_time', 300);
?>
<body>
<form enctype="multipart/form-data" action="upload.php" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="return_bytes(ini_get('upload_max_filesize'))" />
    <!-- Name of input element determines name in $_FILES array -->
    <!-- Upload this file: <input name="upfile[]" type="file" multiple="multiple" /> -->
    
     <br /><br />
     <table  id="tbl1" border="0">
        <tr><td>File [1]</td><td><input name="upfile[]" type="file" multiple="multiple" /></td></tr>
    </table>
     <br/>
     <a href="javascript:void(0);" id='anc_add'>Add File</a>
     <a href="javascript:void(0);" id='anc_rem'>Remove File</a>
     <br/><br/><br/>
    <input type="submit" value="Upload File" />
    
    <br/> Maximum file size allowed = 16MB
     <br/> Maximum number of files allowed = 20 <br/>
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

function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}


if( strtolower( $_SERVER[ 'REQUEST_METHOD' ] ) == 'post' && !empty( $_FILES ) )
{
        


try {
   
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    //echo $_FILES['upfile']['name'][0];
    //echo $_FILES['upfile']['name'][1];
    //var_dump($_FILES);
    /*
    if ($_FILES['upfile']) {
        $file_ary = reArrayFiles($_FILES['upfile']);

        foreach ($file_ary as $file) {
             echo "<br />";
              echo "<br />";
            echo 'File Name: ' . $file['name'];
            echo "<br />";
            echo 'File Type: ' . $file['type'];
            echo "<br />";
            echo 'File Size: ' . $file['size'];
            echo "<br />";
        }
    }
    */
    
    if ($_FILES['upfile']) {
        $file_ary = reArrayFiles($_FILES['upfile']);

        foreach ($file_ary as $file) {
             echo "<br />";
              echo "<br />";
            echo 'File Name: ' . $file['name'];
            echo "<br />";
            echo 'File Type: ' . $file['type'];
            echo "<br />";
            echo 'File Size: ' . $file['size'];
            echo "<br />";
            echo 'File Error: '.$file['error'];
            echo "<br/>";
            echo 'File Set? '. isset($file['error']);
            echo "<br/>";
        
    

    if (
        !isset($file['error']) ||
        is_array($file['error'])
    ) {
        //echo ini_get('upload_max_filesize');
        //echo ini_get('max_execution_time');
        throw new RuntimeException('Please choose an Image file (jpg,jpeg,png,gif) to upload.');
    }

    // Check $_FILES['upfile']['error'] value.
    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('File not selected.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // check filesize here.
    if ($file['size'] > return_bytes(ini_get('upload_max_filesize'))) {
        throw new RuntimeException('Exceeded filesize limit 16MB.');
    }

    // Check MIME Type.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($file['tmp_name']),
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
        $file['tmp_name'],
        sprintf('./images/%s',
            $file['name'])
    ))
     {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    echo 'File '.$file['name'].' is uploaded successfully.';
        }
    }
} catch (RuntimeException $e) {

    echo $e->getMessage();

}
}

?>
