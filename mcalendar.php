<?php
include('includes/conf.php');
$section = "calendar";
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>

<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html> <head>
<title></title>
</head>

<body>

<h2>Calendar of Elections:</h2>
<p>Please click the flags or the country names below to navigate to the country pages. Please click the Election to view the Election Profile information.</p>
				
<?php
include_once('admin/includes/Dbtable.Class.php');
include_once('admin/includes/Common.Dbtable.Class.php');
include_once('includes/ElectionList.Class.php');
ElectionList::show_them_all();
?>


<hr>
<address></address>
<!-- hhmts start --> <!-- hhmts end -->
</body> </html>
