<?php
    include('includes/conf.php');
	$section = "home";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide</title>
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
				<h2>ElectionGuide:</h2>
				<p>ElectionGuide provides information on national and selected other elections around the world with coverage beginning in 1998. Move your mouse over the dots below to view the next 10 election dates or click a region to view a Region page. ElectionGuide is a product of the <a href="http://www.ifes.org/arc.html" target="_blank" style="color:black">F. Clifton White Applied Research Center</a>.</p>
				<p style="text-align: center;"><a name="themap"></a>
                <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="454" height="335" id="map1" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="map.swf" />
<param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />
<embed src="map.swf" quality="high" bgcolor="#ffffff" width="454" height="335" name="map1" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object></p>
				
				<div class="ad banner"><a href="http://www.codeinc.com/"><img src="images/ads/CODE-ElectionGuide-home-pg.gif" alt="Advertisement" /></a></div>
			</div>
			<hr />
			<div id="sidebar-wrapper">
				<?php
					include( "includes/sidebar-search.php" );
					include( "includes/sidebar-news.php" );
					//include( "includes/sidebar-ads.php" );
				?>
			</div>
		</div>
		<hr id="clear-hack" />
		<?php include( "includes/footer.php" ); ?>
	</div>

</body>
</html>
