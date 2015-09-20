<?php
    include('includes/conf.php');
	$section = "election";
	$page = "events";
	$id = $_REQUEST['ID'];
	if ($id=='') {
        ob_end_clean();
        header('location: calendar.php');
        exit();
    }
	$db = new Db();
	$db->Query("SELECT id, region, region_desc FROM regions WHERE id=".$id);
	if ($db->GetAffectedRows()==1)
	    $data = $db->fetchAssoc();
 	else {
	    ob_end_clean();
	    header('location: calendar.php');
	    exit();
	}
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Election Profile for <?php echo $data['region']; ?> - Events</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/ifes.css" />
	<link rel="home" title="Home" href="http://www.electionguide.org/" />
</head>
<body id="body-<?php echo $section; ?>">
	<?php include( "includes/jump-links.php" ); ?>
	<div id="wrapper">
		<?php include( "includes/header.php" ); ?>
		<hr />
		<div id="main-wrapper">
			<div id="content-wrapper">
				<?php include( "includes/nav-region.php" ); ?>
				<h2>Region Profile:</h2>
				<h3>Events for <?php echo $data['region']; ?></h3>
				<?php
				if ($_REQUEST['year'])
                    sess::set('year', $_REQUEST['year']);
        		include_once('admin/includes/Dbtable.Class.php');
        		include_once('admin/includes/Common.Dbtable.Class.php');
        		include_once('includes/ElectionList.Class.php');
        		ElectionList::show_dropdowns_country('region-events', $id);
        		ElectionList::show_them_by_region($id);

				?>
			</div>
			<hr />
			<div id="sidebar-wrapper">
				<?php
					include( "includes/sidebar-search.php" );
					include( "includes/sidebar-elections.php" );
					include( "includes/sidebar-news.php" );
				?>
			</div>
		</div>
		<hr id="clear-hack" />
		<?php include( "includes/footer.php" ); ?>
	</div>

</body>
</html>
