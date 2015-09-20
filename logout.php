<?php
  include( "includes/conf.php" );
 	sess::set('loggedin', FALSE);
 	sess::unset_all();
  sess::set_msg('You are logged out.');
	header( "Location: eguide.php" );
	exit();
?>