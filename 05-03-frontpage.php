<?
/**
* Thumbnail layout system
* Grabs the contents of the images folder, randomizes it and displays in blocks
* 
* @author Matt Carter <m@ttcarter.com>
* @date 2014-02-11
*/

// Grab all images
$files = glob('images/*');

// This line seeds the random number generator with the current time divided into 5 minute blocks
// Uncomment this to get a random selection of images that changes every 5 minutes
// srand(time() / (5*60));

// Shuffle it
shuffle($files);

$imgno = 0;
?>

<table>

<? for($row = 0; $row < 4; $row++) { ?>
	<tr>
		<? for ($col = 0; $col < 4; $col++) { ?>
			<td>
				<img src="<?=$files[$imgno++]?>" style="width: 150px; height: 150px"/>
			</td>
		<? } ?>
	</tr>
<? } ?>

</table>
