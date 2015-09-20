<?php
    include('includes/conf.php');
	$section = "search";
	$page = "advanced";
	
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
	sess::set('winner', ($_REQUEST['winner']!='' ? $_REQUEST['winner'] : 0));

	sess::set('keyword', ($_REQUEST['keyword']!='' ? $_REQUEST['keyword'] : ''));
	sess::set('party_leader', ($_REQUEST['party_leader']!='' ? $_REQUEST['party_leader'] : ''));
	sess::set('cs_keyword', ($_REQUEST['cs_keyword']!='' ? $_REQUEST['cs_keyword'] : ''));
	sess::set('hg_keyword', ($_REQUEST['hg_keyword']!='' ? $_REQUEST['hg_keyword'] : ''));
	sess::set('a_keyword', ($_REQUEST['a_keyword']!='' ? $_REQUEST['a_keyword'] : ''));
	
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Advanced Search</title>
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
    
    function choose_ks(type) {
        var etype = type;

        var target = document.getElementById("kwlabel");
        var extra = document.getElementById("extrakw");
        var winnerlabel = document.getElementById("winnerlabel");

        
        target.innerHTML = 'Candidate keyword:';
        extra.style.display = 'none';
        clear_keyword();
        
        if (etype>0)
            show_keyword();
            
        if(etype==1) {
            target.innerHTML = 'Candidate keyword:';
            winnerlabel.innerHTML = 'Winners only';
            extra.style.display = 'none';
        } else if (etype==2) {
            target.innerHTML = 'Political Party keyword:';
            winnerlabel.innerHTML = 'Winners only';
            extra.style.display = 'block';
        } else if (etype==3) {
            target.innerHTML = 'Political Party keyword:';
            winnerlabel.innerHTML = 'Winners only';
            extra.style.display = 'block';
        } else if (etype==4) {
            target.innerHTML = 'Referenda keyword:';
            winnerlabel.innerHTML = 'Approved only';
            extra.style.display = 'none';
        } else {
            target.innerHTML = 'Candidate keyword:';
            winnerlabel.innerHTML = 'Winners only';
            extra.style.display = 'none';
            document.getElementById("keyword").value='';
            document.getElementById("party_leader").value='';
            document.getElementById("winner").value='';
            clear_keyword();
        }
    }
    
    function show_keyword() {
        var extra = document.getElementById("keywordkw");
        extra.style.display = 'block';
    }
    
    function clear_keyword() {
        var extra = document.getElementById("keywordkw");
        extra.style.display = 'none';
    }
    //-->
    </script>
</head>
<?php
    $onload = 'choose_ks('.sess::get('type').');';
    if(sess::get('region')>0) {
        $onload .= 'choose_countries('.sess::get('region').');';
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
			<h2>Advanced Search:</h2>
			<p>If you seek elections which involved specific people, issues, or parties, use the Advanced search filters below. Your results will display in a list of
                election profile links. As always, you may click the country name to go directly
                to the country profile page.  For multiple selections hold down the control key
                and click the mouse to highlight each of your choices. The more criteria you
                choose, the narrower your search will be  for more results, reduce the number
                of filters used.</p>
				
    <form action="advanced-search.php" method="get">
	<table id="wide-search-table"><tr>
    <td valign="top">
        <label for="region">By Region:</label><br />
            <?php

                    $regs = Common::get_regions(FALSE);
                    $regionsfinal = array(''=>'Any');
                    foreach($regs as $key=>$value)
                        $regionsfinal[$key] = $value;
                    echo Common::select_item("region", $regionsfinal, sess::get('region'), ' onchange="choose_countries(this.value);" style="width:200px;"');

            ?><br /><br />
            <label for="country">By Country:</label><br />
            <div id="countries"><?php
                include('includes/search_countries.php');
                
      		$countriesfinal = array(''=>'Any');
            foreach($countries as $key=>$value)
      		    $countriesfinal[$key] = $value;
                  echo Common::select_item("country", $countriesfinal, sess::get('country'), ' style="width:200px;"');
                
                ?><br /><br />
            </div>
            <label for="type">By Election Type:</label><br /><?php
            $types = Common::get_election_types(TRUE);
            foreach($types as $k => $v) {
                $chk = ($k==sess::get('type')) ? ' checked="checked"': '';
                echo '<input type="radio" value="'.$k.'" name="type" onclick="choose_ks(this.value);"'.$chk.' /><span class="etype"> '.$v.'</span><br />';
            }
            ?><br />
            <label for="round_num">By Election Round/Stage:</label><br />
            <?php
                $nums[0] = 'N/A';
                foreach(range(1, 10) as $value)
                    $nums[$value] = 'Round '.$value;
            echo Common::select_item("round_num", $nums, '', ' style="width:200px;"');
            ?><br /><br />
            <label>By Date Range From: </label><br />
            <?php 
                $years= range(1998, date('Y')+5);
                echo Common::select_item('start_month', Common::get_double_months(), sess::get('start_month'), ' style="width:100px;"').'
                '.Common::select_item_simple('start_year', $years, sess::get('start_year'), ' style="width:100px;"'); ?><br />
            <label>To: </label><br /><?php
                echo Common::select_item('end_month', Common::get_double_months(), sess::get('end_month'), ' style="width:100px;"').'
                '.Common::select_item_simple('end_year', $years, sess::get('end_year'), ' style="width:100px;"'); ?><br /><br />
            <input type="hidden" name="submitted" value="1" />
            <input type="image" src="images/button-search.gif" name="submit" id="submit-search" value="Search" alt="Search" />
  </td>
  <td valign="top" style="padding-left:10px">
  		<div id="keywordkw">
			<label for="keyword"><span id="kwlabel">Candidate keyword: </span></label><br />
			<input type="text" name="keyword" id="keyword" style="width:200px;" value="<?php echo sess::get('keyword'); ?>" />
			<?php $chk = (sess::get('winner')==1) ? ' checked="checked"': ''; ?>
			<input type="checkbox" value="1" name="winner" id="winner"<?php echo $chk; ?> /> <label for="winner" id="winnerlabel">Winners only</label><br /><br />
		</div>
		<div id="extrakw">
			<label for="party_leader">Party Leader keyword: </label><br />
			<input type="text" name="party_leader" id="party_leader" style="width:200px;" value="<?php echo sess::get('party_leader'); ?>" /><br /><br />
		</div>
        <div>
			<label for="cs_keyword"><span id="kwlabel">Chief of State keyword: </span></label><br />
            <input type="text" name="cs_keyword" id="cs_keyword" style="width:200px;" value="<?php echo sess::get('cs_keyword'); ?>" /><br /><br />
			<label for="hg_keyword"><span id="kwlabel">Head of Government keyword: </span></label><br />
            <input type="text" name="hg_keyword" id="hg_keyword" style="width:200px;" value="<?php echo sess::get('hg_keyword'); ?>" /><br /><br />
			<label for="a_keyword"><span id="kwlabel">Assembly keyword: </span></label><br />
            <input type="text" name="a_keyword" id="a_keyword" style="width:200px;" value="<?php echo sess::get('a_keyword'); ?>" />
		</div>

  </td>
  </tr>
  </table>
  </form>
<?php

if (isset($_GET['submitted']) && (trim($_GET['keyword'])!='' || trim($_GET['country'])!='' || trim($_GET['year'])!='any')) {
    $db = new Db();
    $sql = "SELECT elections.id,
        country.region,
        regions.region as region_name,
        elections.round_num,
        elections.country,
        round_texts.status_text AS round_num_text,
        IF(election_type=4,elections.election_summary,'') as election_summary,
        IF(election_type=4,elections.requirements,'') as requirements,
        elections.last_election_notes,
        CONCAT(elections.chief_fname, ' ', elections.chief_lname) as chief,
        CONCAT(elections.head_fname, ' ', elections.head_lname) as thehead,
        IF((country.show_link=1 && country.country_flag!=''),
            CONCAT('<a href=\"country.php?ID=', country.id, '\"><img src=\"images/flags/', country.country_flag, '\" style=\"width:50px\" /></a>'),
            IF (country.country_flag!='',
                CONCAT('<img src=\"images/flags/', country.country_flag, '\" style=\"width:50px\" />'),
            '')
        ) AS flag,
        IF(country.show_link=1,
            CONCAT('<a href=\"country.php?ID=', country.id, '\">', country.country_name, '</a>'),
            country.country_name
        ) AS thecountry,
        IF ((elections.is_active=4 OR elections.is_active=5),
            CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT('<br />', round_texts.status_text), ''), ' (',status_texts.status_text, ')'),
            IF(elections.is_active=2,
                CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT('<br />', round_texts.status_text), '')),
                CONCAT('<a href=\"election.php?ID=', elections.id, '\">', election_types.type_name, '</a>',
                    IF(elections.round_num>0, CONCAT('<br />', round_texts.status_text), ''))
            )
        ) as thetype,
        elections.assembly_type,
        elections.election_type,
        IF(elections.assembly_name='',
        CONCAT('<em>',elections.assembly_name_foreign, '</em>'), elections.assembly_name) as complete_assembly_name,
        IF(elections.uh_name='',CONCAT('<em>',elections.uh_name_foreign, '</em>'), elections.uh_name) as upper_house_name,
        IF(elections.lh_name='',CONCAT('<em>',elections.lh_name_foreign, '</em>'), elections.lh_name) as lower_house_name,
        CONCAT(candidates_pres.candidate_fname, ' ', candidates_pres.candidate_lname) as candidate_pres,
            candidates_ref.provision_at_stake as candidate_ref,
            candidates.party_name as candidate,
        CONCAT(candidates.party_leader_fname, ' ', candidates.party_leader_lname ) as party_leader_name,
        CONCAT(
            IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
            IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
            YEAR(election_date)) as thedate
        FROM elections, country, election_types
        LEFT JOIN candidates_pres ON elections.id=candidates_pres.election
        LEFT JOIN candidates_ref ON elections.id=candidates_ref.election
        LEFT JOIN candidates ON elections.id=candidates.election
        LEFT JOIN regions ON country.region=regions.id
        LEFT JOIN status_texts ON elections.is_active=status_texts.id
        LEFT JOIN round_texts ON elections.round_num=round_texts.id
            WHERE elections.country=country.id
            AND status_texts.id=elections.is_active
            AND elections.is_active=1
            AND election_types.id=elections.election_type";
            
        if (sess::get('keyword')!='') {
            if (sess::get('type')=='' || sess::get('type')==1)  {
                $sql .= " AND (candidates_pres.candidate_fname LIKE '%".sess::get('keyword')."%' OR
                candidates_pres.candidate_lname LIKE '%".sess::get('keyword')."%')";
            }
            
            if (sess::get('type')=='' || sess::get('type')==4) {
                $sql .= " AND (candidates_ref.provision_summary LIKE '%".sess::get('keyword')."%' OR
                candidates_ref.provision_desc LIKE '%".sess::get('keyword')."%' OR
                candidates_ref.provision_desc_foreign LIKE '%".sess::get('keyword')."%' OR
                elections.election_summary LIKE '%".sess::get('keyword')."%')";
            }
            
            if (sess::get('type')=='' || sess::get('type')==2 || sess::get('type')==3) {
                $sql .= " AND (candidates.party_name LIKE '%".sess::get('keyword')."%' OR
                candidates.party_name_foreign LIKE '%".sess::get('keyword')."%' OR
                candidates.party_acronym LIKE '%".sess::get('keyword')."%') ";
            }
        }
        if (sess::get('party_leader')!='')
            $sql .= " AND (candidates.party_leader_fname LIKE '%".sess::get('party_leader')."%' OR
            candidates.party_leader_lname LIKE '%".sess::get('party_leader')."%')";
            
        if (sess::get('winner')!='') {
             if (sess::get('type')=='' || sess::get('type')==1)
               $sql .= " AND candidates_pres.is_winner=1";

             if (sess::get('type')=='' || sess::get('type')==4)
                $sql .= " AND candidates_ref.is_winner=1";
                
             if (sess::get('type')=='' || sess::get('type')==2 || sess::get('type')==3)
                $sql .= " AND candidates.is_winner=1";
        }
        
        if (sess::get('type')!='')
            $sql .= " AND elections.election_type=".sess::get('type');

        if (sess::get('cs_keyword')!='') {
            $sql .= " AND (
                elections.chief_fname LIKE '%".sess::get('cs_keyword')."%' OR
                elections.chief_lname LIKE '%".sess::get('cs_keyword')."%'
            ) ";
        }
        
        if (sess::get('hg_keyword')!='') {
            $sql .= " AND (
                elections.head_fname LIKE '%".sess::get('hg_keyword')."%' OR
                elections.head_lname LIKE '%".sess::get('hg_keyword')."%'
            ) ";
        }
        
        if (sess::get('a_keyword')!='') {
            $sql .= " AND ( elections.assembly_name LIKE '%".sess::get('a_keyword')."%'
            OR elections.assembly_name_foreign LIKE '%".sess::get('a_keyword')."%'
            OR elections.uh_name LIKE '%".sess::get('a_keyword')."%'
            OR elections.uh_name_foreign LIKE '%".sess::get('a_keyword')."%'
            OR elections.lh_name LIKE '%".sess::get('a_keyword')."%'
            OR elections.lh_name_foreign LIKE '%".sess::get('a_keyword')."%' )";
        }
        
        if (sess::get('round_num')>0)
            $sql .= " AND elections.round_num=".sess::get('round_num');
            
        if (sess::get('region')!='')
            $sql .= " AND country.region=".sess::get('region');
            
        if (sess::get('country')!='')
            $sql .= " AND country.id=".sess::get('country');
        
        if ($start_year!=0 && $start_month!=0)
            $sql .= " AND (election_date>='".$start_year."-".$start_month."-01')";
        else if ($start_year!=0)
            $sql .= " AND YEAR(election_date)>='".$start_year."'";
            
        if ($end_year!=0 && $end_month!=0)
            $sql .= " AND election_date<='".$end_year."-".$end_month."-31'";
        else if ($end_year!=0)
            $sql .= " AND YEAR(election_date)<='".$end_year."'";
  
        $sql .= " GROUP BY elections.id ORDER BY region_name ASC, country_name ASC, elections.order_date DESC";
        echo $sql;
        $db->Query($sql);
        $rows = $db->GetAffectedRows();
        if ($rows>0) {
            echo '<p>Found '.$rows.' results matching your search criteria.</p>';
            while($data = $db->fetchAssoc())
                $resarray[$data['election_type']][$data['id']] = $data;
            include('includes/new_search_fs.php');
            foreach($resarray as $thetype => $data) {
                //$cname = ($thetype==4) ? 'Referenda Issue': (($thetype==2 || $thetype==3) ? 'Party Name' :'Candidate');
                echo '<h2>'.Common::get_election_type($thetype).'</h2>';
                if($thetype==2 || $thetype==3)
                    echo legislative_results($data);
                else if ($thetype==1)
                    echo presidential_results($data);
                else if ($thetype==4)
                    echo referendum_results($data);
            }
        } else {
            echo '<p>Nothing found matching your search criteria.</p>';
        }
        //echo $sql;

} else if (isset($_GET['submitted'])) {
    echo '<p>Please enter a search term.</p>';
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
