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
	sess::set('keyword', ($_REQUEST['keyword']!='' ? $_REQUEST['keyword'] : ''));
	sess::set('pkeyword', ($_REQUEST['pkeyword']!='' ? $_REQUEST['pkeyword'] : ''));
	sess::set('winner', ($_REQUEST['winner']!='' ? $_REQUEST['winner'] : ''));

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
<?php
    if(sess::get('region')>0) {
        $onload = 'choose_countries('.sess::get('region').');';
    } else {
        $onload .= '';
    }
?>
<body id="body-<?php echo $section; ?>" onload="<?php echo $onload; ?>">

	<?php include( "includes/jump-links.php" ); ?>
	<div id="wrapper">
		<?php include( "includes/header.php" ); ?>
		<hr />
		<div id="main-wrapper">
			<div id="wide-content-wrapper">
			<?php include( "includes/nav-search.php" ); ?>
			<h2>Party Performance</h2>
			
				<table id="wide-search-table">
				<tr>
				
			<td align="left" valign="top" width="40%">
			<p>This report lists parties and their performance in parliamentary and legislative elections over time. Select criteria below to view all parties within one country, or search by for a particular party. If you wish to compare related parties across countries, you must enter a party keyword. The second performance report below indicates which parties won the most number of seats in each election. Note that formation of governing coalitions etc. following an election are fluid events not captured in these reports, but could be detailed in news items. The 'winner' (the party that won the most seats across all seats at stake) is denoted by a small green arrow. Only Election Profiles with results posted will be returned in Reports.</p>

			</td>
<td align="left" valign="top" width="10%">
						</td>

				
				<td>
                <form action="reports2.php" method="get">
                <table id="wide-search-table"><tr>
                    <td><label for="region">By Region:</label><br />
                    <?php

                    $regs = Common::get_regions(FALSE);
                    $regionsfinal = array(''=>'Any');
                    foreach($regs as $key=>$value)
                        $regionsfinal[$key] = $value;
                    echo Common::select_item("region", $regionsfinal, sess::get('region'), ' onchange="choose_countries(this.value);" style="width:200px;"');
                    ?>
                </tr>
                <tr>
                    <td><label for="country">By Country:</label><br />
                    <div id="countries"><?php
                    include('includes/search_countries.php');
              		$countriesfinal = array(''=>'Any');
                    foreach($countries as $key=>$value)
          		        $countriesfinal[$key] = $value;
                    echo Common::select_item("country", $countriesfinal, sess::get('country'), ' style="width:200px;"');
                    ?>
                    </div></td>
                </tr>
                <tr>
                    <td><label for="type">By Election Type:</label><br /><?php
                    $types = array(''=>'Any',2=>'Parliamentary', 3=>'Legislative');
                    foreach($types as $k => $v) {
                        $chk = ($k==sess::get('type')) ? ' checked="checked"': '';
                        echo '<input type="radio" value="'.$k.'" name="type"'.$chk.' /><span class="etype"> '.$v.'</span><br />';
                    }
                    ?></td>
                </tr>
                <tr>
                    <td><label for="round_num">By Election Round/Stage:</label><br />
                    <?php
                    $nums[0] = 'N/A';
                    foreach(range(1, 10) as $value)
                        $nums[$value] = 'Round '.$value;
                    echo Common::select_item("round_num", $nums, sess::get('round_num'), ' style="width:200px;"');
                    ?></td>
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
                    <td><label for="pkeyword">Party Leader Keyword: </label><br />
                        <input type="text" name="pkeyword" id="pkeyword" style="width:200px;" value="<?php echo sess::get('pkeyword'); ?>" />
                    </td>
                </tr>
                <tr>
                    <td><label for="keyword">Party Keyword: </label><br />
                        <input type="text" name="keyword" id="keyword" style="width:200px;" value="<?php echo sess::get('keyword'); ?>" />
                        <label for="winner"></label>
                        <?php $chk = (sess::get('winner')==1) ? ' checked="checked"': ''; ?>
                        <input type="checkbox" value="1" name="winner" id="winner"<?php echo $chk; ?> /> Winners only</td>
                </tr>
                <tr>
                    <td><input type="hidden" name="submitted" value="1" />
                    <input type="image" src="images/button-search.gif" name="submit" id="submit-search" value="Search" alt="Search" />
                	</td>
                </tr>
                </table>
            </form>
			
			</td>
			
			</tr>
			</table>
			
			
			
            <?php

            if (isset($_GET['submitted']) && (trim($_GET['region'])!='' || trim($_GET['country'])!='' || trim($_GET['keyword'])!='')) {
                include('includes/Report.Class.php');
                $rpt = new Report();
                $rpt->report_parties();
            } else  if (isset($_GET['submitted'])) {
                echo '<p>Keyword, region or country is required.</p>';
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
