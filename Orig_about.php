<?php
    include('includes/conf.php');
	$section = "about";
	$page = "overview";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - About Us: Overview</title>
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
				<?php include( "includes/nav-about.php" ); ?>
				<h2>About Us:</h2>
				
				<p>Election Guide is provided by IFES, an international nonprofit dedicated to the building of democratic societies.  Launched in 1998  through a generous grant from USAID, ElectionGuide  is the most timely source of verified election results available online.  Its results are also available on <a href="http://www.cnn.com/WORLD/election.watch/" target="_blank">CNN Election Watch</a>.</p>
				<p>ElectionGuide provides timely and accurate information on:</p>
				<ul>
					<li>National elections around the world, and other electoral events deemed of high interest</li>
					<li>Political parties and candidates</li>
					<li>Referenda provisions</li>
					<li>Breaking news on election-related laws and political developments around the world</li>
					<li>Governmental and electoral structures</li>
					<li>Election results and voter turnout</li>
				</ul>
				<p>IFES' F. Clifton White Applied  Resource Center on Democracy and Elections (ARC) houses the Election Guide research team, who are responsible for gathering the data, analyzing the results, writing reports and maintaining the consistency and accuracy of ElectionGuide.</p>
				
				<h3>About ARC</h3>
				<p>IFES formed the F. Clifton White Applied Research Center on Democracy and Elections  (ARC) to concentrate its research talent and capacity. ARC generates innovative applied research to enhance the practice of democracy-building and to bolster the development of democratic and participatory institutions, processes and culture. Through its research and outreach activities, ARC seeks to bridge the gap between theories of democratic development and practical realities of democracy-building in order to enrich democracy programming.</p>
				
				<h3>About IFES</h3>
				<p>As one of the world's premier democracy and governance assistance organizations, IFES provides targeted technical assistance to strengthen transitional democracies. Founded in 1987 as a nonpartisan, nonprofit organization, IFES has developed and implemented comprehensive, collaborative democracy solutions in more than 100 countries.</p>
				
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
