<?
/**
* Example multiple file uploader
*
* NOTE: This file is kept as simple as possible fo the sake of example
*       Its much better to grab an off-the-shelf uploading component that takes care of most of the operations here for you.
*
*
* @author Matt Carter <m@ttcarter.com>
* @date 2014-02-11
*/

if ($_FILES && isset($_FILES['file'])) { // An upload operation is happening
	foreach ($_FILES['file']['tmp_name'] as $no => $tmp) {
		if (!$tmp) // No file given - skip it
			continue;

		$id = basename($tmp); // Extract just the file part of the temporary name which gives us a unique ID
		$id = substr($id, 3); // Clip off the 'php' prefix

		if ($_FILES['file']['type'][$no] == 'image/jpeg') {
			$ext = 'jpg';
		} elseif ($_FILES['file']['type'][$no] == 'image/png') {
			$ext = 'png';
		} else {
			echo "Unknown file type<br/>";
			continue;
		}
		echo "Saving $id.$ext...<br/>";

		// Next line disabled for sake of example
		// move_uploaded_file($tmp, "images/$id.$ext");
	}
} else {  // No files given - show the interface ?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Multiple upload example</title>
	<script src="https://code.jquery.com/jquery-git1.min.js"></script>
	<script>
	$(function() {
		// Every time a file input box gets a value (i.e. a file has been attached)
		$('#uploads').on('change', 'input[type=file]', function() {
			$(this).after('<br/><input type="file" name="file[]"/>');
		});
	});
	</script>
</head>
<body>
	<h1>Upload files</h1>
	<form id="uploads" method="POST" action="?" enctype="multipart/form-data">
		<input type="file" name="file[]"/>
		<hr/>
		<input type="submit" value="Upload"/>
	</form>
</body>
</html>
<? } ?>
