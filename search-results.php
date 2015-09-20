<?php
    include('includes/conf.php');
	$section = "search";
	$page = "overview";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
	
	sess::set('search_year', (isset($_REQUEST['search_year']) ? $_REQUEST['search_year'] : sess::get('search_year')));
	sess::set('country', (isset($_REQUEST['country']) ? $_REQUEST['country'] : sess::get('country')));
	sess::set('type', (isset($_REQUEST['type']) ? $_REQUEST['type'] : sess::get('type')));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Quick Search Results</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/ifes.css" />
	<link rel="home" title="Home" href="http://www.electionguide.org/" />
	<script type="text/javascript" src="scripts/striper.js"></script>
	<script type="text/javascript">
    if (document.getElementById("election-calendar")!=null) {
        window.onload = function() {
            stripe( "election-calendar", "#dde5f0", "#efefe7" );
        }
    }
    </script>
</head>

<body id="body-<?php echo $section; ?>">

	<?php include( "includes/jump-links.php" ); ?>
	<div id="wrapper">
		<?php include( "includes/header.php" ); ?>
		<hr />
		<div id="main-wrapper">
			<div id="content-wrapper">
			    <?php include( "includes/nav-search.php" ); ?>
				<h2>Search Results:</h2>
				<p>Explore our Search and Reporting features here.</p>
				
				<ul> <li>Please use the Quick Search console on the left to search quickly for election
                profiles. </br></li>

                <li>For more options and filters, please go to the <a href="advanced-search.php" >Advanced Search</a>
                page to seek elections that fit your criteria.  </br></li>
				
				<li>To generate comparative charts and reports, please go to the <a href="reports.php" >Reports</a> page. </br></li></ul>
				<hr />
				
				<?php
                include_once('admin/includes/Dbtable.Class.php');
        		include_once('admin/includes/Common.Dbtable.Class.php');
        		include_once('includes/ElectionList.Class.php');
        		if ($_REQUEST['submitted']==1)
                    ElectionList::show_search_results();
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
