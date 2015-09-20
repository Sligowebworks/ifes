<?php
include('includes/conf.php');
foreach($_SESSION[SESS_NAME] as $key => $value) {
    $value='';
    unset($_SESSION[SESS_NAME][$key]);
}
?>
