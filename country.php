<?php
    include('includes/conf.php');
	$section = "country";
	$page = "overview";
	$id = $_REQUEST['ID'];
	if ($id=='' || $id==0) {
        ob_end_clean();
        header('location: calendar.php');
        exit();
    }
    
	$db = new Db();
	$sfsql =& new SafeSQL_MySQL();
	$db->Query($sfsql->SafeCompose("SELECT * FROM country WHERE show_link=1 AND id=%s", $id));
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
	<title>IFES Election Guide - Country Profile: <?php echo $data['country_name']; ?></title>
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
				<?php include( "includes/nav-country.php" ); ?>
				<h2>Country Profile:</h2>
				<?php
				require_once('includes/ElectionList.Class.php');
                ElectionList::show_upcoming_for_country($id);
                ?>
				<table id="country-meta"><tbody>
				<tr>
					<?php
                    if (file_exists('images/flags/'.$data['country_flag']) && is_file('images/flags/'.$data['country_flag'])) {
                        echo '<td>
                            <img src="images/flags/'.$data['country_flag'].'" alt="'.$data['country_name'].'" style="width:100px;border:1px solid silver;" />
                        </td>';
                    }
                    ?>
					<td>
						<ul>
							<li><?php echo $data['country_name']; ?></li>
							<li><?php echo $data['official_name']; ?></li>
							<li>Region: <a href="region.php?ID=<?php echo $data['region']; ?>">
							    <?php echo Common::get_region_name($data['region']); ?></a>
							</li>
						</ul>
					</td>
				</tr>
				</tbody></table>
				<?php
				
                require_once("admin/includes/ElectionExport.Class.php");
				require_once("admin/includes/CountryExport.Class.php");
                require_once("admin/includes/ElectionViewNew.Class.php");
				require_once("admin/includes/CountryViewNew.Class.php");
                $grp = new CountryView($id);
                $grp->webview();
                
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
