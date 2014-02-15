<?php
 
// Use in the "Post-Receive URLs" section of your GitHub repo.
 
if ( $_POST['payload'] ) {
echo "in";
shell_exec( 'cd /var/www/ && sudo git reset --hard HEAD && sudo git pull' );
}
 
?>
