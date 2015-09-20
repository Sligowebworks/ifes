<?php
    include('includes/conf.php');
	$section = "country";
	$page = "news";
	$id = $_REQUEST['ID'];
	if ($id=='') {
        ob_end_clean();
        header('location: calendar.php');
        exit();
    }
    
	$db = new Db();
	$db->Query("SELECT * FROM country WHERE id=".$id);
	if ($db->GetAffectedRows()==1)
	    $data = $db->fetchAssoc();
	else {
	    sess::set_error('This country does not exist.');
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
	<title>IFES Election Guide - Country Profile: <?php echo $data['country_name']; ?> - Elections</title>
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
				
				<a name="top"> </a>
				<h2>Country Profile:</h2>
								
					
			<?php
			
							
            $db = new Db();
            $db->Query("SELECT news_items.id, news_title, news_date, rss_title, 
				IF(ext_id>0, news_link, CONCAT('http://www.electionguide.org/country-news.php?ID=',country.id,'#anchor_', news_items.id)) as news_link,
               	IF(ext_id>0, external_news.rss_title, 'ElectionGuide') as news_source,
                news_content, ext_id
                FROM news_items
                LEFT JOIN country on country.id=news_items.country
                LEFT JOIN external_news ON external_news.id=news_items.ext_id
                WHERE news_items.is_active=1 AND news_items.country=".$id."
                ORDER BY news_items.news_date DESC ");
           	
			if ($db->GetAffectedRows()>0) {
				
				echo '<h3> ' .$data['country_name']. '  - News Archive </h3>';
   	        
			    while($data = $db->fetchAssoc()) {
              			   					   
					     $news_date = date('m/d/Y', strtotime($data['news_date'])); 
                           $cont = strip_tags($data['news_content']);
                           $title = html_entity_decode(strip_tags($data['news_title']));
					 													   
					    echo '<a name="anchor_'.$data['id'].'"></a>
								<h3>'.$data['news_title'].'</h3>
								<p style="margin-bottom:0;">Posted: '.$news_date.'<br />'.ucfirst($cont).'</p>
                                <p style="padding:0;margin:0;">';
                                
                                if ($data['ext_id']!=0) {
                                    echo '<a href="'.$data['news_link'].'" target="_blank">Read full story</a>.';
                                	echo ' Source: '.$data['rss_title'].'</p>'; }
								else 	{
							   		echo ' Source: ElectionGuide </p>';
                                   }
					    }
					echo '<br><a href="#top"> Go to top </a>';	
										
            }
			else echo '<h3>'.$data['country_name'].' - No news available. </h3>'; 	
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
