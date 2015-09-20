<?php
    include('includes/conf.php');
	$section = "newsletter";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Newsletter: ElectList!</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/ifes.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="css/msgs.css" />
	<link rel="home" title="Home" href="http://www.electionguide.org/" />
</head>

<body id="body-<?php echo $section; ?>">

	<?php include( "includes/jump-links.php" ); ?>
	<div id="wrapper">
		<?php include( "includes/header.php" ); ?>
		<hr />
		<div id="main-wrapper">
			<div id="content-wrapper">
			
			
				<!-- Ads come here -->
		<img align="right" src="images/ads/samplead.jpg" />  
		
		
				<h2>ElectList!:</h2>
				<p>ElectList! is a weekly newsletter that brings you election-related news from around
                the world as well as the latest updates to ElectionGuide.  With the newsletter, you will
                receive information and links about upcoming election dates and the most recently posted
                election results.  We will also provide concise summaries of breaking news and important
                developments related to electoral processes and current elections throughout the world.</p>

				<p>To have all our breaking election-related news delivered to your Inbox each week,
                sign up for ElectList! today. <a href="signup.php">Subscribe now!</a></p>
                <p><a href="news-search.php">Search News Archives</a></p>

				<?php
				Message::show_messages();
				
				echo '<h3>Current ElectList News</h3><br />
                <div class="current-news">';
                $Db = new Db;
                $Db->Query("SELECT news_items.id,
                news_items.news_title,
                news_items.news_content,
                news_items.news_date,
                news_items.news_link,
                news_items.is_external,
                news_items.news_source,
                news_items.country,
                country.id as country_id,
                country.country_name
                FROM news_items, country
                WHERE news_items.electlist=1
                AND country.id=news_items.country
                ORDER BY news_items.news_date DESC LIMIT 0,5");

                while($data = $Db->fetchAssoc()) {
                    $newarray[$data['country']]['id'] = $data['country'];
                    $newarray[$data['country']]['name'] = $data['country_name'];
                    $newarray[$data['country']]['elements'][$data['id']] = $data;
                }

                foreach($newarray as $event=>$electiondata) {
                    foreach($electiondata['elements'] as $id => $data) {
                        echo '<p><strong><a href="country.php?ID='.$data['country_id'].'">'.$data['country_name'].'</a>';
                        //if ($data['news_date']!='')
                       //     echo ' ('.date('M d, Y', strtotime($data['news_date'])).')';
                        echo ': </strong> ';
                        $content = str_replace('<p>', '', $data['news_content']);
                        $content = str_replace('</p>', '', $content);
                        echo trim($content).'</p>';
                    }
                }
                ?>
				</div>
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
