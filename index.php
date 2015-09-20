<?php
/**/
	include('includes/conf.php');
	$section = "home";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide</title>
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
<!--	<div class="ad banner"><a href="http://www.codeinc.com/" target="_blank"><img src="images/ads/CODE-ElectionGuide-home-pg.gif" alt="Advertisement" /></a></div>-->
<!--<p>ElectionGuide wants to know what you think. Please help us become an even better election resource by taking this
<a href="http://www.surveymonkey.com/s.aspx?sm=JDpWVwOdIU5GBP_2fxcdtYpg_3d_3d" target="_blank">short survey</a>.
</p> -->
	  <h2>ElectionGuide – Upcoming Elections</h2> 
				
				</p>
				<p> Browse by region or click on dots to see 10 next elections. </p>
				<p style="text-align: center;"><a name="themap"></a>
                <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="454" height="335" id="map1" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="map.swf" />
<param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />
<embed src="map.swf" quality="high" bgcolor="#ffffff" width="454" height="335" name="map1" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object></p>
				
				<div id="copyright">  © 2009 ElectionGuide. A publication of IFES' <a href="http://www.ifes.org/arc.html" target="_blank" style="color:black">F. Clifton White Applied Research Center for Democracy and Elections</a>. All Rights Reserved. </div>
		
        <!--<div class="ad banner"><a href="http://lantrade.com" target="_blank"><img src="images/ads/LGS_IFESbanner.gif" alt="Advertisement" /></a></div> -->
        
        
       <?php include ("banner-rotator.php"); ?>

        
        	
			<!--<div class="ad banner"><a href="http://www.codeinc.com/" target="_blank"><img src="images/ads/CODE-ElectionGuide-home-pg.gif" alt="Advertisement" /></a></div> --> 


<!--Example of Javascript for image rotator				
	var rotator1 = {
    path: "images/",  // path to your images
    id: "r1",   // id assigned in image tag
    speed:  4500, // rate of rotation
    bTrans: false, // transition filter for IE Win
    images: ["smile.gif", "grim.gif", "frown.gif", "bomb.gif"]
} -->
			
				
			
			
			</div>
				
			
	    <hr />
			<div id="sidebar-wrapper">
				<?php
					include( "includes/sidebar-search.php" );
					include( "includes/sidebar-news.php" );
					//include( "includes/sidebar-ads.php" );
				?>
			</div>
		</div>
		<hr id="clear-hack" />
		<?php include( "includes/footer.php" ); ?>
	</div>

</body>
</html>
