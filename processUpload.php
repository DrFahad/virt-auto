
<?php

//echo return_bytes(ini_get('upload_max_filesize'));
//echo return_bytes(ini_get('max_execution_time'));

$UPLOAD_DIR = "./images";
ini_set('max_execution_time', 300);

function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val) - 1]);
    switch ($last) {
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

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

//if (strtolower($_SERVER['REQUEST_METHOD']) == 'post' && !empty($_FILES)) {
//echo $_SERVER['REQUEST_METHOD'];

try {



    if ($_FILES['upfile']) {
        $file_ary = reArrayFiles($_FILES['upfile']);

        foreach ($file_ary as $file) {
            //echo "<br />";
            //echo "<br />";
            //echo $file['name'].'('. $file['type'].') - ';
            //$file['size'];
            //echo "<br />";
            //echo 'File Error: '.$file['error'];
            //echo "<br/>";
            //echo 'File Set? '. isset($file['error']);
            //echo "<br/>";
            //echo var_dump($file);


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
                //echo "File not selected.";
                //break;
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
                    $finfo->file($file['tmp_name']), array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                    ), true
                    )) {
                throw new RuntimeException('Invalid file format.');
            }

            // upload binary data.
            set_time_limit(5000);
            //echo $UPLOAD_DIR."/".$_FILES['upfile']['name'];

            if (!move_uploaded_file(
                            $file['tmp_name'], sprintf('./images/%s', $file['name'])
                    )) {
                throw new RuntimeException('Failed to move uploaded file.');
            }

            echo $file['name'] . '(' . $file['type'] . ') - ' . ' Uploaded successfully.<br/>';
        }
    }
} catch (RuntimeException $e) {

    echo $e->getMessage();
}
//}
?>
