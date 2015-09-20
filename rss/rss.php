<?php

include('../../includes/rsscreator.php');
ob_end_clean();
header("Content-type: application/xml");
header("Content-length: " . strlen($str));
echo $str;
?>
