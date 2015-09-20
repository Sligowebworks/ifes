<?php
    include('includes/conf.php');
	$section = "calendar";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Upcoming Elections</title>
	<link href="http://www.ifes.org/css/global_styles.css" rel="stylesheet" type="text/css" />
	<link rel="home" title="Home" href="http://www.electionguide.org/" />
	<style type='text/css'>

	<!--

			.ue_country {
					text-align: left;
					font-weight: bold;
					margin: 0px 0px 0px 0px;
					}
			.ue_election {
					text-align: left;
					font-weight: bold;
					margin: 0px 0px 0px 3px;
					}
			.ue_date {
					text-align: left;
					margin: 0px 0px 5px 3px;
					}		

	-->

	</style>
	
	
	
	<script type="text/javascript" src="scripts/striper.js"></script>
	<script type="text/javascript">window.onload = function() { stripe( "election-calendar", "#dde5f0", "#efefe7" ); inputDBValues(); }</script>
	<script language='JavaScript'>
 
	<!--
		/*
		The php in the body creates a table from the database
		The javascript in in the head reformats this table into a usable form
		This is a bit of a rounudabout way of doing things, but at the time, it was easiest and quickest  
		
		*/

  
		function inputDBValues(){
			var thisTable = document.getElementById("election-calendar");
			for (var i=0; i < 3; i++){
			
				document.getElementById('country' + i).innerHTML = thisTable.tBodies[0].rows[i].cells[1].innerHTML;
				document.getElementById('election' + i).innerHTML = "("+thisTable.tBodies[0].rows[i].cells[2].innerHTML+")";
				document.getElementById('date' + i).innerHTML = thisTable.tBodies[0].rows[i].cells[0].innerHTML;
				}
			thisTable.style.display = "none";
		}

		
	//-->

	</script>
</head>

<body id="body-<?php echo $section; ?>">

			<?php
                include_once('admin/includes/Dbtable.Class.php');
        		include_once('admin/includes/Common.Dbtable.Class.php');
        		include_once('includes/electionlistnew.Class.php');
				ElectionList::show_upcoming_elections();
                ?>

	<div class="callout">
    	<div class="calloutTop" style="height: 12px;">
    		<div></div>
    	</div>
		<p></p>
		
		<div class="calloutBody" ><span></span>
			<h3 style="white-space: nowrap; text-align: left;">Upcoming Elections</h3>
			<div id="electionList" style="text-align: left;">
				<div id="country0" class="ue_country"></div><div id="election0" class="ue_election"></div><div id="date0" class="ue_date"></div>
				<div id="country1" class="ue_country"></div><div id="election1" class="ue_election"></div><div id="date1" class="ue_date"></div>
				<div id="country2" class="ue_country"></div><div id="election2" class="ue_election"></div><div id="date2" class="ue_date"></div>
	    
		  		<br /> 
	      		<p><a href="http://www.electionguide.org" target="_blank">Visit ElectionGuide.org&nbsp;&raquo;</a></p>
	    	</div>
		 
		</div>
		
		<div class="calloutBottom" style="height: 12px;">
    		<div></div>
      	</div>
  		
	</div>


</body>
</html>
