<?php
	include( "includes/conf.php" );
	$section = "utilities";
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
			
			<!-- AD begins here -  OL Feb 28, 2006-->
			<img align="right" src="images/ads/samplead.jpg" />
			<!-- AD ends here-->
			
				<h2>Glossary:</h2>
				<p>The following is a list of electoral and political terms that frequently
                appear on the ElectionGuide and its component pages.  Unless otherwise noted,
                definitions are adopted from World Encyclopedia of Parliaments and Legislatures
                (Washington, DC: Congressional Quarterly Inc., 1998).</p>
				
				<?php
					$db = new Db();
					$db->Query("SELECT id,title,content FROM glossary WHERE is_active=1 ORDER BY title");
					if($db->GetAffectedRows()>0) {
						while($data = $db->fetchAssoc()) {
							echo '<h3>'.$data['title'].'</h3>
							<p>'.strip_tags($data['content'], '<a>').'</p>';
						}
					} else {
						echo '<p>Nothing available.</p>';
					}
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
