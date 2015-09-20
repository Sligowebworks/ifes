<?php
    include('includes/conf.php');
	$section = "utilities";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Privacy Policy</title>
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
			
			<!-- AD begins here -  OL Feb 28, 2006-->
			<div class="ad banner"><a href="http://www.codeinc.com/" target="_blank"><img src="images/ads/rotate/codeinc.gif" alt="Advertisement" /> </a></div>
			<!-- AD ends here-->
			
			
				<h2>Privacy Policy:</h2>
				<p>IFES is a non-profit, non-partisan 501(c)(3) foundation dedicated to providing assistance in monitoring, supporting, and strengthening the mechanics of the election process around the world.</p>
				<p>Copyright Notice: Copyrights &copy; 1998, 1999, 2000, 2001, 2002, 2003, 2004, 2005 - IFES. Information from this electronic publication may not be reproduced without providing full credit to IFES.</p>
				<p>Acknowledgment: The information in this electronic publication may be used, copied, and distributed. However, in all cases acknowledgment of the IFES ElectionGuide.Org as the source of the material is required.</p>
				<p>Flags used on this website are obtained from World Flag Database and Political Resources on the Net.</p>
				<p>The IFES ElectionGuide is a publication of the F. Clifton White Applied Research Center, IFES, 1101 15th Street NW, Third Floor, Washington, DC 20005, U.S.A.</p>
				
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
