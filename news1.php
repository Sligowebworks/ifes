<?php
	include( "includes/conf.php" );
	$section = "utilities";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Election News</title>
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
			
			<!-- Ads come here -->
		<img align="right" src="images/ads/samplead.jpg" />
		
				<h2>Election Updates & News:</h2>
				
				
				<?php
				//$NewEnc = new ConvertCharset;
                //$str= $NewEnc->Convert(file_get_contents('rss/news_full.xml'), 'utf-8' , 'iso-8859-1');
                $str = file_get_contents('rss/news_full.xml');
                require_once('XML/Unserializer.php');
                $options = array("complexType" => "array", "parseAttributes"=>"TRUE");
                $unser = &new XML_Unserializer($options);
                $status = $unser->unserialize($str, FALSE);

                if (PEAR::isError($status)) {
                    //exit();
                } else {
                    $data = $unser->getUnserializedData();
                }
                $values = $data['item'];
                $ctr = (count($values)>10) ? 9: count($values);
                for($x=0;$x<$ctr;$x++) {
                    echo '<a name="anchor_'.$values[$x]['id'].'"></a><h3>'.html_entity_decode(ucwords($values[$x]['title'])).'</h3>
                    <p style="margin-bottom:0;">Posted: '.$values[$x]['news_date'].' <br />
                    '.$values[$x]['description'].'</p>
                    <p style="padding:0;margin:0;">';
                    echo '</p><p style="padding:0;margin:0;">';
                    if ($values[$x]['extid']!=0)
                        echo '<a href="'.$values[$x]['link'].'" target="_blank">Read full story</a>.';
                    else
                        echo '<a href="newsletter.php">Read more ElectionGuide News</a>.';
                    echo ' Source: '.$values[$x]['news_source'].'</p>';

                }
				?>
				<p style="text-align:right">
                <link rel="alternate" type="application/rss+xml" title="Election Guide News" href="rss/news.xml" />
				<a href="what-is-rss.php" onclick="window.open( this.href, 'what_is_rss', 'width=400,height=400,scrollbars=yes,resizable=no,status=no' ); return false;">What is RSS?</a>
                <a href="rss/news.xml" title="Election Guide News"  target="_blank"><img src="images/rss.gif" alt="" style="vertical-align: text-bottom;" /></a>
				</p>
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
