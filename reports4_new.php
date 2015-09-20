<?php
    include('includes/conf.php');
	$section = "search";
	$page = "reports";
	
	$start_month = ($_REQUEST['start_month']!='') ? $_REQUEST['start_month']: '01';
	$start_year = ($_REQUEST['start_year']!='') ? $_REQUEST['start_year']: date('Y');
	$start_date = $start_year.'-'.$start_month;

	$end_month = ($_REQUEST['end_month']!='') ? $_REQUEST['end_month']: '12';
	$end_year = ($_REQUEST['end_year']!='') ? $_REQUEST['end_year']: date('Y');
	$end_date = $end_year.'-'.$end_month;

    sess::set('start_year', ($start_year!='0000-00' ? $start_year : '2005'));
    sess::set('end_year', ($end_year!='0000-00' ? $end_year : '2005'));
    sess::set('start_month', ($start_month!='0000-00' ? $start_month : '01'));
    sess::set('end_month', ($end_month!='00' ? $end_month : '12'));
    
    sess::set('start_date', ($start_date!='0000-00' ? $start_date : '2005-01'));
    sess::set('end_date', ($end_date!='0000-00' ? $end_date : '2005-12'));
    
	sess::set('region', ($_REQUEST['region']!='' ? $_REQUEST['region'] : ''));
	sess::set('country', ($_REQUEST['country']!='' ? $_REQUEST['country'] : ''));
	sess::set('type', ($_REQUEST['type']!='' ? $_REQUEST['type'] : ''));
	sess::set('round_num', ($_REQUEST['round_num']>0 ? $_REQUEST['round_num'] : 0));
	sess::set('country', ($_REQUEST['country']!='' ? $_REQUEST['country'] : ''));
	sess::set('type', ($_REQUEST['type']!='' ? $_REQUEST['type'] : ''));
	if (sess::get('region')<0 || sess::get('region')>5)
        sess::set('region', 1);

echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Reporting: Voter Turnout</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/ifes.css" />
	<link rel="home" title="Home" href="http://www.electionguide.org/" />
<script language="javascript" type="text/javascript">
    <!--
    function choose_countries(region){
        if(region!=0){
            var doc = null;
            if (typeof window.ActiveXObject != 'undefined' ){
                doc = new ActiveXObject("Microsoft.XMLHTTP");
            } else {
                doc = new XMLHttpRequest();
            }

    		if (doc){
                doc.open("GET", "get_countries.php?ID="+region, false);
                doc.send(null);
                var dest = document.getElementById("countries");
                dest.innerHTML = doc.responseText;
    		}
        }
    }
    //-->
    </script>
</head>

<body id="body-<?php echo $section; ?>">

	<?php include( "includes/jump-links.php" ); ?>
	<div id="wrapper">
		<?php include( "includes/header.php" ); ?>
		<hr />
		<div id="main-wrapper">
			<div id="wide-content-wrapper">
			<?php include( "includes/nav-search.php" ); ?>
			<h2>Average Voter Turnout</h2>
			
			<table id="wide-search-table">
			<tr>
			
			<td align="left" valign="top" width="40%">
			<p>Choose a region and date range below to view average turnout by region in each election type.  
			Only elections for which voter turnout data is available are used to calculate the average; thus in the Number of Elections column, 
			you see not only the number of elections included in the calculation but also, in parentheses, the number of elections actually held.  </p>
			</td>
			
			<td align="left" valign="top" width="10%">
						</td>
			
			<td>
			
			
			

                <form action="reports4.php" method="get">
                <table id="wide-search-table"><tr>
                    <td><label for="region">By Region:</label><br />
                    <?php
                    echo Common::select_item("region", Common::get_regions(FALSE), $_REQUEST['region'], ' style="width:200px;"');
                    ?>
                </tr>
                <tr>
                    <td><label>By Date Range From: </label><br />
                    <?php
                        $years= range(1998, date('Y')+5);
                        echo Common::select_item('start_month', Common::get_double_months(), sess::get('start_month'), ' style="width:100px;"').'
                        '.Common::select_item_simple('start_year', $years, sess::get('start_year'), ' style="width:100px;"'); ?>
                    </td>
                </tr>
                <tr>
                    <td><label>To: </label><br /><?php
                        echo Common::select_item('end_month', Common::get_double_months(), sess::get('end_month'), ' style="width:100px;"').'
                        '.Common::select_item_simple('end_year', $years, sess::get('end_year'), ' style="width:100px;"'); ?>
                	</td>
                </tr>
                <tr>
                    <td><input type="hidden" name="submitted" value="1" />
                    <input type="image" src="images/button-search.gif" name="submit" id="submit-search" value="Search" alt="Search" />
                	</td>
                </tr>
                </table>
            </form>
			
			</td>
			</tr></table>
			
            <br />
            <?php
            if (isset($_GET['submitted'])) {
                include('includes/Report.Class.php');
                $rpt = new Report();
                $rpt->report_average_turnout();
            } 
            ?>
			</div>
			<hr />
		</div>
		<hr id="clear-hack" />
		<?php include( "includes/footer.php" ); ?>
	</div>
</body>
</html>
