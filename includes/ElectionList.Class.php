<?php

class ElectionList {
    function show_eguide_upcoming($id) {
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
		
		$sql = "SELECT elections.id, 
        elections.is_active, 
        elections.round_num,
        round_texts.status_text as round_text,
        election_types.type_name,
        IF ((elections.is_active=4 OR elections.is_active=5),
            CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT(' (', round_texts.status_text, ')'), ''), ' (',status_texts.status_text, ')'),
            IF(elections.is_active=2,
                CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT(' (', round_texts.status_text, ')'), '')),
                CONCAT('<a style=\"font-weight:bold;color:#003399;\" href=\"election.php?ID=', elections.id, '\">', election_types.type_name, '</a>',
                    IF(elections.round_num>0, CONCAT(' (', round_texts.status_text, ')'), ''))
            )
        ) as thetype,
        CONCAT(
            IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
            IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
            YEAR(election_date)) as election_date
        FROM elections, election_types
        LEFT JOIN round_texts ON elections.round_num=round_texts.id
        LEFT JOIN status_texts ON elections.is_active=status_texts.id ";
		$sql .= $sfsql->SafeCompose(
        " WHERE country=%i ", $id);
		$sql .=
        " AND election_types.id=elections.election_type
        AND election_date>='".date('Y-m-d')."' ORDER BY order_date DESC LIMIT 0,5";
		
		
        $db->Query($sql);
        if ($db->GetAffectedRows()>0) {
            echo '<h3>Future elections</h3><ul>';
            while($data = $db->fetchAssoc()) {
                echo '<li>'.$data['thetype'].' - '.$data['election_date'].'</li>';
            }
            echo '</ul>';
        }
    }
    
    function show_eguide_past($id) {
        $db = new Db();
		$sfsql =& new MySQL_SafeSQL();
		
        $sql = "SELECT elections.id,
        elections.is_active, 
        elections.round_num,
        round_texts.status_text as round_text,
        IF ((elections.is_active=4 OR elections.is_active=5),
            CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT(' (', round_texts.status_text, ')'), ''), ' (',status_texts.status_text, ')'),
            IF(elections.is_active=2,
                CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT(' (', round_texts.status_text, ')'), '')),
                CONCAT('<a style=\"font-weight:bold;color:#003399;\" href=\"election.php?ID=', elections.id, '\">', election_types.type_name, '</a>',
                    IF(elections.round_num>0, CONCAT(' (', round_texts.status_text, ')'), ''))
            )
        ) as thetype,
        CONCAT(
            IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
            IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
            YEAR(election_date)) as election_date
        FROM elections, election_types
        LEFT JOIN round_texts ON elections.round_num=round_texts.id
        LEFT JOIN status_texts ON elections.is_active=status_texts.id ";
		$sql .= $sfsql->SafeCompose(
        " WHERE country=%i ", $id);
		$sql .=
        " AND election_types.id=elections.election_type
        AND election_date<'".date('Y-m-d')."' ORDER BY elections.order_date DESC LIMIT 0,10";
		
		$db->Query($sql);
        if ($db->GetAffectedRows()>0) {
            echo '<h3>Past elections</h3><ul>';
            while($data = $db->fetchAssoc()) {
                echo '<li>'.$data['thetype'].' - '.$data['election_date'].'</li>';
            }
            echo '</ul>';
        }
    }
    
    function show_upcoming_for_region($id) {
            $db = new Db();
			$sfsql =& new SafeSQL_MySQL();
			
            $sql = "SELECT elections.id,
            election_type,
            elections.country,
            country.country_name,
            country.show_link,
            IF ((elections.is_active=4 OR elections.is_active=5),
                CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT('<br />', round_texts.status_text), ''), ' (',status_texts.status_text, ')'),
                IF(elections.is_active=2,
                    CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT('<br />', round_texts.status_text), '')),
                    CONCAT('<a href=\"election.php?ID=', elections.id, '\">', election_types.type_name, '</a>',
                        IF(elections.round_num>0, CONCAT('<br />', round_texts.status_text), ''))
                )
            ) as election_type_str,
            CONCAT(IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
                IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
                YEAR(election_date)) as thedate
            FROM elections, election_types
            LEFT JOIN country ON elections.country=country.id
            LEFT JOIN round_texts ON elections.round_num=round_texts.id
            LEFT JOIN status_texts ON elections.is_active=status_texts.id
            WHERE elections.is_active!=3
            AND election_types.id=elections.election_type ";
			$sql .= $sfsql->SafeCompose(
        " AND country.region=%i ", $id);
		$sql .=
        " AND election_date>='".date('Y-m-d')."' ORDER BY order_date ASC LIMIT 0,5";
		
		$db->Query($sql);
           	if ($db->GetAffectedRows()>0) {
                echo '<div id="upcoming-wrapper">
                <h3>Upcoming Elections</h3>
                <ul>';
   	            while($data1 = $db->fetchAssoc()) {
                    echo '<li>';
                    echo ($data1['show_link']==1)
                        ? '<a href="country.php?ID='.$data1['country'].'">'.$data1['country_name'].'</a>'
                        : $data1['country_name'];
                    echo ' - '.$data1['election_type_str'].'<br /> '.$data1['thedate'].'</li>';
                }
                echo '</ul></div>';
            } 
    }
            
    function show_upcoming_for_country($id) {
            $db = new Db();
			$sfsql =& new SafeSQL_MySQL();
			$sql = "SELECT elections.id, 
            elections.is_active,
            IF ((elections.is_active=4 OR elections.is_active=5),
                CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT('<br />', round_texts.status_text), ''), ' (',status_texts.status_text, ')'),
                IF(elections.is_active=2,
                    CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT('<br />', round_texts.status_text), '')),
                    CONCAT('<a href=\"election.php?ID=', elections.id, '\">', election_types.type_name, '</a>',
                        IF(elections.round_num>0, CONCAT('<br />', round_texts.status_text), ''))
                )
            ) as election_type_str,
            CONCAT(IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
                IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
                YEAR(election_date)) as thedate
            FROM elections, election_types
            LEFT JOIN round_texts ON elections.round_num=round_texts.id
            LEFT JOIN status_texts ON elections.is_active=status_texts.id
            WHERE elections.is_active!=3
            AND election_types.id=elections.election_type ";
			$sql .= $sfsql->SafeCompose(
			" AND country=%i ", $id);
			$sql .=
			" AND election_date>='".date('Y-m-d')."' ORDER BY order_date ASC LIMIT 0,5";
			
			$db->Query($sql);
			if ($db->GetAffectedRows()>0) {
                echo '<div id="upcoming-wrapper">
                <h3>Upcoming Elections</h3>
                <ul>';
   	            while($data1 = $db->fetchAssoc()) {
   	                if ($data1['id'])
                    echo '<li>'.$data1['election_type_str'].'<br />
                        '.$data1['thedate'].'</li>';
                }
                echo '</ul></div>';
            } 
    }

    function show_dropdowns($file, $id=0) {
        echo '<form action="'.$file.'.php" method="get" id="calendar-search">';
        if ($id!=0)
            echo '<input type="hidden" name="ID" value="'.$id.'" />';
        echo '<label for="cal-year">Select Year:</label>
        <select name="year" id="cal-year">
        <option value="any">Any</option>';
        sess::set('calyear',($_REQUEST['year']) ? $_REQUEST['year']: date('Y'));
		foreach(range(1998, date('Y')+5) as $value) {
            $sel = ($year==$value) ? ' selected="selected"': '';
            echo '<option value="'.$value.'"'.$sel.'>'.$value.'</option>';
        }
		echo '</select>
        <input type="image" src="images/button-go.gif" name="submit" id="submit" value="Search" alt="Search" />
        </form><br />';
    }
    
    function show_dropdowns_country($file, $id=0) {
        echo '<form action="'.$file.'.php" method="get" id="calendar-search">';
        if ($id!=0)
            echo '<input type="hidden" name="ID" value="'.$id.'" />';
        echo '<label for="cal-year">Select Year:</label>
        <select name="year" id="cal-year">
        <option value="any">Any</option>';
        $year = ($_REQUEST['year']) ? $_REQUEST['year']: 'any';
		foreach(range(1998, date('Y')+5) as $value) {
            $sel = ($year==$value) ? ' selected="selected"': '';
            echo '<option value="'.$value.'"'.$sel.'>'.$value.'</option>';
        }
		echo '</select>
        <input type="image" src="images/button-go.gif" name="submit" id="submit" value="Search" alt="Search" />
        </form><br />';
    }
    
    function show_search_results() {
		$sfsql =& new SafeSQL_MySQL();

        $order = ($_REQUEST['_orderby']!='') ? $_REQUEST['_orderby']: 'order_date';
        $dir = ($_REQUEST['_dir']!='') ? $_REQUEST['_dir']: 'ASC';
        
        $dbtable = new Table;
        $dbtable->set_user_options();
        $dbtable->setProperty("headers","Flag, Country, Election, Date,null");
        $dbtable->setProperty("tdclasses","first,,,last");
        $dbtable->setProperty("showresults",FALSE);
        $dbtable->setProperty("orderbyoptions","country_name,country_name,type_name,order_date");
        $dbtable->setProperty("noRowsMessage", "No Elections available.");

        $sql = "SELECT
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
        CONCAT(
            IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
            IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
            YEAR(election_date)) as thedate
        FROM elections, country, election_types
        LEFT JOIN status_texts ON elections.is_active=status_texts.id
        LEFT JOIN round_texts ON elections.round_num=round_texts.id
        WHERE elections.country=country.id
            AND status_texts.id=elections.is_active
            AND elections.is_active!=3
            AND election_types.id=elections.election_type";

        if (sess::get('search_year')!='' && sess::get('search_year')!='any')
            $sql .= $sfsql->SafeCompose(" AND YEAR(elections.election_date)=%s ", array(sess::get('search_year')));

        if (sess::get('country')!='')
            $sql .= $sfsql->SafeCompose(" AND country.id=%i ", array(sess::get('country')));

        if (sess::get('type')!='')
            $sql .= $sfsql->SafeCompose(" AND elections.election_type=%i ", array(sess::get('type')));

        $sql .= $sfsql->SafeCompose(" ORDER BY %s %s ", array($order, $dir));
        echo $dbtable->output($sql);
    }
    
    function show_them_all() {
		$sfsql =& new SafeSQL_MySQL();

        $order = ($_REQUEST['_orderby']!='') ? $_REQUEST['_orderby']: 'order_date';
        $dir = ($_REQUEST['_dir']!='') ? $_REQUEST['_dir']: 'ASC';
        echo '<form action="calendar.php" method="get" id="calendar-search">
        <label for="cal-year">Select Year:</label>
        <select name="calyear" id="cal-year">
        <option value="any">Any</option>';
        sess::set('calyear',($_REQUEST['calyear']) ? $_REQUEST['calyear']: date('Y'));
		foreach(range(1998, date('Y')+5) as $value) {
            $sel = (sess::get('calyear')==$value) ? ' selected="selected"': '';
            echo '<option value="'.$value.'"'.$sel.'>'.$value.'</option>';
        }
		echo '</select>
        <input type="image" src="images/button-go.gif" name="submit" id="submit" value="Search" alt="Search" />
        </form><br />';
        $dbtable = new Table;
        $dbtable->set_user_options();
        $dbtable->setProperty("headers","Flag, Country, Election, Date,null");
        $dbtable->setProperty("tdclasses","first,,,last");
        $dbtable->setProperty("showresults",FALSE);
        $dbtable->setProperty("orderbyoptions","country_name,country_name,type_name,order_date");
        $dbtable->setProperty("noRowsMessage", "No Elections available.");

        $sql = "SELECT
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
        CONCAT(
            IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
            IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
            YEAR(election_date)) as thedate
        FROM elections, country, election_types
        LEFT JOIN status_texts ON elections.is_active=status_texts.id
        LEFT JOIN round_texts ON elections.round_num=round_texts.id
        WHERE elections.country=country.id
            AND status_texts.id=elections.is_active
            AND elections.is_active!=3
            AND election_types.id=elections.election_type";

        if (sess::get('calyear')!='' && sess::get('calyear')!='any')
            $sql .= $sfsql->SafeCompose(" AND YEAR(elections.election_date)=%s ",
				sess::get('calyear'));

        //if (sess::get('country')!='')
        //    $sql .= " AND country.id=".sess::get('country');

        //if (sess::get('type')!='')
        //    $sql .= " AND elections.election_type=".sess::get('type');

        $sql .= $sfsql->SafeCompose(" ORDER BY %s %s ", array($order, $dir));
        
        echo $dbtable->output($sql);
    }
    
    function show_them_by_region($id) {
		$sfsql = & new SafeSQL_MySQL();
		
        $order = ($_REQUEST['_orderby']!='') ? $_REQUEST['_orderby']: 'order_date';
        $dir = ($_REQUEST['_dir']!='') ? $_REQUEST['_dir']: 'ASC';

        $dbtable = new Table;
        $dbtable->set_user_options();
        $dbtable->setProperty("headers","Flag, Country, Election, Date");
        $dbtable->setProperty("tdclasses","first,,,last");
        $dbtable->setProperty("showresults",FALSE);
        $dbtable->setProperty("orderbyoptions","country_name,country_name,type_name,order_date");
        $dbtable->setProperty("noRowsMessage", "No Elections available.");

        $sql = "SELECT
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
        ) as election_type_str,
        CONCAT(
            IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
            IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
            YEAR(election_date)) as thedate
        FROM elections, country, election_types
        LEFT JOIN status_texts ON elections.is_active=status_texts.id
        LEFT JOIN round_texts ON elections.round_num=round_texts.id 
        WHERE elections.country=country.id
            AND elections.is_active!=3 
            AND election_types.id=elections.election_type ";
			
		$sql .= $sfsql->SafeCompse("AND country.region=%i ", $id);

        if (sess::get('year')!='' && sess::get('year')!='any')
            $sql .= $sfsql->SafeCompose(" AND YEAR(elections.election_date)=%s ", sess::get('year'));
            
        //if (sess::get('type')!='')
        //    $sql .= " AND elections.election_type=".$_REQUEST['year'];

        $sql .= $sfsql->SafeCompose(" ORDER BY %s %s ", array($order, $dir));
        
        echo $dbtable->output($sql);
    }
    
    function show_them_by_country($id) {
        $order = ($_REQUEST['_orderby']!='') ? $_REQUEST['_orderby']: 'order_date';
        $dir = ($_REQUEST['_dir']!='') ? $_REQUEST['_dir']: 'ASC';
		
		$sfsql =& new SafeSQL_MySQL();

        $dbtable = new Table;
        $dbtable->set_user_options();
        $dbtable->setProperty("headers","Flag, Country, Election, Date");
        $dbtable->setProperty("tdclasses","first,,,last");
        $dbtable->setProperty("showresults",FALSE);
        $dbtable->setProperty("orderbyoptions","country_name,country_name,type_name,order_date");
        $dbtable->setProperty("noRowsMessage", "No Elections available.");

        $sql = "SELECT
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
        ) as election_type_str,
        CONCAT(
            IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
            IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
            YEAR(election_date)) as thedate
        FROM elections, country, election_types
        LEFT JOIN status_texts ON elections.is_active=status_texts.id
        LEFT JOIN round_texts ON elections.round_num=round_texts.id
        WHERE elections.country=country.id
            AND elections.is_active!=3
            AND election_types.id=elections.election_type ";
			
		$sql .= $sfsql->SafeCompose(" AND country.id=%i ", $id);
		
        if ($_REQUEST['year']!='' && $_REQUEST['year']!='any')
            $sql .= $sfsql->SafeCompose(" AND YEAR(elections.election_date)=%s", $_REQUEST['year']);

        //if (sess::get('type')!='')
        //    $sql .= " AND elections.election_type=".sess::get('type');

        $sql .= $sfsql->SafeCompose(" ORDER BY %s %s ", array($order,$dir));
        echo $dbtable->output($sql);
    }
    
    
    //COUNTRY,DATE,ELECTION TYPE,REGISTERED VOTERS,VOTES CAST,% OF REGISTERED VOTERS
    function show_turnout() {
		$sfsql =& new SafeSQL_MySQL();
		
        $order = ($_REQUEST['_orderby']!='') ? $_REQUEST['_orderby']: 'order_date';
        $dir = ($_REQUEST['_dir']!='') ? $_REQUEST['_dir']: 'ASC';
        echo '<form action="voter-turnout.php" method="get" id="calendar-search">
        <label for="search_year">Select Year:</label>
        <select name="search_year" id="cal-year">
        <option value="any">Any</option>';
        sess::set('search_year',($_REQUEST['search_year']) ? $_REQUEST['search_year']: sess::get('search_year'));
		foreach(range(1998, date('Y')+5) as $value) {
            $sel = (sess::get('search_year')==$value) ? ' selected="selected"': '';
            echo '<option value="'.$value.'"'.$sel.'>'.$value.'</option>';
        }
		echo '</select>
        <input type="image" src="images/button-go.gif" name="submit" id="submit" value="Search" alt="Search" />
        </form><br />';
        $sql = "SELECT
            election_types.type_name as election,
            elections.id AS id,
            elections.election_type AS election_type,
            IF(country.show_link=1,
                CONCAT('<a href=\"country.php?ID=', country.id, '\">', country.country_name, '</a>'),
                country.country_name
            ) AS thecountry,
            CONCAT(
                IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
                IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
                YEAR(election_date)) as thedate,
            IF ((elections.is_active=4 OR elections.is_active=5),
                CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT('<br />', round_texts.status_text), ''), ' (',status_texts.status_text, ')'),
                IF(elections.is_active=2,
                    CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT('<br />', round_texts.status_text), '')),
                    CONCAT('<a href=\"election.php?ID=', elections.id, '\">', election_types.type_name, '</a>',
                        IF(elections.round_num>0, CONCAT('<br />', round_texts.status_text), ''))
                )
            ) as thetype,
            IF(elections.reg_voters<=0, 0, elections.reg_voters) as reg_voters,
            elections.assembly_type,
            elections.round_num,
            elections.votes_cast,
            elections.votes_cast_percentage,
            elections.votes_cast1,
            elections.votes_cast1_percentage,
            elections.uh_seats_at_stake,
            elections.lh_seats_at_stake,
            elections.show_uh_results,
            elections.show_lh_results,
            IF(elections.uh_name='', CONCAT('<em>', elections.uh_name_foreign,'</em>'), elections.uh_name) as uh_name,
            IF(elections.lh_name='', CONCAT('<em>', elections.lh_name_foreign,'</em>'), elections.lh_name) as lh_name,
            elections.votes_cast,
            elections.votes_cast1,
            elections.votes_cast_percentage,
            elections.votes_cast1_percentage,
            IF(elections.election_type=4, COUNT(candidates_ref.votes_cast_percentage),0) as counter,
            elections.votes_cast_percentage,
            elections.votes_cast
            FROM elections, country, election_types
            LEFT JOIN status_texts ON elections.is_active=status_texts.id
            LEFT JOIN candidates_ref ON elections.id=candidates_ref.election
            LEFT JOIN round_texts ON elections.round_num=round_texts.id
            WHERE elections.show_results=1
            AND elections.country=country.id
            AND elections.is_active!=3
            AND election_types.id=elections.election_type";

        if (sess::get('search_year')!='' && sess::get('search_year')!='any')
            $sql .= $sfsql->SafeCompose(" AND YEAR(elections.election_date)=%s ", sess::get('search_year'));

        $sql .= $sfsql->SafeCompose(" GROUP BY elections.id ORDER BY %s %s ", array($order, $dir));
        $db = new Db();
        $db->Query($sql);

        if (sess::get('type')=='' || sess::get('type')=='any' || sess::get('type')==4) {
            $sql1 = "SELECT
                elections.id AS elid,
                candidates_ref.order_id AS order_id,
                candidates_ref.id AS refid,
                candidates_ref.votes_cast,
                candidates_ref.votes_cast_percentage
                FROM candidates_ref
                LEFT JOIN elections ON elections.id=candidates_ref.election
                WHERE elections.show_results=1
                AND elections.id=candidates_ref.election
                AND elections.is_active!=3";

            if (sess::get('search_year')!='' && sess::get('search_year')!='any')
                $sql1 .= $sfsql->SafeCompose(" AND YEAR(elections.election_date)=%s ",sess::get('search_year'));

            $sql1 .= " AND elections.election_type=4
            ORDER BY elections.id ASC, candidates_ref.order_id ASC";

            $db1 = new Db();
            $db1->Query($sql1);
            $refrow = $db1->GetAffectedRows();
            while($rows = $db1->fetchAssoc())
                $refs[$rows['elid']][$rows['refid']] = $rows;
        }

        if ($db->GetAffectedRows()>0) {
            $link = 'voter-turnout.php?';
            if (sess::get('year')!='' && sess::get('year')!='any')
                $link .= '&year='.sess::get('year');
            $dirnow = ($_REQUEST['_dir']=='ASC') ? '&_dir=DESC': '&_dir=ASC';
            $dirnowimg = ($_REQUEST['_dir']=='ASC')
                ? '<img src="images/_table_ASC.gif" style="border:0;" alt="ASC" />'
                : '<img src="images/_table_DESC.gif" style="border:0;" alt="DESC" />';

            //'.($_REQUEST['_orderby']=='votes_cast_percentage'?$dirnowimg:'').'
            //<a href="'.$link.'&_orderby=votes_cast_percentage'.$dirnow.'">
            //'.($_REQUEST['_orderby']=='votes_cast'?$dirnowimg:'').'
            //<a href="'.$link.'&_orderby=votes_cast'.$dirnow.'">
            echo '<table id="election-calendar">
            <thead><tr>
            	<th scope="col"><a href="'.$link.'&_orderby=country_name'.$dirnow.'"> Country</a>'.($_REQUEST['_orderby']=='country_name'?$dirnowimg:'').'</th>
            	<th scope="col"><a href="'.$link.'&_orderby=order_date'.$dirnow.'"> Date</a>'.($_REQUEST['_orderby']=='order_date'?$dirnowimg:'').'</th>
            	<th scope="col"><a href="'.$link.'&_orderby=election'.$dirnow.'"> Election</a>'.($_REQUEST['_orderby']=='election'?$dirnowimg:'').'</th>
            	<th scope="col"><a href="'.$link.'&_orderby=reg_voters'.$dirnow.'"> Registered Voters</a>'.($_REQUEST['_orderby']=='reg_voters'?$dirnowimg:'').'</th>
            	<th scope="col" style="color:#1f4671;"> Votes Cast</th>
            	<th scope="col" style="color:#1f4671;"> % of Registered Voters</th>
            </tr></thead>
            <tbody>';
            while($data = $db->fetchAssoc()) {
               
                if ($data['round_num']<=11) {
                if ($data['election_type']==4) {
                    if ($refrow>0) {
                    foreach($refs[$data['id']] as $refid => $refdata) {
                        $counter = count($refs[$data['id']]);

                        $reg_voters = ($data['reg_voters']<0) ? 'N/A' : number_format($data['reg_voters'],0);
                        $refvotes_cast = ($refdata['votes_cast']<0) ? 'N/A' : number_format($refdata['votes_cast'],0);
                        $refvotes_castp = ($refdata['votes_cast_percentage']<0) ? 'N/A' : $refdata['votes_cast_percentage'].'%';
                        
                        echo '<tr><td class="first">'.$data['thecountry'].'</td>
                        	<td>'.$data['thedate'].'</td>
                        	<td>'.$data['thetype'].' '.($counter>1? 'Provision '.$refdata['order_id']:'').'</td>
                        	<td>'.$reg_voters.'</td>
                        	<td>'.$refvotes_cast.'</td>
                        	<td class="last">'.$refvotes_castp.'</td>
                        </tr>';
                    }
                    }
                } else if ($data['election_type']==2 || $data['election_type']==3) {
                    $reg_voters = ($data['reg_voters']<0) ? 'N/A' : number_format($data['reg_voters'],0);
                    if ($data['assembly_type']=='uni') {
                        $votes_cast = ($data['votes_cast']<0) ? 'N/A' :number_format($data['votes_cast'],0);
                        $votes_castp = ($data['votes_cast_percentage']<0) ? 'N/A' :$data['votes_cast_percentage'].'%';
                        echo '<tr><td class="first">'.$data['thecountry'].'</td>
                        	<td>'.$data['thedate'].'</td>
                        	<td>'.$data['thetype'].'</td>
                        	<td>'.$reg_voters.' </td>
                        	<td>'.$votes_cast.'</td>
                        	<td class="last">'.$votes_castp.'</td>
                        </tr>';
                    } else {
                        $votes_cast = ($data['votes_cast']<0) ? 'N/A' :number_format($data['votes_cast'],0);
                        $votes_castp = ($data['votes_cast_percentage']<0) ? 'N/A' :$data['votes_cast_percentage'].'%';
                        $votes_cast1 = ($data['votes_cast1']<0) ? 'N/A' :number_format($data['votes_cast1'],0);
                        $votes_castp1 = ($data['votes_cast1_percentage']<0) ? 'N/A' :$data['votes_cast1_percentage'].'%';
                        if ($data['show_uh_results']==1) {
                            echo '<tr><td class="first">'.$data['thecountry'].'</td>
                            	<td>'.$data['thedate'].'</td>
                            	<td>'.$data['thetype'].' '.($data['show_lh_results']==1?'<br />'.$data['uh_name']:'').'</td>
                            	<td>'.$reg_voters.' </td>
                            	<td>'.$votes_cast.'</td>
                            	<td class="last">'.$votes_castp.'</td>
                            </tr>';
                        }

                        if ($data['show_lh_results']==1) {
                            echo '<tr><td class="first">'.$data['thecountry'].'</td>
                            	<td>'.$data['thedate'].'</td>
                            	<td>'.$data['thetype'].' '.($data['show_uh_results']==1?'<br />'.$data['lh_name']:'').'</td>
                            	<td>'.$reg_voters.'</td>
                            	<td>'.$votes_cast1.'</td>
                            	<td class="last">'.$votes_castp1.'</td>
                            </tr>';
                        }
                    }
                } else {
                     $reg_voters = ($data['reg_voters']<0) ? 'N/A' : number_format($data['reg_voters'],0);
                    $votes_cast = ($data['votes_cast']<0) ? 'N/A' :number_format($data['votes_cast'],0);
                    $votes_castp = ($data['votes_cast_percentage']<0) ? 'N/A' :$data['votes_cast_percentage'].'%';
                    echo '<tr><td class="first">'.$data['thecountry'].'</td>
                    	<td>'.$data['thedate'].'</td>
                    	<td>'.$data['thetype'].'</td>
                    	<td>'.$reg_voters.'</td>
                    	<td>'.$votes_cast.'</td>
                    	<td class="last">'.$votes_castp.'</td>
                    </tr>';
                }
                }
            }
            echo '</tbody></table>';
        } else {
            echo '<p>No results available</p>';
        }
    }

    function get_image($src) {
        $src = trim($src);
        $alttag = htmlspecialchars($src);
        if (strstr(strtolower($src), 'allafrica'))
            $image = '<img src="images/icons/allafrica.gif" alt="'.$alttag.'" />';
        else if (strstr(strtolower($src), 'electionguide'))
            $image = '<img src="images/icons/election-guide.gif" alt="'.$alttag.'" />';
        else if (strstr(strtolower($src), 'cnn'))
            $image = '<img src="images/icons/cnn.gif" alt="'.$alttag.'" />';
        else if (strstr(strtolower($src), 'bbc'))
            $image = '<img src="images/icons/bbc.gif" alt="'.$alttag.'" />';
        else if (strstr(strtolower($src), 'reuters'))
            $image = '<img src="images/icons/reuters.gif" alt="'.$alttag.'" />';
        else
            $image = '';
        return $image;
    }

    function region_news($id) {
            $db = new Db();
			$sfsql =& new SafeSQL_MySQL();
			
			$sql = "SELECT news_items.id, news_title, news_date,ext_id,
               IF(ext_id>0, news_link, CONCAT('http://www.electionguide.org/country-news.php?ID=',country.id,'#anchor_', news_items.id)) as news_link,
                IF(ext_id>0, external_news.rss_title, 'ElectionGuide') as news_source,
                news_content, is_external
                FROM news_items
                LEFT JOIN country on country.id=news_items.country
                LEFT JOIN regions on regions.id=country.region
                LEFT JOIN external_news ON external_news.id=news_items.ext_id
                WHERE news_items.is_active=1 ";
			$sql .= $sfsql->SafeCompose(" AND country.region=%i ", array($id));
			$sql .= " ORDER BY news_items.news_date DESC LIMIT 0,3;";
			
            $db->Query($sql);
           	if ($db->GetAffectedRows()>0) {
   	            while($data = $db->fetchAssoc()) {
                    $target = ($data['ext_id']>0) ? ' target="blank"' : '';
                    $desc = (strlen($data['news_content'])>50)
                        ? substr($data['news_content'],0,50).'... '
                        : $data['news_content'];
                    $desc = htmlspecialchars(strip_tags(html_entity_decode($desc)));
                    $image = ElectionList::get_image($data['news_source']);
                    echo '<dt><a href="'.$data['news_link'].'"'.$target.'>'.htmlspecialchars(ucwords($data['news_title'])).'</a>
                        '.$image.'</dt>
                        <dd>'.$desc.'<a href="'.$data['news_link'].'"'.$target.'>Full Story</a></dd>';
                }
            }else {
                ElectionList::generic_news(); 
            }
    }
    
    function country_news($id) {
            $db = new Db();
			$sfsql =& new SafeSQL_MySQL();
			
            $sql = "SELECT news_items.id, news_title, news_date,
               IF(ext_id>0, news_link, CONCAT('http://www.electionguide.org/country-news.php?ID=',country.id,'#anchor_', news_items.id)) as news_link,
                IF(ext_id>0, external_news.rss_title, 'ElectionGuide') as news_source,
                news_content, ext_id
                FROM news_items
                LEFT JOIN country on country.id=news_items.country
                LEFT JOIN external_news ON external_news.id=news_items.ext_id
                WHERE news_items.is_active=1";
				
				$sql .= $sfsql->SafeCompose(" AND news_items.country=%i ", array($id));
				$sql .= " ORDER BY news_items.news_date DESC LIMIT 0,3;";
				
			$db->Query($sql);
           	if ($db->GetAffectedRows()>0) {
   	            while($data = $db->fetchAssoc()) {
                    $target = ($data['ext_id']>0) ? ' target="blank"' : '';
                    $desc = (strlen($data['news_content'])>50)
                        ? substr($data['news_content'],0,50).'... '
                        : $data['news_content'];
                    $desc = htmlspecialchars(strip_tags(html_entity_decode($desc)));
                    $image = ElectionList::get_image($data['news_source']);
                    echo '<dt><a href="'.$data['news_link'].'"'.$target.'>'.htmlspecialchars(ucwords($data['news_title'])).'</a>
                        '.$image.'</dt>
                        <dd>'.$desc.'<a href="'.$data['news_link'].'"'.$target.'>Full Story</a></dd>';
                }
        } else {
			$sql = "SELECT news_items.id, news_title, news_date,ext_id,
                IF(ext_id>0, news_link, CONCAT('http://www.electionguide.org/country-news.php?ID=',country.id,'#anchor_', news_items.id)) as news_link,
                IF(ext_id>0, external_news.rss_title, 'ElectionGuide') as news_source,
                news_content, is_external
                FROM news_items
                LEFT JOIN country on country.id=news_items.country
                LEFT JOIN regions on regions.id=country.region
                LEFT JOIN external_news ON external_news.id=news_items.ext_id
                WHERE news_items.is_active=1";
				$sql .= $sfsql->SafeCompose(" AND country.id=%i ", array($id));
				$sql .= " ORDER BY news_items.news_date DESC LIMIT 0,3;";
				
			$db->Query($sql);
           	if ($db->GetAffectedRows()>0) {
   	            while($data = $db->fetchAssoc()) {
                    $target = ($data['ext_id']>0) ? ' target="blank"' : '';
                    $desc = (strlen($data['news_content'])>50)
                        ? substr($data['news_content'],0,50).'... '
                        : $data['news_content'];
                    $desc = htmlspecialchars(strip_tags(html_entity_decode($desc)));
                    $image = ElectionList::get_image($data['news_source']);
                    echo '<dt><a href="'.$data['news_link'].'"'.$target.'>'.htmlspecialchars(ucwords($data['news_title'])).'</a>
                        '.$image.'</dt>
                        <dd>'.$desc.'<a href="'.$data['news_link'].'"'.$target.'>Full Story</a></dd>';
                }
            } else {
                ElectionList::generic_news(); 
            }
        }
    }

    function generic_news() {
            $db = new Db();
            $db->Query("SELECT news_items.id, news_title, news_date,
                IF(ext_id>1, news_link, CONCAT('http://www.electionguide.org/news.php#anchor_', news_items.id)) as news_link,
                IF(ext_id>0, external_news.rss_title, 'ElectionGuide') as news_source,
                news_content, ext_id
                FROM news_items
                LEFT JOIN country on country.id=news_items.country
                LEFT JOIN external_news ON external_news.id=news_items.ext_id
                WHERE news_items.is_active=1 
                ORDER BY news_items.news_date DESC LIMIT 0,3");
           	if ($db->GetAffectedRows()>0) {
   	            while($data = $db->fetchAssoc()) {
                    $target = ($data['ext_id']>0) ? ' target="blank"' : '';
                    $desc = (strlen($data['news_content'])>50)
                        ? substr($data['news_content'],0,50).'... '
                        : $data['news_content'];
                    $desc = htmlspecialchars(strip_tags(html_entity_decode($desc)));
                    $image = ElectionList::get_image($data['news_source']);
                    echo '<dt><a href="'.$data['news_link'].'"'.$target.'>'.htmlspecialchars(ucwords($data['news_title'])).'</a>
                        '.$image.'</dt>
                        <dd>'.$desc.'<a href="'.$data['news_link'].'"'.$target.'>Full Story</a></dd>';
                }
            }
    }

    function show_eguide_news($id) {
            $db = new Db();
			$sfsql =& new SafeSQL_MySQL();
			
			$sql = 
            "SELECT news_items.id, news_title, news_date,
                IF(ext_id>0, news_link, CONCAT('http://www.electionguide.org/country-news.php?ID=',country.id,'#anchor_', news_items.id)) as news_link,
                IF(ext_id>0, external_news.rss_title, 'ElectionGuide') as news_source,
                news_content, ext_id
                FROM news_items
                LEFT JOIN country on country.id=news_items.country
                LEFT JOIN external_news ON external_news.id=news_items.ext_id
                WHERE news_items.is_active=1 ";
			
			$sql .=
				$sfsql->SafeCompose(" AND news_items.country=%i ", $id)
                . " ORDER BY news_items.news_date DESC LIMIT 0,2 ";
				
			$db->Query($sql);
            $firstnews=FALSE;
            $numrows = $db->GetAffectedRows();
            if ($numrows>0)
                $firstnews=TRUE;
                
            if ($numrows>0) {
                echo '<h3>News</h3>';
   	            while($data = $db->fetchAssoc()) {
                    $target = ($data['ext_id']>0) ? ' target="blank"' : '';
                    $desc = (strlen($data['news_content'])>100)
                        ? substr($data['news_content'],0,100).'... '
                        : $data['news_content'];
                    $desc = htmlspecialchars(strip_tags(html_entity_decode($desc)));
                    $image = ElectionList::get_image($data['news_source']);
                     
                    echo '<p><a style="color:#003399;" href="'.$data['news_link'].'"'.$target.'><strong>'.htmlspecialchars(html_entity_decode($data['news_title'])).'</strong></a> '.$image.'<br />
                    '.html_entity_decode($desc).' <a href="'.$data['news_link'].'"'.$target.'>Full Story</a></p>';
                }
            }
            if ($numrows>=4) {
                $db->Query("SELECT news_items.id, news_title, news_date, ext_id,
                   IF(ext_id>0, news_link, CONCAT('http://www.electionguide.org/country-news.php?ID=',country.id,'#anchor_', news_items.id)) as news_link,
                    IF(ext_id>0, external_news.rss_title, 'ElectionGuide') as news_source,
                    news_content, is_external
                    FROM news_items
                    LEFT JOIN country on country.id=news_items.country
                    LEFT JOIN regions on regions.id=country.region
                    LEFT JOIN external_news ON external_news.id=news_items.ext_id
                    WHERE news_items.is_active=1 "
					. $sfsql->SafeCompose(" AND country.id=%i ", $id)
					. " ORDER BY news_items.news_date DESC LIMIT 0,2");
                $numrows2 = $db->GetAffectedRows();
               	if ($numrows2>0) {
       	            while($data = $db->fetchAssoc()) {
                        $target = ($data['ext_id']>0) ? ' target="blank"' : '';
                        $desc = (strlen($data['news_content'])>100)
                            ? substr($data['news_content'],0,100).'... '
                            : $data['news_content'];
                        $desc = htmlspecialchars(strip_tags(html_entity_decode($desc)));
                        $image = ElectionList::get_image($data['news_source']);
                        echo ($firstnews==TRUE) ? '' : '<h3>News</h3>';
                        echo '<p><a href="'.$data['news_link'].'"'.$target.'><strong>'.htmlspecialchars(html_entity_decode($data['news_title'])).'</strong></a> '.$image.'<br />
                        '.html_entity_decode($desc).' <a href="'.$data['news_link'].'"'.$target.'>Full Story</a></p>';
                    }
                }
            }
    }
    

    function get_eguide_links($id) {
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
		
        $sql = "SELECT link_name,link_url
        FROM country_links
        WHERE is_active=1 "
		. $sfsql->SafeCompose(
        "AND country=%i", $id)
        . " ORDER BY order_id";
        $db->Query($sql);
        if ($db->GetAffectedRows()>0) {
            echo '<div><h3>Links:</h3><ul>';
  		    while($data = $db->fetchAssoc())
                echo '<li><a href="'.$data['link_url'].'" target="_blank">'.html_entity_decode($data['link_name']).'</a></li>';
      		echo '</ul></div>';
        }
    }
}
function fnumber($nums) {
	$num = $nums['reg_voters'];
    return ($num>0) ? number_format($num) : 'N/A';
}
    
function fnumber1($nums) {
	$num = $nums['votes_cast'];
    return ($num>0) ? number_format($num) : 'N/A';
}
function fpercentage($nums) {
	$num = $nums['votes_cast_percentage'];
    return ($num>0) ? $num.'%' : 'N/A';
}
?>
