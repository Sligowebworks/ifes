<?php
    include( "includes/conf.php" );
	$section = "utilities";
    $db = new Db();
	$db->Query("ALTER TABLE data_warehouse CHANGE `org_type` `org_type` VARCHAR(100) NOT NULL DEFAULT ''");
?>
