<?php
    include('includes/conf.php');
	$section = "country";
	$page = "overview";
	$id = $_REQUEST['ID'];
	if ($id==''|| $id==0) {
        ob_end_clean();
        header('location: calendar.php');
        exit();
    }
	$db = new Db();
	$db->Query("SELECT region, region_desc FROM regions WHERE id=".$id);
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
	<title>IFES Election Guide - Region Profile: <?php echo $data['region']; ?></title>
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
				
				<!-- Ads come here -->
		<img align="right" src="images/ads/samplead.jpg" />
				
				<h3 class="title"><?php echo $data['region']; ?></h3>
				<br />
				<?php
				sess::set('year', ($_REQUEST['year']!='' ? $_REQUEST['year'] : sess::get('year')));
                include_once('admin/includes/Dbtable.Class.php');
        		include_once('admin/includes/Common.Dbtable.Class.php');
        		include_once('includes/ElectionList.Class.php');

        		//ElectionList::show_dropdowns('region', $id);
                //ElectionList::show_them_by_region($id);
                //include_once('includes/region_'.$id.'.php');
                $sql = "SELECT id, country_name, show_link FROM country 
                WHERE is_active=1 AND region=".$id." ORDER BY country_name";
                $db = new Db();
                $db->Query($sql);
                $count = $db->GetAffectedRows();
                if ($count<=20) {
                    ElectionList::show_upcoming_for_region($id);
                }
                if ($count>0) {
                    $half = ceil($count*0.6);
                    echo '<table><tr><td valign="top" width="50%"><p>';
                    $ctr=0;
                    while($data = $db->fetchAssoc()) {
                        $ctr++;
                        echo ($data['show_link']==1) 
                            ? '<a href="country.php?ID='.$data['id'].'">'.$data['country_name'].'</a>' 
                            : $data['country_name'];
                        echo '<br />';
                        if ($count>20) {
                            if ($half==$ctr) {
                                echo '</p></td><td valign="top" width="50%">';
                                ElectionList::show_upcoming_for_region($id);
                                echo '<p><br clear="both" />';
                            }
                        }
                    }
                    echo '</p></td></tr></table>';
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
