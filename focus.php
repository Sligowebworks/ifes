<?php
require_once( "includes/conf.php" );

$op = ($_REQUEST['op']) ? $_REQUEST['op'] : "public";
$id = ($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$section = "utilities";


if ($op !== "public_component" ){
	$op = "public";
}

require_once("includes/Focus.Class.php");
$focus = new Focus();

foreach ($_SESSION as $n => $v){
	//if this is an administrator show inactive stuff
	if (is_array($v) && $v['is_admin'] == 1) {
	   $focus->is_admin = true;
	}
}

if ($id){
	$focus->load($id);
}

$focus->display($op);	