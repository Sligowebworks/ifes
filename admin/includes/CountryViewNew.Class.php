<?php

class CountryView extends ElectionView{
    var $id         = '';
    var $table      = '';
    var $bigarray   = array();
    var $upper_house_name = '';
    var $lower_house_name = '';
    var $complete_asesembly_name = '';
    var $round_text = '';
    var $all_data = array();
    var $all_stages = array();
    var $all_stages_text = array();
    var $multiple_results = FALSE;
    var $data = array();
    var $content = '';
    var $header = '';
    var $election_type = '';
    var $elections = array();
    
	function CountryView($id) {
        $this->id = $id; 
        $db = new Db();
        $sfsql =& new SafeSQL_MySQL();
		$contentsql = $sfsql->SafeCompose(
        "SELECT custom_content FROM country WHERE country.id=%s", $this->id);
        
        $db->Query($contentsql);
        if($db->GetAffectedRows()==1){
            $data = $db->fetchAssoc();
            $this->content = $data['custom_content'];
            if ($this->content=='') {
                $this->content = $this->get_election();
            }
        }
        
        
        
        $sql = "SELECT
        country.id as country,
        regions.region as region_name,
        country.region,
        country.country_name,
        country.country_flag,
        country.official_name,
        country.chief_title,
        country.chief_fname,
        country.chief_lname,
IF(country.chief_term=0, '', country.chief_term) AS chief_term,
TRIM(chiefby.the_way) as chief_electedby,
TRIM(chieftype.the_way) as chief_elected_type,
IF(TRIM(country.chief_comments)='','', CONCAT('<p class=\"comment\">* ', country.chief_comments, '</p>')) AS chief_comments,
IF(country.chief_comments!='','*', '') as chief_comments_note,
country.chief_since,
country.head_title,
country.head_fname,
country.head_lname,
IF(country.head_term=0, '', country.head_term) AS head_term,
TRIM(headby.the_way) as head_electedby,
TRIM(headtype.the_way) as head_elected_type,
IF(TRIM(country.head_comments)='','', CONCAT('<p class=\"comment\">** ', country.head_comments, '</p>')) AS head_comments,
IF(country.head_comments!='','**', '') as head_comments_note,
IF((country.assembly_name!='' && country.assembly_name_foreign!=''),
    CONCAT(country.assembly_name, ' (<em>', country.assembly_name_foreign, '</em>)'),
    IF((country.assembly_name!='' && country.assembly_name_foreign=''),
        country.assembly_name,
        CONCAT('<em>', country.assembly_name_foreign, '</em>'))
) as complete_assembly_name,
IF(country.assembly_name!='',country.assembly_name, CONCAT(' <em>', country.assembly_name_foreign, '</em>')) as assembly,
country.assembly_name,
country.assembly_name_foreign,
IF(country.assembly_type='bi', 'a bicameral', 'a unicameral') AS assembly_type_string,
IF(country.assembly_seats='-1', 'N/A', country.assembly_seats) AS assembly_seats,
IF(country.assembly_term=0, '', country.assembly_term) AS assembly_term,
IF(TRIM(country.assembly_comments)='','', CONCAT('<p class=\"comment\">*** ', country.assembly_comments, '</p>')) AS assembly_comments,
IF(country.assembly_comments!='','***', '') as assembly_comments_note,
country.uh_name,
country.uh_name_foreign,
IF(country.uh_seats='-1', 'N/A', country.uh_seats) AS uh_seats,
IF(country.uh_term=0, '', country.uh_term) AS uh_term,
IF(TRIM(country.uh_comments)='','', CONCAT('<p class=\"comment\">* ', country.uh_comments, '</p>')) AS uh_comments,
IF(country.uh_comments!='','*', '') as uh_comments_note,

IF(
    (country.uh_name!='' && country.uh_name_foreign!=''),
    CONCAT(country.uh_name, ' (<em>', country.uh_name_foreign, '</em>)'),
    IF((country.uh_name!='' && country.uh_name_foreign=''),
        country.uh_name,
        CONCAT('<em>', country.uh_name_foreign, '</em>'))
) as upper_house_name,
IF(
    (country.lh_name!='' && country.lh_name_foreign!=''),
    CONCAT(country.lh_name, ' (<em>', country.lh_name_foreign, '</em>)'),
    IF((country.lh_name!='' && country.lh_name_foreign=''),
        country.lh_name,
        CONCAT('<em>', country.lh_name_foreign, '</em>'))
) as lower_house_name,
IF(country.lh_name!='',country.lh_name, CONCAT(' <em>', country.lh_name_foreign, '</em>')) as lower_house,
IF(country.uh_name!='',country.uh_name, CONCAT(' <em>', country.uh_name_foreign, '</em>')) as upper_house,
country.lh_name,
country.lh_name_foreign,
IF(country.lh_seats='-1', 'N/A', country.lh_seats) AS lh_seats,
IF(country.lh_term=0, '', country.lh_term) AS lh_term,
IF(TRIM(country.lh_comments)='','', CONCAT('<p class=\"comment\">** ', country.lh_comments, '</p>')) AS lh_comments,
IF(country.lh_comments!='','**', '') as lh_comments_note,
IF(country.population='-1', 'N/A', FORMAT(country.population,0)) AS population,
MONTHNAME(CONCAT(country.population_year, '-', country.population_month,'-00')) as population_month,
country.population_year,
IF ((country.population_year!=0 || country.population_comments!=''),
    CONCAT(' (', MONTHNAME(CONCAT(country.population_year, '-', country.population_month,'-00')), ' ', IF(country.population_year!=0, country.population_year, ''), ' ',TRIM(country.population_comments), ') '),
    ''
) AS population_comments
        FROM country
        LEFT JOIN regions ON country.region=regions.id
        LEFT JOIN assembly_ways chieftype ON chieftype.id=country.chief_election_type
        LEFT JOIN election_ways chiefby ON chiefby.id=country.chief_electedby
        LEFT JOIN assembly_ways headtype ON headtype.id=country.head_election_type
        LEFT JOIN election_ways headby ON headby.id=country.head_electedby ";
		
		$sql .= $sfsql->SafeCompose(
		" WHERE country.id=%s", $this->id);
        $sql .= " AND show_link=1";

        $db->Query($sql);
        if ($db->GetAffectedRows()>0) {
            while($data = $db->fetchAssoc()) {
                $this->data[] = $data;
                $this->country_flag = $data['country_flag'];
                $this->region = $data['region'];
                $this->region_name = $data['region_name'];
            }
        }
	}
	
    function get_election() {
        $e = new CountryExport($this->id);
        $ret = $e->save(TRUE);
        return $ret;
    }
    
    function get_country() {
        $header = '<table id="country-meta"><tbody><tr>'. $this->get_icon().'<td><ul>
            <li>Country: <a href="country.php?ID=[[country]]">[[country_name]]</a></li>
			<li>Official Name: <a href="country.php?ID=[[country]]">[[country_name]]</a></li>';
		if ($this->region!=0)
            $header .= '<li>Region: <a href="region.php?ID=[[region]]">[[region_name]]</a></li>';

        $header .= '</ul></td></tr></tbody></table>';
        return $header;
    }
    function get_dbresults() {       
        $db = new Db();
        $sfsql =& new SafeSQL_MySQL();
        $sql = "SELECT curr_groups.int_id as id,
        curr_groups.group_name,
        IF(curr_groups.assembly_type='up', 'uh', 'lh') as atype,
        IF(curr_groups.seats='-1', '', curr_groups.seats) AS seats,
        curr_groups.term,
        IF(election_ways.the_way='N/A', '', TRIM(election_ways.the_way)) as electedby,
        IF(assembly_ways.the_way='N/A', '', TRIM(assembly_ways.the_way)) as election_type
        FROM curr_groups
        LEFT JOIN assembly_ways ON assembly_ways.id=curr_groups.election_type
        LEFT JOIN election_ways ON election_ways.id=curr_groups.elected_by
        WHERE curr_groups.is_active=1 ";
		$sql .= $sfsql->SafeCompose(
        " AND curr_groups.country=%s", $this->id);
		$sql .=
        " ORDER BY curr_groups.int_id";
        $db->Query($sql);
        while($data = $db->fetchAssoc())
            $groups[$data['atype']][$data['id']] = $data;
        return $groups;
        
    }
    
    function webview() {
        $this->header = '';
        foreach ($this->data as $round => $values){
            foreach ($values as $key => $value){
                $this->header = str_replace('[['.$key.']]', html_entity_decode($value), $this->header);
                $this->content = str_replace('[['.$key.']]', html_entity_decode($value), $this->content);
            }
        }
        //$this->content = $this->get_electoral();
        $gs = $this->get_dbresults();
        if ($this->election_type!=4) {
            if(is_array($gs)) {
            foreach($gs as $h => $hvalues) {
                foreach($hvalues as $g => $gvalues) {
                    foreach($gvalues as $key => $value) {
                        $this->content = str_replace('[['.$h.'.'.$g.'.'.$key.']]', html_entity_decode($value), $this->content);
                    }
                }
            }
            }
        }
        echo $this->header.$this->content;
        $this->get_future_elections();
        $this->get_last_elections();
    }
    
    function view() {
        $this->header = $this->get_country();
        foreach ($this->data as $round => $values){
            foreach ($values as $key => $value){
                $this->header = str_replace('[['.$key.']]', html_entity_decode($value), $this->header);
                $this->content = str_replace('[['.$key.']]', html_entity_decode($value), $this->content);
            }
        }
        //$this->content = $this->get_electoral();
        $gs = $this->get_dbresults();
        if ($this->election_type!=4) {
            if(is_array($gs)) {
            foreach($gs as $h => $hvalues) {
                foreach($hvalues as $g => $gvalues) {
                    foreach($gvalues as $key => $value) {
                        $this->content = str_replace('[['.$h.'.'.$g.'.'.$key.']]', html_entity_decode($value), $this->content);
                    }
                }
            }
            }
        }
        echo $this->header.$this->content;
    }
    

    function get_links_widh_cats() {
        $link_types = Common::get_link_types();
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
		
        $sql = $sfsql->SafeCompose(
		"SELECT * FROM country_links WHERE is_active=1 AND country=%s", $this->id);
		$sql .= " ORDER BY order_id";
		
        $db->Query($sql);
        if ($db->GetAffectedRows()>0) {
            echo '<h3>Links:</h3>';
  		    while($data = $db->fetchAssoc()) {
                $type = explode(',', $data['link_type']);
                foreach($type as $val)
                    $link_arr[$val][] = $data;
  		    }
      		foreach($link_arr as $category=>$values) {
  		        echo '<h3>'.$link_types[$category].'</h3><ul>';
      		    foreach($values as $vals)
  		            echo '<li><a href="'.$vals['link_url'].'" target="_blank">'.$vals['link_name'].'</a></li>';
      		    echo "</ul>";
      		}
        }
    }

    function get_future_elections() {
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
		
        $sql = "SELECT elections.id,
        elections.is_active,
        elections.round_num,
        round_texts.status_text as round_text,
        election_types.type_name,
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
            YEAR(election_date)) as election_date
        FROM elections, election_types
        LEFT JOIN round_texts ON elections.round_num=round_texts.id
        LEFT JOIN status_texts ON elections.is_active=status_texts.id ";
		$sql .= $sfsql->SafeCompose(
        " WHERE country=%s", $this->id);
		$sql .= " AND elections.is_active!=3
        AND election_types.id=elections.election_type
        AND election_date>'".date('Y-m-d')."' ORDER BY order_date DESC";
		
		
        $db->Query($sql);
        if ($db->GetAffectedRows()>0) {
            echo '<h3>Future elections</h3><ul>';
            while($data = $db->fetchAssoc()) {
                echo '<li>'.$data['thetype'].' - '.$data['election_date'].'</li>';
            }
            echo '</ul>';
        }
    }

    function get_last_elections() {
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
		
        $sql = "SELECT elections.id,
        elections.is_active,
        elections.round_num,
        round_texts.status_text as round_text,
        election_types.type_name,
        IF ((elections.is_active=4 OR elections.is_active=5),
            CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT(' ', round_texts.status_text), ''), ' (',status_texts.status_text, ')'),
            IF(elections.is_active=2,
                CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT(' ', round_texts.status_text), '')),
                CONCAT('<a href=\"election.php?ID=', elections.id, '\">', election_types.type_name, '</a>',
                    IF(elections.round_num>0, CONCAT(' ', round_texts.status_text), ''))
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
        " WHERE country=%s", $this->id);
		$sql .= " AND elections.is_active!=3
        AND election_types.id=elections.election_type
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
}
?>
