<?php
    include('includes/conf.php');
	$section = "links";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Links</title>
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
			<div class="ad banner"><a href="http://www.smith-ouzman.co.uk/" target="_blank"><img src="images/ads/rotate/smith.gif" alt="Advertisement" /> </a></div>
			<!-- AD ends here-->
			
			
				<h2>Links:</h2>
				
				<h3>IFES Links</h3>
				<ul>
					<li><a href="http://www.ifes.org/" target="_blank">IFES</a></li>
					<li><a href="http://www.democracyatlarge.org/" target="_blank">Democracy At Large</a> (quarterly magazine)</li>
                    <li><a href="http://www.ifesbuyersguide.com" target="_blank">IFES Buyer's Guide</a> (industry-specific directory offering information about companies selling voting equipment and electoral supplies for use in public or private elections)</li>
					<li><a href="http://www.aceproject.org/" target="_blank">ACE Project</a></li>
				</ul>
				
				
				<h3>Election Links</h3>
				<ul>
					<li><a href="http://africanelections.tripod.com/index.html" target="_blank">African Elections Database</a></li>
					<li><a href="http://www.angus-reid.com/" target="_blank">Angus Reid Consultants</a></li>
					<li><a href="http://electionresources.org/" target="_blank">Election Resources on the Internet</a></li>
					<li><a href="http://www.electionworld.org/" target="_blank">Elections around the World</a></li>
					<li><a href="http://www.angelfire.com/ma/maxcrc/elections.html" target="_blank">Maximiliano Herrera Human Rights Links</a></li>
					<li><a href="http://psephos.adam-carr.net/" target="_blank">Adam Carr's Election Archive</a></li>
					<li><a href="http://www.cfr.org/issue/84/elections.html" target="_blank">Council on Foreign Relations</a></li>
				</ul>
								
				
				<h3>Other Organizations</h3>
				<ul>
					<li><a href="http://www.ndi.org/" target="_blank">National Democratic Institute for International Affairs (NDI)</a></li>
					<li><a href="http://www.iri.org/" target="_blank">International Republican Institute (IRI)</a></li>
					<li><a href="http://www.usaid.gov/" target="_blank">US Agency for International Development (USAID)</a></li>
					<li><a href="http://www.osce.org/odihr/" target="_blank">OSCE Office for Democratic Institutions and Human Rights - Elections</a></li>
				</ul>
				
				
				<h3>News Links</h3>
				<ul>
					<li><a href="http://www.allafrica.com/" target="_blank">AllAfrica.com</a></li>
					<li><a href="http://news.bbc.co.uk/" target="_blank">BBC News</a></li>
					<li><a href="http://edition.cnn.com/WORLD/" target="_blank">CNN International </a></li>
					<li><a href="http://www.irinnews.org/" target="_blank"> United Nations Integrated Regional Information Networks (IRIN) </a></li>
					<li><a href="http://today.reuters.com/news/home.aspx" target="_blank"> Reuters </a></li>
					<li><a href="http://www.rferl.org/" target="_blank"> Radio Free Europe Radio Liberty </a></li>
					<li><a href="http://www.balkantimes.com/ " target="_blank"> Southeast European Times </a></li>
					
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
