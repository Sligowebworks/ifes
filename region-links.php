<?php
    include('includes/conf.php');
	$section = "country";
	$page = "links";
	$id = $_REQUEST['ID'];
	if ($id=='') {
        ob_end_clean();
        header('location: calendar.php');
        exit();
    }
	$db = new Db();
	$db->Query("SELECT * FROM regions WHERE id=".$id);
	if ($db->GetAffectedRows()==1)
	    $data = $db->fetchAssoc();
 	else {
	    ob_end_clean();
	    header('location: calendar.php');
	    exit();
	}
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Region Profile: <?php echo $data['region']; ?> - Links</title>
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
				<?php include( "includes/nav-region.php" ); ?>
				<h2>Region Profile:</h2>
				<h3>Links for <?php echo $data['region']; ?></h3>
				<?php
				include('admin/includes/cfg_link_types.php');
        		$db = new Db();
        		$db->Query("SELECT link_name, link_url, link_type 
        		FROM country_links 
        		WHERE region=".$id." AND is_active=1 ORDER BY order_id");
        		if ($db->GetAffectedRows()>0) {
        		    while($data = $db->fetchAssoc()) {
        		        $type = explode(',', $data['link_type']);
        		        foreach($type as $val)
        		            $link_arr[$val][] = $data;
        		        //print_r($data);
        		    }
            		foreach($link_arr as $category=>$values) {
            		    echo '<h3>'.html_entity_decode($link_types[$category]).'</h3><ul>';
            		    foreach($values as $vals)
            		        echo '<li><a href="'.$vals['link_url'].'" target="_blank">'.html_entity_decode($vals['link_name']).'</a></li>';
            		    echo "</ul>";
            		}
        		} else {
        		    echo '<p>Nothing Available.</p>';
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
