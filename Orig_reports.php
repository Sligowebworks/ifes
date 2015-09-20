<?php
    include('includes/conf.php');
	$section = "search";
	$page = "reports";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Reporting</title>
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
			 <?php include( "includes/nav-search.php" ); ?>
				<h2>Reports:</h2>
				<p>Welcome to the Reports section of Election Guide. All reports focus on one
                main issue for analysis or comparison, and allow you to customize your inquiry.
                Each report is summarized below - the report pages describe the results set you will receive in detail.</p>
                
				<ul>
                    <li><a href="reports1.php">Voter Turnout</a> - Choose your date range, display and compare turnout statistics by country or region, and view average turnout by region.</li>
                    <li><a href="reports4.php">Average Voter Turnout</a></li>
                    <li><a href="reports2.php">Party Performance</a> - View seats won and lost over time for one political entity or compare several; search by country, region, political party, party leader, and date.</li>
                    <li><a href="reports3.php">Presidential Election Candidate Performance/ Winners</a> - View candidates, votes, and change in votes over time; search by country, region, candidate name, candidate party, and date.</li>
                    <li><a href="reports5.php">Government Structure: Assemblies</a> - View ways in which Assembly members obtain office; search by country, region, methods of election, appointment, etc. of offices, and date. This report allows you to compare the ways in which presidents.</li>
                    <li><a href="reports6.php">Government Structure: Chiefs of State</a> - View ways in which Chiefs of State obtain office; search by country, region, methods of election, appointment, etc. of offices, and date.</li>
                </ul>
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
