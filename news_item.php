<?php
	include( "includes/conf.php" );
	
	require_once( "admin/includes/News.Class.php" );
	$id = $_REQUEST['id'];
	if (!$id){
		header("Location:news.php");
		exit;
	}

	$news = new News($id);
	$news->get_info();

	if (!$news->is_active){
		header("Location:news.php");
		exit;
	}

	if (!$news->has_fulltext())
	{
		if ($news->news_link){
			header("Location:".$news->news_link);
			exit;
		} else {
			header("Location:news.php");
			exit;
		}
	}

	$section = "utilities";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Election News</title>
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
			<?php /*<div class="ad banner"><a href="http://www.smith-ouzman.co.uk/" target="_blank"><img src="images/ads/rotate/smith.gif" alt="Advertisement" /> </a></div> */ ?>
			<!-- AD ends here-->
			
				<h2><?php echo $news->news_title; ?></h2>
				<?php echo $news->news_date; ?><br/><br/>
				
				<?php echo $news->news_fulltext; ?>
			

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
