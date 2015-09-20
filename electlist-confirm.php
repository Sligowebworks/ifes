<?php
    include( "includes/conf.php" );
if ($_REQUEST['ID']) {
  if (!$_REQUEST['ID'] || strlen($_REQUEST['ID'])!=32) {
    sess::set_error('Authorization Code is missing or invalid.');
  } else {
		$db = new Db();
		$db->Query("SELECT dw_id AS id FROM data_warehouse WHERE MD5(CONCAT(email,auth_string))='".$_REQUEST['ID']."'");
		if ($db->GetAffectedRows()==1) {
			$row = $db->fetchAssoc();
			$db1 = new Db();
			$db1->Query("UPDATE data_warehouse SET opted_in=1 WHERE dw_id=".$row['id']);
    	   sess::set_msg('Your Subscription has been activated.');
		} else {
    	   sess::set_error('Authorization Code not found.');
		}
    }
} 
header( "Location: newsletter.php" );
exit();
?>
