<?php

class ElectionView{
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
    
	function ElectionView($id) {
        $db = new Db;
        $sql = "SELECT parent,round_num FROM elections WHERE id=".$id;
        $db->Query($sql);
        $data = $db->fetchAssoc();
        if($data['parent']>0) {
            ob_end_clean();
            $add = ($_REQUEST['op']!='') ? '&op='.$_REQUEST['op'] : '';
            header('location: '.$_SERVER['PHP_SELF'].'?ID='.$data['parent'].$add);
            exit();  
        } else {
            $this->id = $id; 
        }
            
        $sql = "SELECT elections.*,
        DATE_FORMAT(elections.date_updated, '%m/%d/%Y') as date_updated,
        country.country_name,
        country.country_flag,
        country.official_name,
        election_types.type_name AS election_type_str,
        CONCAT(
            IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
            IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
            YEAR(election_date)) as election_date
        FROM elections, election_types
        LEFT JOIN country ON country.id=elections.country
        WHERE election_types.id=elections.election_type
        AND elections.id=".$this->id;
        //echo $sql;
        $db->Query($sql);
        $data = $db->fetchAssoc();
        foreach($data as $key => $value)
            $this->{$key} = $value;
            
        $this->round_text = '';
        $this->all_data = array();
        $this->all_stages = array();
        $this->all_stages_text = array();
        $texts = Common::get_round_options1();

        $sql = "SELECT id FROM elections
        WHERE (parent=".$this->id." OR id=".$this->id.") AND is_active!=3 AND show_results=1
        ORDER BY round_num DESC, order_date DESC";
        $db->Query($sql);
        while($stagedata = $db->fetchAssoc())
            $this->all_stages[] = $stagedata['id'];

        $sql = "SELECT elections.*,
        CONCAT( IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
            IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
            YEAR(election_date)) as election_date 
        FROM elections 
        WHERE parent=".$this->id." OR id=".$this->id."
        ORDER BY round_num ASC, order_date ASC";
        $db->Query($sql);

        while($stagedata = $db->fetchAssoc()) {
            $this->all_data[$stagedata['id']] = $stagedata;
            //$this->all_stages[] = $stagedata['id'];
            $this->all_stages_text[$stagedata['id']] = ($stagedata['round_num']>0) ? $texts[$stagedata['round_num']] : '';
            if ($stagedata['is_active']!=3)
                //$this->round_text .= $texts[$stagedata['round_num']].': '.$stagedata['election_date'].'<br />';
                $this->round_text .= ($texts[$stagedata['round_num']]!=''?$texts[$stagedata['round_num']].':':'').' '.$stagedata['election_date'].'<br />';
        }
        
        if(count($this->all_stages)>1)
            $this->multiple_results = TRUE;
	}


    function admin_links() {
        echo '<p style="text-align:right"><a href="preview.php?op=web&ID='.$this->id.'">Summary</a> | 
            <a href="preview.php?op=rez&ID='.$this->id.'">Results</a> | 
            <a href="preview.php?op=int&ID='.$this->id.'">Of Interest</a></p>';
    }
    
    function get_country() {
        echo '<table id="country-meta"><tbody><tr>'. $this->get_icon().'<td><ul>
		<li>Country: <a href="country.php?ID='.$this->country.'">'.$this->country_name.'</a>';
		if ($this->region!=0)
            echo '(Region: <a href="region.php?ID='.$this->region.'">'.Common::get_region_name($this->region).'</a>)';
        echo '</li>
			<li>Official Name: <a href="country.php?ID='.$this->country.'">'.$this->official_name.'</a></li>
			<li>Election Type: '.$this->election_type_str.'</li>
            <li>';
        echo  ($this->round_num>0 && $this->election_type!=4) ? $this->round_text : 'Date: '.$this->election_date;
        echo '</li></ul></td>
        </tr></tbody></table>';
    }
    
    function get_admin_country() {
        echo '<table id="country-meta"><tbody><tr>'. $this->get_admin_icon().'<td><ul>
		<li>Country: <a href="../country.php?ID='.$this->country.'">'.$this->country_name.'</a>';
		if ($this->region!=0)
            echo '(Region: <a href="../region.php?ID='.$this->region.'">'.Common::get_region_name($this->region).'</a>)';
        echo '</li>
			<li>Official Name: <a href="../country.php?ID='.$this->country.'">'.$this->official_name.'</a></li>
			<li>Election Type: '.$this->election_type_str.'</li>
            <li>';
        echo  ($this->round_num>0 && $this->election_type!=4) ? $this->round_text : 'Date: '.$this->election_date;
        echo '</li></ul></td>
        </tr></tbody></table>';
    } 
    
    function get_admin_icon() {
        $file = '../images/flags/'.$this->country_flag;
        if (file_exists($file) && is_file($file)) {
            $str= '<td>
                <img src="'.$file.'" alt="Flag of '.$this->country_name.'" style="width:100px;border:1px solid silver;" />
            </td>';
            return $str;
        }   
    }

    function get_admin_country_results() {
        echo '<table id="country-meta"><tbody><tr>'.$this->get_admin_icon().'<td><ul>
			<li><a href="../country.php?ID='.$this->country.'">'.$this->country_name.'</a></li>
			<li>'.$this->election_type_str.'</li>
            <li>';
        echo ($this->round_num>0 && $this->election_type!=4) ? $this->round_text : 'Date: '.$this->election_date;
        echo '</li></ul></td>
        </tr></tbody></table>';
    }
           
    function get_icon() {
        $file = 'images/flags/'.$this->country_flag;
        if (file_exists($file) && is_file($file)) {
            $str= '<td>
                <img src="'.$file.'" alt="Flag of '.$this->country_name.'" style="width:100px;border:1px solid silver;" />
            </td>';
            return $str;
        }   
    }

    function get_country_results() {
        echo '<table id="country-meta"><tbody><tr>'.$this->get_icon().'<td><ul>
			<li><a href="country.php?ID='.$this->country.'">'.$this->country_name.'</a></li>
			<li>'.$this->election_type_str.'</li>
            <li>';
        echo ($this->round_num>0 && $this->election_type!=4) ? $this->round_text : 'Date: '.$this->election_date;
        echo '</li></ul></td>
        </tr></tbody></table>';
    }
    
    function whats_at_stake() {
        if ($this->election_type==1) {
            $str ='<h3>At stake in this election:</h3>
            <ul><li>The office of president of '.$this->country_name.'</li></ul>';
            $this->bigarray['at_stake'] = 'The office of president of '.$this->country_name;
            
        } else if ($this->election_type==2 || $this->election_type==3) {
            $str ='<h3>At stake in this election:</h3>
            <ul>';
            if ($this->assembly_type=='uni') {
                if ($this->assembly_seats_at_stake>0) {
                    $atstake = '<li>'.$this->assembly_seats_at_stake.' seats in ';
                    $atstake .= $this->get_name($this->assembly_name, $this->assembly_name_foreign);
                    $atstake .= '.</li>';
                    $this->bigarray['at_stake'] = strip_tags($at_stake);
                }
            } else {
                $atstake = '';
                if ($this->uh_seats_at_stake>0) {
                    $atstake .= '<li>'.$this->uh_seats_at_stake.' seats in ';
                    $atstake .= $this->get_name($this->uh_name, $this->uh_name_foreign);
                    $atstake .= '.</li>';
                    $this->bigarray['at_stake'][] = $at_stake;
                }
                if ($this->lh_seats_at_stake>0) {    
                    $atstake .= '<li>'.$this->lh_seats_at_stake.' seats in ';
                    $atstake .= $this->get_name($this->lh_name, $this->lh_name_foreign);
                    $atstake .= '.</li>';
                    $this->bigarray['at_stake'][] = strip_tags($at_stake);
                }
            }
            $str .= $atstake.'</ul>';
        } else if ($this->election_type==4) {
            $str ='<h3>At stake in this Referendum:</h3><ul>
                <li>'.$this->election_summary.'</li>
            </ul>';
            $this->bigarray['at_stake'][] = $this->election_summary;
        }
        return $str;
    }
    
    function foreign($str) {
        //return trim(html_entity_decode($str));
        return trim($str);
    }
    
    function get_parl_comments() {
    	if ($this->assembly_comments!='')
    		echo '*'.nl2br($this->assembly_comments);
        $this->bigarray = array(
            'vote_comments' => $this->assembly_comments);
    }
          
    function view() {
        echo $this->whats_at_stake();
        $this->get_government();
        
        if ($this->election_type==1) {
            // presidential
            $this->get_electoral();
            if($this->multiple_results && $this->round_num<10 && $this->round_num>0) {
                foreach($this->all_stages as $id) {
                    echo $this->get_presidential_candidates($id);
                }  
                if ($this->last_election_notes!='')
                    echo $this->get_presidential_last_election();      
            } else {
                echo $this->get_presidential_candidates($this->id);
                if ($this->last_election_notes!='')
                    echo $this->get_presidential_last_election();
            }

        } else if ($this->election_type==2 || $this->election_type==3) {
            // parlimentary
            $this->get_electoral();  
            if($this->multiple_results && $this->round_num<10 && $this->round_num>0) {
                foreach($this->all_stages as $id) {
                    echo $this->get_parl_candidates($id);
                }
                if ($this->last_election_notes!='')
                    echo $this->get_parl_last_election();       
            } else {
                echo $this->get_parl_candidates($this->id);
                if ($this->last_election_notes!='')
                    echo $this->get_parl_last_election();
            }
        } else if ($this->election_type==4) {  
            if($this->multiple_results && $this->round_num<10 && $this->round_num>0) {
                foreach($this->all_stages as $id) {
                    echo $this->get_ref_candidates($id);
                }   
                if ($this->last_election_notes!='')
                    echo $this->get_ref_last_election();     
            } else {
                echo $this->get_ref_candidates($this->id);
                if ($this->last_election_notes!='')
                    echo $this->get_ref_last_election();
            }
        }
        $this->get_population();
        //$this->get_of_interest();
    }

    function get_of_interest() {
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
        $sql = $sfsql->SafeCompose("SELECT content FROM of_interest WHERE is_active=1 AND election=%i", $this->id);
        $db->Query($sql);
        if ($db->GetAffectedRows()>0) {
            echo '<h3>Of interest:</h3><ul>';
            while($data = $db->fetchAssoc()) {
                echo '<li>'.$data['content'].'</li>';
                $this->bigarray['ofinterest'][] = html_entity_decode($data['content']);
            }
            echo '</ul>';
        } else {
            echo '<p>Nothing available</p>';
        }
    }
    function results() {
        //$this->get_country_results();
        //$this->get_country_results();
        if ($this->show_results==1) {
            if ($this->election_type==1) {
                if($this->multiple_results && $this->round_num<10 && $this->round_num>0) {
                    foreach($this->all_stages as $id) {
                        echo $this->get_presidential_results($id);
                    }
                } else {
                    echo $this->get_presidential_results($this->id);
                }
            } else if ($this->election_type==2 || $this->election_type==3) {
                // parliamentary and legislative
                if($this->multiple_results && $this->round_num<10 && $this->round_num>0) {
                    foreach($this->all_stages as $id) {
                        echo $this->get_parl_results($id);
                    }        
                } else {
                  echo $this->get_parl_results($this->id);
                }
            } else if ($this->election_type==4) {
                // referendum
                if($this->multiple_results && $this->round_num<10 && $this->round_num>0) {
                    foreach($this->all_stages as $id) {
                        echo $this->get_ref_results($id);
                    }        
                } else {
                  echo $this->get_ref_results($this->id);
                }
            }
        } else {
            echo '<br /><p>No results posted yet.</p>';
        }
    }

    function get_dbresults() {       
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
		
        $sql = "SELECT groups.id,
        groups.group_name,
        groups.assembly_type,
        IF(groups.seats='-1', 'N/A', groups.seats) as seats,
        groups.term,
        CONCAT(election_ways.the_way, ' ', IF(assembly_ways.the_way='n/a', '', assembly_ways.the_way)) as elected_by,
        groups.group_comments
        FROM groups
        LEFT JOIN assembly_ways ON assembly_ways.id=groups.election_type
        LEFT JOIN election_ways ON election_ways.id=groups.elected_by";
		
		$sql .= $sfsql->SafeCompose(
        " WHERE groups.is_active=1 AND groups.election=%i", $this->id);
		$sql .= " ORDER BY groups.seats DESC";

        $db->Query($sql);
        while($data = $db->fetchAssoc()) {
            $groups[$data['assembly_type']][$data['id']] = $data;
        }
        return $groups;
    }
    
    function get_dbresults1() {       
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
		
        $sql = "SELECT groups.id,
        groups.group_name,
        groups.assembly_type,
        IF(groups.seats='-1', 'N/A', groups.seats) as seats,
        groups.term,
        TRIM(election_ways.the_way) as elected,
        TRIM(assembly_ways.the_way) as elected_by,
        groups.group_comments
        FROM groups
        LEFT JOIN assembly_ways ON assembly_ways.id=groups.election_type
        LEFT JOIN election_ways ON election_ways.id=groups.elected_by ";
		$sql .= $sfsql->SafeCompose(
        " WHERE groups.is_active=1 AND groups.election=%i", $this->id);
		$sql .= " ORDER BY groups.seats DESC";

        $db->Query($sql);
        while($data = $db->fetchAssoc()) {
            $key = $data['id'];
            foreach($data as $k => $v)
                $groups[$key.':'.$k] = html_entity_decode($v);
        }
        return $groups;
    }

    function get_gen_results($array) {

        foreach($array as $key => $value)
            $this->{$key} = $value;
    	$rmonth = ($this->reg_voters_month==0) ? '': Common::get_months($this->reg_voters_month);
        $regv = ($this->reg_voters<0) ? 'N/A': number_format($this->reg_voters);
        
        $votes_cast = ($this->votes_cast<0) ? 'N/A': number_format($this->votes_cast);
        $votes_cast_p = ($this->votes_cast_percentage<0)
            ? ' [NA% of registered voters]'
            : ' ['.$this->votes_cast_percentage.'% of registered voters]';
        
        $votes_valid = ($this->votes_valid<0) ? 'N/A': number_format($this->votes_valid);
        $votes_valid_p = ($this->votes_valid_percentage<0)
            ? ' [NA% of votes cast]'
            : ' ['.$this->votes_valid_percentage.'% of votes cast]';
        
        $votes_invalid = ($this->votes_invalid<0) ? 'N/A': number_format($this->votes_invalid);
        $votes_invalid_p = ($this->votes_invalid_percentage<0)
            ? ' [NA% of votes cast]'
            : ' ['.$this->votes_invalid_percentage.'% of votes cast]';

        $regs = '';
        if ($rmonth!='')
            $regs .= $rmonth.' ';
        if ($this->reg_voters_year>0)
            $regs .= $this->reg_voters_year;
        $regs .= (trim($this->reg_voters_comments)!='') ? $this->reg_voters_comments : '';
        $reg = (trim($regs)!='') ? '('.html_entity_decode($regs).')' : '';
        echo '
        <table class="main-results-table"><tbody>
            <tr>
                <th>Registered Voters: </th>
                <td class="right">'.$regv.'</td>
                <td>'.$reg.'</td>
            </tr>
            <tr>
                <th>Votes Cast: </th>
                <td class="right">'.$votes_cast.'</td>
                <td>'.$votes_cast_p.'</td>
            </tr>
            <tr>
                <th>Valid Votes: </th>
                <td class="right">'.$votes_valid.'</td>
                <td>'.$votes_valid_p.'</td>
            </tr>
            <tr>
                <th>Invalid Votes: </th>
                <td class="right">'.$votes_invalid.'</td>
                <td>'.$votes_invalid_p.'</td>
            </tr></tbody></table>';
    }
    
    function get_gen_prez_results($array) {
        foreach($array as $key => $value)
            $this->{$key} = $value;
    	$rmonth = ($this->reg_voters_month==0) ? '': Common::get_months($this->reg_voters_month);
        $regv = ($this->reg_voters<0) ? 'N/A': number_format($this->reg_voters);
            
        $votes_cast = ($this->votes_cast<0) ? 'N/A': number_format($this->votes_cast);
        $votes_cast_p = ($this->votes_cast_percentage<0)
            ? ' [NA% of registered voters]'
            : ' ['.$this->votes_cast_percentage.'% of registered voters]';

        $votes_valid = ($this->votes_valid<0) ? 'N/A': number_format($this->votes_valid);
        $votes_valid_p = ($this->votes_valid_percentage<0)
            ? ' [NA% of votes cast]'
            : ' ['.$this->votes_valid_percentage.'% of votes cast]';

        $votes_invalid = ($this->votes_invalid<0) ? 'N/A': number_format($this->votes_invalid);
        $votes_invalid_p = ($this->votes_invalid_percentage<0)
            ? ' [NA% of votes cast]'
            : ' ['.$this->votes_invalid_percentage.'% of votes cast]';

        $regs = '';
        if ($rmonth!='')
            $regs .= $rmonth.' ';
        if ($this->reg_voters_year>0)
            $regs .= $this->reg_voters_year;
        $regs .= (trim($this->reg_voters_comments)!='') ? $this->reg_voters_comments : '';
        $reg = (trim($regs)!='') ? '('.html_entity_decode($regs).')' : '';
        
        echo '
        <table class="main-results-table"><tbody>
            <tr>
                <th>Registered Voters: </th>
                <td class="right">'.$regv.'</td>
                <td>'.$reg.'</td>
            </tr>
            <tr>
                <th>Votes Cast: </th>
                <td class="right">'.$votes_cast.'</td>
                <td>'.$votes_cast_p.'</td>
            </tr>
            <tr>
                <th>Valid Votes: </th>
                <td class="right">'.$votes_valid.'</td>
                <td>'.$votes_valid_p.'</td>
            </tr>
            <tr>
                <th>Invalid Votes: </th>
                <td class="right">'.$votes_invalid.'</td>
                <td>'.$votes_invalid_p.'</td>
            </tr></tbody></table>';
        if (trim($this->valid_comments)!='')
            echo '<p class="comment">* '.nl2br(html_entity_decode($this->valid_comments)).'</p>';
        else
            echo '<br />';

    }

    function get_lower_results($array) {
        foreach($array as $key => $value)
            $this->{$key} = $value;
    	$rmonth = ($this->reg_voters_month==0) ? '': Common::get_months($this->reg_voters_month);
        $regv = ($this->reg_voters<0) ? 'N/A': number_format($this->reg_voters);

        $votes_cast = ($this->votes_cast1<0) ? 'N/A': number_format($this->votes_cast1);
        $votes_cast_p = ($this->votes_cast1_percentage<0)
            ? ' [NA% of registered voters]'
            : ' ['.$this->votes_cast1_percentage.'% of registered voters]';

        $votes_valid = ($this->votes_valid1<0) ? 'N/A': number_format($this->votes_valid1);
        $votes_valid_p = ($this->votes_valid1_percentage<0)
            ? ' [NA% of votes cast]'
            : ' ['.$this->votes_valid1_percentage.'% of votes cast]';

        $votes_invalid = ($this->votes_invalid1<0) ? 'N/A': number_format($this->votes_invalid1);
        $votes_invalid_p = ($this->votes_invalid1_percentage<0)
            ? ' [NA% of votes cast]'
            : ' ['.$this->votes_invalid1_percentage.'% of votes cast]';
        
        $regs = '';
        if ($rmonth!='')
            $regs .= $rmonth.' ';
        if ($this->reg_voters_year>0)
            $regs .= $this->reg_voters_year;
        $regs .= (trim($this->reg_voters_comments)!='') ? $this->reg_voters_comments : '';
        $reg = (trim($regs)!='') ? '('.html_entity_decode($regs).')' : '';
        echo '
        <table class="main-results-table"><tbody>
        <tr>
            <th>Registered Voters: </th>
            <td class="right">'.$regv.'</td>
            <td>'.$reg.'</td>
        </tr>
        <tr>
            <th>Votes Cast: </th>
            <td class="right">'.$votes_cast.'</td>
            <td>'.$votes_cast_p.'</td>
        </tr>
        <tr>
            <th>Valid Votes: </th>
            <td class="right">'.$votes_valid.'</td>
            <td>'.$votes_valid_p.'</td>
        </tr>
        <tr>
            <th>Invalid Votes: </th>
            <td class="right">'.$votes_invalid.'</td>
            <td>'.$votes_invalid_p.'</td>
        </tr></tbody>
        </table>';
    }

    function get_presidential_results($election=0) {
        $id = ($election==0) ? $this->id : $election;
        $commnote = (trim($this->all_data[$id]['valid_comments'])!='') ? ' *' : '';
        
        echo ($this->round_num>0 && $this->round_num<10)
            ? '<h3 class="title">Results of the '.$this->all_stages_text[$id].$commnote.'</h3>'
            : '<h3 class="title">Results'.$commnote.'</h3>';
            
        $this->get_gen_prez_results($this->all_data[$id]);
        
        //echo (trim($this->all_data[$id]['valid_comments'])!='')
        //    ? '<p class="comment">* '.trim(html_entity_decode($this->all_data[$id]['valid_comments'])).'</p>'
        //    : '<br />';

        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
		
        $sql = "SELECT candidates_pres.order_id,
        candidates_pres.candidate_party AS party_name,
        IF(candidates_pres.party_name_foreign!='', CONCAT('<em>', candidates_pres.party_name_foreign, '</em>'),'') AS party_name_foreign,
        candidates_pres.party_url,
        candidates_pres.party_acronym,
        candidates_pres.candidate_votes,
        candidates_pres.candidate_votes_percentage,
        IF(candidates_pres.is_winner=1, '<img src=\"images/active_1.gif\" alt=\"Winner\" />', '') AS winner,
        IF(candidates_pres.party_acronym_foreign!='',
            CONCAT('<em>', candidates_pres.party_acronym_foreign, '</em>'),
            ''
        ) AS party_acronym_foreign,
        candidates_pres.candidate_fname,
        candidates_pres.candidate_lname,
        IF(TRIM(candidates_pres.votes_comments)='',
            '',
            CONCAT('<br />', REPEAT('*', candidates_pres.order_id), ' ', candidates_pres.votes_comments)
        ) AS votes_comments,
        IF(TRIM(candidates_pres.votes_comments)='',
            '',
            REPEAT('*',candidates_pres.order_id)
        ) AS votes_comments_note,
        IF(party_types.party_type IS NULL, 'Party', party_types.party_type) AS party_type,
        elections.round_num
        FROM candidates_pres
        LEFT JOIN elections ON elections.id=candidates_pres.election
        LEFT JOIN party_types ON party_types.id=candidates_pres.party_type ";
		$sql .= $sfsql->SafeCompose(
        " WHERE candidates_pres.is_active=1 AND candidates_pres.election=%i", $id);
        $sql .= " AND elections.is_active!=3
        ORDER BY candidates_pres.order_id ASC";
		
        $db->Query($sql);
        if($db->GetAffectedRows()>0) {
            echo '<table class="results-table"><thead><tr>
                <th class="candidates">Candidates</th>
                <th>Party</th>
                <th>Valid Votes</th>
                <th>% [of Valid Votes]</th>
            </tr></thead><tbody>';
            $totalcomments = '';
        
            while($cdata = $db->fetchAssoc()) {
                echo '<tr>';
            
                $partyname = $this->get_party_name($cdata['candidate_party'], $cdata['party_name_foreign'],$cdata['party_acronym'], $cdata['party_acronym_foreign']);
                echo '<th class="candidates">';
                echo html_entity_decode($cdata['candidate_fname']).' '.html_entity_decode($cdata['candidate_lname']);
                echo $cdata['winner'].' '.$cdata['votes_comments_note'];
                echo '</th><td>';
                $totalcomments .= $cdata['votes_comments'];
                $partyname='';
                if ($cdata['party_name']!='')
                    $partyname .= $cdata['party_name'];

                if ($cdata['party_acronym']!='')
                    $partyname .= ' '.$cdata['party_acronym'];

                if ($cdata['party_name_foreign']!='')
                    $partyname .= ' '.$cdata['party_name_foreign'];

                if ($cdata['party_name_foreign']!='')
                    $partyname .= ' '.$cdata['party_acronym_foreign'];
                echo ($partyname!='') ? html_entity_decode($partyname) : 'N/A';
                $votes = ($cdata['candidate_votes']>=0) ? number_format($cdata['candidate_votes']) : 'N/A';
                $percentage = ($cdata['candidate_votes_percentage']>=0) ? $cdata['candidate_votes_percentage'].'%' : 'N/A';
                echo '</td>
                <td class="right">'.$votes.'</td>
                <td class="centered">'.$percentage.'</td></tr>';
            }
            echo '</tr></tbody></table>';
        } else {
            echo '<p>No Candidates available</p>';
        }
        echo(trim($totalcomments)!='') ? '<p class="comment">'.trim(html_entity_decode($totalcomments), '<br />').'</p>' : '<br />';
    }


    function get_vote_percentage($votes, $percentage) {
        return ($percentage!=0) ? $percentage : round(($votes/$this->votes_valid)*100,2);
    }
    
     
    function get_parl_results($election=0) {
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
		
        $id = ($election==0) ? $this->id : $election;
        $sql = "SELECT candidates.id,
        candidates.order_id,
        candidates.party_name,
        candidates.party_votes,
        candidates.party_votes_percentage,
        candidates.party_seats_won,
        candidates.party_votes1,
        candidates.party_votes1_percentage,
        candidates.party_seats1_won,
        IF(candidates.is_winner=1, '<img src=\"images/active_1.gif\" alt=\"Winner\" />', '') AS winner,
        IF(candidates.party_name_foreign!='',CONCAT('<em>', candidates.party_name_foreign, '</em>'),'') AS party_name_foreign,
        candidates.party_url,
        IF(candidates.party_seats='-1', 'N/A', candidates.party_seats) as party_seats,
        IF(candidates.party_seats1='-1', 'N/A', candidates.party_seats1) as party_seats1,
        candidates.party_acronym,
        IF(candidates.party_acronym_foreign!='',CONCAT('<em>', candidates.party_acronym_foreign, '</em>'),'') AS party_acronym_foreign,
        IF(candidates.party_leader_fname='' && candidates.party_leader_lname='',
            'N/A',
            CONCAT(candidates.party_leader_fname, ' ', candidates.party_leader_lname)
        ) AS party_leader,
        IF(TRIM(candidates.votes_comments)='',
            '',
            CONCAT('<br />', REPEAT('*', candidates.order_id), ' ', candidates.votes_comments)
        ) AS votes_comments,
        IF(candidates.votes_comments!='',
            REPEAT('*', candidates.order_id),
            ''
        ) AS votes_comments_note,
        IF(TRIM(candidates.votes_comments1)='',
            '',
            CONCAT('<br />', REPEAT('*', candidates.order_id), ' ', candidates.votes_comments1)
        ) AS votes_comments1,
        IF(candidates.votes_comments1!='',
            REPEAT('*', candidates.order_id),
            ''
        ) AS votes_comments1_note,
        party_types.party_type,
        elections.round_num
        FROM candidates
        LEFT JOIN elections ON elections.id=candidates.election
        LEFT JOIN party_types ON party_types.id=candidates.party_type ";
		
		$sql .= $sfsql->SafeCompose(
        "WHERE candidates.is_active=1 AND candidates.election=%i", $id);
		$sql .= " AND elections.is_active!=3
        ORDER BY order_id ASC";
        //echo $sql;
        $db->Query($sql);
        while($data = $db->fetchAssoc()) {
            $newdata[$data['id']] = $data;
        }
        $texts = Common::get_round_options();
        $addon = ($this->round_num<10 && $this->all_stages_text[$id]!='')
            ? 'for '.$this->all_stages_text[$id].' for '
            : 'for ';
        

        if (is_array($newdata)) {
        if ($this->assembly_type=='bi') {

            // upper house results
            $totalcomments = '';
            if ($this->uh_seats_at_stake!=0 && $this->show_uh_results==1) {
                $this->complete_name = $this->get_name($this->uh_name, $this->uh_name_foreign);
                $commnote = (trim($this->valid_comments)!='') ? ' *' : '';
                echo '<h3 class="results">Results '.$addon.$this->complete_name.$commnote.'</h3>';
                $this->get_gen_results($this->all_data[$id]);
                echo(trim($this->valid_comments)!='')
                    ? '<p class="comment">* '.trim(html_entity_decode($this->valid_comments)).'</p>'
                    : '<br />';
                echo '<table class="results-table"><thead>
                <tr>
                    <th class="party_candidates">Party</th>
                    <th class="right">Valid Votes</th>
                    <th class="centered votes">% [of Valid Votes]</th>
                    <th class="right votes">Seats</th>
                </tr></thead>
                <tbody>';
                foreach($newdata as $key => $cdata) {
                    $totalcomments .= $cdata['votes_comments'];
                    $pname = $this->get_party_name($cdata['party_name'], $cdata['party_name_foreign'], $cdata['party_acronym'], $cdata['party_acronym_foreign']);
                    $votes = ($cdata['party_votes']<0) ? 'N/A' : number_format($cdata['party_votes']);
                    $seats = ($cdata['party_seats_won']<0) ? 'N/A' : number_format($cdata['party_seats_won']);
                    $percentage = ($cdata['party_votes_percentage']<0) ? 'N/A' : $cdata['party_votes_percentage'].'%';
                    echo '<tr>
                        <th class="party_candidates">'.$pname.$cdata['winner'].' '.$cdata['votes_comments_note'].'</th>
                        <td class="right">'.$votes.'</td>
                        <td class="centered votes">'.$percentage.'</td>
                        <td class="right votes">'.$seats.'</td>
                    </tr>';
                }
                echo '</tbody></table>';
                echo(trim($totalcomments)!='') ? '<p class="comment">'.trim(html_entity_decode($totalcomments), '<br />').'</p>' : '<br />';

            }
            
            $totalcomments = '';
            //lower house results
            if ($this->lh_seats_at_stake>0 && $this->show_lh_results==1) {
                $this->complete_name = $this->get_name($this->lh_name, $this->lh_name_foreign);
                $commnote = (trim($this->valid1_comments)!='') ? ' *' : '';
                echo '<h3 class="results">Results '.$addon.$title.$this->complete_name.$commnote.'</h3>';
                $this->get_lower_results($this->all_data[$id]);
                echo(trim($this->valid1_comments)!='')
                    ? '<p class="comment">* '.trim(html_entity_decode($this->valid1_comments)).'</p>'
                    : '<br />';
                    
                echo '<table class="results-table"><thead><tr>
                    <th class="party_candidates">Party</th>
                    <th class="right">Valid Votes</th>
                    <th class="votes">% [of Valid Votes]</th>
                    <th class="right votes">Seats</th>
                </tr></thead>
                <tbody>';
                foreach($newdata as $key => $cdata) {
                    $totalcomments .= $cdata['votes_comments1'];
                    $pname = $this->get_party_name($cdata['party_name'], $cdata['party_name_foreign'], $cdata['party_acronym'], $cdata['party_acronym_foreign']);
                    $votes = ($cdata['party_votes1']<0) ? 'N/A' : number_format($cdata['party_votes1']);
                    $seats = ($cdata['party_seats1_won']<0) ? 'N/A' : number_format($cdata['party_seats1_won']);
                    $percentage = ($cdata['party_votes1_percentage']<0) ? 'N/A' : $cdata['party_votes1_percentage'].'%';
                    //$comm =  (trim($cdata['party_comments'])!='') ? '* ' : '';
                    echo '<tr>
                        <th class="party_candidates">'.$pname.$cdata['winner'].' '.$cdata['votes_comments1_note'].'</th>
                        <td class="right">'.$votes.'</td>
                        <td class="centered votes">'.$percentage.'</td>
                        <td class="right votes">'.$seats.'</td>
                    </tr>';
                }
                echo '</tbody></table>';
                echo(trim($totalcomments)!='') ? '<p class="comment">'.trim(html_entity_decode($totalcomments), '<br />').'</p>' : '<br />';
            }
        } else {
            $totalcomments ='';
            $this->complete_name = $this->get_name($this->assembly_name, $this->assembly_name_foreign);
            $commnote = (trim($this->valid_comments)!='') ? ' *' : '';
            echo '<h3 class="results">Results '.$addon.$this->complete_name.$commnote.'</h3>';
            $this->get_gen_results($this->all_data[$id]);
            echo(trim($this->valid_comments)!='')
                ? '<p class="comment">* '.trim(html_entity_decode($this->valid_comments)).'</p>'
                : '<br />';
            echo '<table class="results-table"><thead>
                <tr>
                    <th class="party_candidates">Party</th>
                    <th class="right">Valid Votes</th>
                    <th class="votes">% [of Valid Votes]</th>
                    <th class="right votes">Seats</th>
                </tr></thead>
                <tbody>';
                foreach($newdata as $key => $cdata) {
                    $totalcomments .= $cdata['votes_comments'];
                    $pname = $this->get_party_name($cdata['party_name'], $cdata['party_name_foreign'], $cdata['party_acronym'], $cdata['party_acronym_foreign']);
                    $votes = ($cdata['party_votes']<0) ? 'N/A' : number_format($cdata['party_votes']);
                    $seats = ($cdata['party_seats_won']<0) ? 'N/A' : number_format($cdata['party_seats_won']);
                    $percentage = ($cdata['party_votes_percentage']<0) ? 'N/A' : $cdata['party_votes_percentage'].'%';
                    //$comm =  (trim($cdata['party_comments'])!='') ? '* ' : '';
                    echo '<tr>
                        <th class="party_candidates">'.$pname.$cdata['winner'].' '.$cdata['votes_comments_note'].'</th>
                        <td class="right">'.$votes.'</td>
                        <td class="centered votes">'.$percentage.'</td>
                        <td class="right votes">'.$seats.'</td>
                    </tr>';
                }
                echo '</tbody></table>';
                echo(trim($totalcomments)!='') ? '<p class="comment">'.trim(html_entity_decode($totalcomments), '<br />').'</p>' : '<br />';
        }
        } else {
            echo '<p>No Candidates available</p>';
        }
    }
    
    function get_number($num) {
        return ($num<0) ? 'N/A': $num;
    }
    
    function get_ref_results($show=TRUE) {

        //$votes_cast = ($this->votes_cast<0) ? 'N/A': number_format($this->votes_cast);
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
		
        $sql = "SELECT candidates_ref.order_id,
        candidates_ref.provision_desc,
        IF(candidates_ref.provision_desc_foreign!='',CONCAT('<em>', candidates_ref.provision_desc_foreign, '</em>'),'') AS provision_desc_foreign,
        candidates_ref.votes_valid,
        candidates_ref.votes_valid_percentage,
        candidates_ref.votes_invalid,
        candidates_ref.votes_invalid_percentage,
        candidates_ref.votes_cast,
        candidates_ref.votes_cast_percentage,
        candidates_ref.yes_votes,
        candidates_ref.yes_votes_percentage,
        candidates_ref.no_votes,
        candidates_ref.no_votes_percentage,
        candidates_ref.provision_at_stake,
        candidates_ref.provision_summary,
        candidates_ref.provision_req,
        IF(TRIM(candidates_ref.votes_comments)='',
        '',
            CONCAT('<br />', REPEAT('*', candidates_ref.order_id), ' ', candidates_ref.votes_comments)
        ) AS votes_comments,
        IF(candidates_ref.votes_comments!='',
            REPEAT('*', candidates_ref.order_id),
            ''
        ) as votes_comments_note,
        elections.reg_voters,
        elections.reg_voters_month,
        elections.reg_voters_year,
        IF(elections.reg_voters_comments='', '', elections.reg_voters_comments) AS reg_voters_comments,
        elections.round_num
        FROM candidates_ref
        LEFT JOIN elections ON elections.id=candidates_ref.election
        WHERE candidates_ref.is_active=1 ";
		$sql .= $sfsql->SafeCompose(
        " AND candidates_ref.election=%i", $this->id);
		$sql .= 
        " AND elections.is_active!=3
        ORDER BY order_id ASC";
		
        $db->Query($sql);
        $numrows = $db->GetAffectedRows();
        if ($numrows>0) {
        $totalcomments = '';
        $texts = Common::get_round_options();
        $x=1;
        while($cdata = $db->fetchAssoc()) {
        	$rmonth = ($cdata['reg_voters_month']==0) ? '': Common::get_months($cdata['reg_voters_month']);
            $regv = ($cdata['reg_voters']<0) ? 'N/A' : number_format($cdata['reg_voters']);

            $regs = '';
            
            if ($rmonth!='')
                $regs .= $rmonth.' ';
                
            if ($cdata['reg_voters_year']>0)
                $regs .= $cdata['reg_voters_year'];
                
            $regs .= (trim($cdata['reg_voters_comments'])!='') ? $cdata['reg_voters_comments'] : '';
            $reg = (trim($regs)!='') ? '('.html_entity_decode($regs).')' : '';

            //$reg = ($cdata['reg_voters']<0) ? '' : $reg;
            $votes_valid = ($cdata['votes_valid']<0) ? 'N/A' : number_format($cdata['votes_valid']);
            $votes_valid_p = ($cdata['votes_valid_percentage']<0)
                ? '[NA% of votes cast]'
                : '['.$cdata['votes_valid_percentage'].'% of votes cast]';
            
            $votes_invalid = ($cdata['votes_invalid']<0) ? 'N/A' : number_format($cdata['votes_invalid']);
            $votes_invalid_p = ($cdata['votes_invalid_percentage']<0)
                ? '[NA% of votes cast]'
                : '['.$cdata['votes_invalid_percentage'].'% of votes cast]';
            
            $votes_cast = ($cdata['votes_cast']<0) ? 'N/A' : number_format($cdata['votes_cast']);
            $votes_cast_p = ($cdata['votes_cast_percentage']<0)
                ? '[NA% of registered voters]'
                : '['.$cdata['votes_cast_percentage'].'% of registered voters]';
            
            $yvotes = ($cdata['yes_votes']<0) ? 'N/A' : number_format($cdata['yes_votes']);
            $nvotes = ($cdata['no_votes']<0) ? 'N/A' : number_format($cdata['no_votes']);
            $percentagey = ($cdata['yes_votes_percentage']<0) ? 'N/A' : $cdata['yes_votes_percentage'].'%';
            $percentagen = ($cdata['no_votes_percentage']<0) ? 'N/A' : $cdata['no_votes_percentage'].'%';
            
            $commnote = (trim($cdata['votes_comments'])!='') ? ' *' : '';
            //$text = (trim($cdata['provision_desc'])=='') ? trim($cdata['provision_at_stake']) : trim($cdata['provision_desc']);
            $title = ($numrows==1) ? 'Results': 'Results for Provision '.$x;
            $title = ($cdata['round_num']>0 && $cdata['round_num']<10) ? $texts[$cdata['round_num']].$title : $title;
            echo '<h3 class="provisiontitle">'.$title.$commnote.'</h3>
            <p class="refdesc">'.trim(html_entity_decode($cdata['provision_at_stake'])).'</p>
            <table class="main-results-table"><tbody>
            <tr>
                <th>Registered Voters: </th>
                <td class="right">'.$regv.'</td>
                <td>'.$reg.'</td>
            </tr>
            <tr>
                <th>Votes Cast: </th>
                <td class="right">'.$votes_cast.'</td>
                <td>'.$votes_cast_p.'</td>
            </tr>
            <tr>
                <th>Valid Votes: </th>
                <td class="right">'.$votes_valid.'</td>
                <td>'.$votes_valid_p.'</td>
            </tr>
            <tr>
                <th>Invalid Votes: </th>
                <td class="right">'.$votes_invalid.'</td>
                <td>'.$votes_invalid_p.'</td>
            </tr>            
            </tbody></table><br />
            <table class="results-table"><thead><tr>
                <th class="candidates">Type of Vote</th>
                <th>Valid Votes</th>
                <th>% [of Valid Votes]</th>
            </tr></thead>
            <tbody>
            <tr>
                <td>\'Yes\' Votes</td>
                <td class="centered">'.$yvotes.'</td>
                <td class="centered">'.$percentagey.'</td>
            </tr>
            <tr>
                <td>\'No\' Votes</td>
                <td class="centered">'.$nvotes.'</td>
                <td class="centered">'.$percentagen.'</td>
            </tr></tbody></table>';
            
            echo (trim($cdata['votes_comments'])!='')
                ? '<p class="comment">'.html_entity_decode(trim($cdata['votes_comments'],'<br />')).'</p>'
                : '<br />';
            $x++;
          }
          } else {
                echo '<p>No Provisions available</p>';
          }
          if (trim($this->valid_comments)!='')
            echo '<p class="comments_border"><strong>Note</strong>: '.html_entity_decode($this->valid_comments).'</p>';
    }

	function get_party_name($home, $foreign, $shorthome, $shortforeign) {
        $home = trim(html_entity_decode($home));
        $foreign = trim(html_entity_decode($foreign));
        $shorthome = trim(html_entity_decode($shorthome));
        $shortforeign = trim(html_entity_decode($shortforeign));

        $short1 = (trim($shorthome)!='') ? ' ('.$shorthome.')': '';
        $short2 = (trim($shortforeign)!='') ? ' ('.$shortforeign.')': '';
        $str = (trim($home)!='')
            ? $home.$short1.((trim($foreign!=''))? '/ '.$foreign.''.$short2: '')
            : trim($foreign).$short2;
        return $str;
    }

	function get_name($home, $foreign) {
        $home = trim(html_entity_decode($home));
        $foreign = '<em>'.trim($foreign).'</em>';
        $foreign = trim(html_entity_decode($foreign));
        $str = (trim($home)!='') ? $home : trim($foreign);
        return $str;
    }
    
    function upr($str) {
        $entities = get_html_translation_table (HTML_ENTITIES);
        $specialchars = get_html_translation_table (HTML_SPECIALCHARS);

        foreach($entities as $key => $value){
           if (!in_array($value, $specialchars)) {
                $str = str_replace($value, $key, $str);
           }
        }
        return mb_strtoupper($str);
    }

    function print_popup_header($width, $title, $showclientname = TRUE) {
        if ($showclientname == TRUE) {
            $header_text = CLIENT_NAME;
            if ($title!='')
                $header_text .= ' - '.$title;
        } else if ($title!='') {
           $header_text = $title;
        } else {
           $header_text = CLIENT_NAME;
        }
        echo '<div id="main">
        <div id="topbar">
            <div class="ipstitle">'.$header_text.'</div>
            <div class="ipslogout"><a href="javascript:window.print();">Print</a> | <a href="javascript:window.close();">Close Window</a></div>
        </div>
        <div id="mainarea" class="poparea">';
    }

    function print_popup_footer() {
        echo '</div></div> ';
    }
}
?>
