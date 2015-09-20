<?php
    include('includes/conf.php');
	$section = "calendar";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Election Calendar</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/ifes.css" />
	<link rel="home" title="Home" href="http://www.electionguide.org/" />
	<script type="text/javascript" src="scripts/striper.js"></script>
	<script type="text/javascript">window.onload = function() { stripe( "election-calendar", "#dde5f0", "#efefe7" ); }</script>
</head>

<body id="body-<?php echo $section; ?>">

	<?php include( "includes/jump-links.php" ); ?>
	<div id="wrapper">
		<?php include( "includes/header.php" ); ?>
		<hr />
		<div id="main-wrapper">
			<div id="content-wrapper">
			
			<!-- AD begins here -  OL Feb 28, 2006-->
			<!-- <div class="ad banner"><a href="http://www.secureelection.com/" target="_blank"><img src="images/ads/rotate/rifkin.gif" alt="Advertisement" /> </a></div>-->
			
			
				<h2>Calendar of Elections:</h2>
				<p>Please click the flags or the country names below to navigate to the 
				country pages. Please click the Election to view the Election Profile information.</p>
				
				<?php
                include_once('admin/includes/Dbtable.Class.php');
        		include_once('admin/includes/Common.Dbtable.Class.php');
        		include_once('includes/ElectionList.Class2.php');
				ElectionList::show_them_all();
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
