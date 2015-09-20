<?php
    include('includes/conf.php');
	$section = "about";
	$page = "methodology";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - About Us: Methodology</title>
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
				
				<p>The ElectionGuide team conducts research and updates the site daily with new information on elections and international news.  Our methodology relies on quality research and data collection, objective analysis, and strict verification procedures.</p>
				
				<h3>Identifying Election Dates</h3>
				<p>New ElectionGuide entries (election dates and types) are gathered from daily monitoring of news reports, from interaction with election administrators, and from discussions with IFES staff in the field.  Once IFES receives notice of a definite or potential election entry, IFES reaches out to the sources below to confirm the date.  In many cases official confirmation of the date may not be available until one or two months prior to the election.</p>
				
				<h3>Compiling Election Profiles</h3>
				<p>As each election date approaches, the ElectionGuide Researcher compiles an Election Profile. The Profile consists of the following elements:</p>
				<ul>
					<li>Election date and type</li>
					<li>Description of government structure</li>
					<li>Description of electoral system</li>
					<li>Candidates and/or political parties participating in the election, or Referenda provisions</li>
					<li>Outcomes of previous election(s)</li>
					<li>Population</li>
					<li>Registered voters and voter turnout</li>
					<li>Election results</li>
					<li>News and items of interest about the electoral/political context</li>
				</ul>
				
				<h3>Verifying Information</h3>
				<p><strong>To verify Dates</strong>, yet still create a comprehensive listing of upcoming elections well in advance of the polling date, IFES has employed the following series of sources with whom we consult for confirmation of election dates and/or of profile information:</p>
				<ul>
					<li>Country election authorities: the first and best source for confirming the election entry is the country election authority responsible for administering the electoral event.</li>
					<li>Other official government source: If the country election authority cannot be immediately reached due to language, time zone, or other barriers, an election date may be confirmed through verification from another government source, often located at country embassies in Washington, DC.</li>
					<li>News sources: In the event that the election date cannot be confirmed by either of the above sources, information from a credible newspaper or other news agency may be used to verify the election date.</li>
					<li>Other sources: If necessary, other sources can be used, including IFES field staff and non-governmental organizations (e.g. OSCE, UN).</li>
				</ul>
				<p>Any of the above four methods yields a sufficient confirmation in order to place the election entry on the calendar.  As the anticipated date nears, IFES attempts to substitute secondary sources with primary ones, until eventually the election authority is the source of record.</p>
				<p>In most cases, the complete election date (month, day, year) is noted on the calendar.  For those cases where the actual date has not yet been determined, the month and year is sufficient.  Every effort is made to provide the precise election date as soon as it can be confirmed using the method cited above.</p>
				<p><strong>To verify Election Event Profiles</strong>, ElectionGuide identifies, if possible, both a country expert and an official source to confirm the profile information.  If necessary, we may post with one of these sources.</p>
				<p><strong>To verify Election Results</strong>, ElectionGuide uses numbers only from an official source (that is, the country election authority, embassy, or other government-affiliated source), or, in rare cases, several media sources in which matching official results are quoted.  The ElectionGuide Researcher attempts to keep in close contact with the electoral commission or embassy in order to receive the confirmed results as soon as they are made available.</p>
				
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
