<?php

class CountryView extends ElectionView {
    var $id         = '';
    var $table      = '';
    var $bigarray   = array();
    
	function CountryView($id) {
        $this->id = $id;
        $db = new Db;
		$sfsql = & new SafeSQL_MySQL();
		$sql = $sfsql->SafeCompose(
        "SELECT country.* FROM country WHERE country.id=%i", $this->id);
        $db->Query($sql);
		$data = $db->fetchAssoc();
        foreach($data as $key => $value)
            $this->{$key} = $value;
	}

    function view() {
        $this->bigarray = array(
            'country_id' => $this->id,
            'country_name'  =>$this->country_name,
            'official_name' =>$this->official_name,
            'last_election' =>$this->last_election_notes);
              
        $this->get_government();
        $this->get_electoral();
        $this->get_population_no_voters();
        $this->get_future_elections();
        $this->get_last_elections();
        //$this->get_links();
    } 
       
    function get_population_no_voters() {
    	$month = ($this->population_month==0) ? '': Common::get_months($this->population_month);
        $year = ($this->population_year==0) ? '': $this->population_year;
        $popv = ($this->population<0) ? 'N/A': number_format($this->population).'.';
        $popfinal = trim($month.' '.$year);
        $pop = ($popfinal=='') ? '' : '('.$popfinal.')';
        
        echo '<h3>Population:</h3><ul>
            <li>Population: '.$popv.' '.$pop.' '.$this->population_comments.'</li>';
        echo '</ul>';
    }
    
    function get_dbresults() {      
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
        $sql = "SELECT 
        curr_groups.id, 
        curr_groups.group_name, 
        curr_groups.assembly_type, 
        curr_groups.seats, 
        curr_groups.term,
        CONCAT(election_ways.the_way, ' ', IF(assembly_ways.the_way='n/a', '', assembly_ways.the_way)) as elected_by,
        curr_groups.is_active
        FROM curr_groups
        LEFT JOIN assembly_ways ON assembly_ways.id=curr_groups.election_type
        LEFT JOIN election_ways ON election_ways.id=curr_groups.elected_by
        WHERE curr_groups.is_active=1 ";
		$sql .= $sfsql->SafeCompose(
        " AND curr_groups.country=%i", $this->id);
		$sql .=
        " ORDER BY curr_groups.seats DESC";

        $db->Query($sql);
        while($data = $db->fetchAssoc()) {
            $groups[$data['assembly_type']][$data['id']] = $data;
        }
        return $groups;
    }
    function get_dbresults1() {      
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
        
        $sql = "SELECT 
        curr_groups.id, 
        curr_groups.group_name, 
        curr_groups.assembly_type, 
        curr_groups.seats, 
        curr_groups.term,
        TRIM(election_ways.the_way) as elected,
        TRIM(assembly_ways.the_way) as elected_by,
        curr_groups.group_comments
        FROM curr_groups
        LEFT JOIN assembly_ways ON assembly_ways.id=curr_groups.election_type
        LEFT JOIN election_ways ON election_ways.id=curr_groups.elected_by
        WHERE curr_groups.is_active=1";
		$sql .= $sfsql->SafeCompose(
        " AND curr_groups.country=%i", $this->id);
		$sql .=
        " ORDER BY curr_groups.seats DESC";

        $db->Query($sql);
        while($data = $db->fetchAssoc()) {
            $key = $data['id'];
            foreach($data as $k => $v)
                $groups[$key.':'.$k] = $v;
        }
        return $groups;
    }   
    function get_electoral() {
    	$chiefterm =  ($this->chief_term==0) ? '' : ' to serve '.Common::get_num_textual($this->chief_term).'-year term';
	    $headterm =  ($this->chief_term==0) ? '' : ' to serve '.Common::get_num_textual($this->head_term).'-year term';
	    
    	$el = Common::get_election_byway($this->chief_electedby);
    	$el1 = Common::get_election_byway($this->head_electedby);
    	
    	$tp = Common::get_election_type_way($this->chief_election_type);
    	$tp1 = Common::get_election_type_way($this->head_election_type);
    		
        echo '<h3>Description of electoral system:</h3><ul>';
        if (strtolower(trim($this->chief_title))!='queen' && strtolower(trim($this->chief_title))!='king' && strtolower(trim($this->chief_title))!='emperor') {
            echo '<li>'.ucfirst($this->chief_title.($el=='N/A'?' To Be Determined':' is '.$el).($tp=='N/A'?'':' '.$tp).$chiefterm.'.');
        	echo (trim($this->chief_comments)!='') ? '<br />*'.$this->chief_comments :'';
        	echo '</li>';
    	}
        echo '<li>';
            
        $this->bigarray['electoral'] = array(
            'chief' => ucfirst($this->chief_title.($el=='N/A'?' To Be Determined':' is '.$el).($tp=='N/A'?'':' '.$tp).$chiefterm.'.'),
            'chief_comments' => $this->chief_comments);
            
        
    if ($this->use_custom==1) {
        $groups = $this->get_dbresults1();
        $lh_name = $this->lh_name;
        $lh_name_foreign = $this->lh_name_foreign;
        $custom = $this->custom_content;
        foreach($groups as $k => $v) {
            $custom = str_replace('[['.$k.']]', $v, $custom);  
        }
        $custom = str_replace('[[assembly_name]]', $this->assembly_lh_name, $custom);
        $custom = str_replace('[[assembly_name_foreign]]', $this->assembly_name_foreign, $custom);
        $custom = str_replace('[[uh_name]]', $this->uh_name, $custom);
        $custom = str_replace('[[uh_name_foreign]]', $this->uh_name_foreign, $custom);
        $custom = str_replace('[[lh_name]]', $this->lh_name, $custom);
        $custom = str_replace('[[lh_name_foreign]]', $this->lh_name_foreign, $custom);
        echo $custom;
    } else {
        $groups = $this->get_dbresults();
        $upper = $groups['up'];
        $lower = $groups['low'];
        $comm = '';
        $houses = '';
        if ($this->assembly_type=='bi') {
            $this->upper_house_name = $this->get_name($this->uh_name, $this->uh_name_foreign);
            if (is_array($upper)) {
            	$groupcount = count($upper);
            	if ($groupcount!=1)
            		$houses = 'Of the '.$this->uh_seats.' seats in the '.$this->upper_house_name.', ';
                $x=1;
                foreach($upper as $gid => $grp) {
                	$term = ($grp['term']==0) ? '' : ' to serve '.Common::get_num_textual($grp['term']).'-year term';
                	if ($groupcount==1) {
                        $str = 'Members of the '.$this->upper_house_name.' are '.$grp['elected_by'].$term;
                	} else {
                        $x++;
                		$str .= $grp['seats'].' members are '.$grp['elected_by'].$term;
                		if ($x<$groupcount)
                            $str .=', ';
                        else if ($x==$groupcount)
                            $str .=' and ';
                	}
                    
                }
                $houses .= $str.'. ';
            }
            
            if ($this->uh_comments)
                $houses .= '<br />*'.$this->uh_comments.' ';
                    
            $this->lower_house_name = $this->get_name($this->lh_name, $this->lh_name_foreign);
            $houses1 = '';
	        $str ='';
            if (is_array($lower)) {
            	$group1count = count($lower);
            	if ($group1count!=1)
            		$str = 'Of the '.$this->lh_seats.' seats in the '.$this->lower_house_name.', ';
                $x=1;
                foreach($lower as $gid => $grp) {
                	$term = ($grp['term']==0) ? '' : ' to serve '.Common::get_num_textual($grp['term']).'-year term';
                	if ($group1count==1) {
                        $str = 'Members of the '.$this->lower_house_name.' are '.$grp['elected_by'].$term;
                	} else {
                        $x++;
                		$str .= $grp['seats'].' members are '.$grp['elected_by'].$term;
                		if ($x<$group1count)
                            $str .=', ';
                        else if ($x==$group1count)
                            $str .=' and ';
                	}
                }
                $houses1 .= $str.'. ';
            }
            if ($this->lh_comments)
                $houses1 .= '<br />*'.$this->lh_comments.' ';
            echo $houses.$houses1;
        } else {
            //UNICAMERAL: Members of the assembly are elected by popular vote to serve four-year terms.
            $this->complete_assembly_name = $this->get_name($this->assembly_name, $this->assembly_name_foreign);
            $houses = '';
            
            if (is_array($upper)) {
            	$groupcount = count($upper);
            	if ($groupcount!=1)
            		$houses = 'Of the '.$this->assembly_seats.' seats in the '.$this->complete_assembly_name.', ';
                $x=1;
                foreach($upper as $gid => $grp) {
                	$term = ($grp['term']==0) ? '' : ' to serve '.Common::get_num_textual($grp['term']).'-year term';
                	// only one group 
                	if ($groupcount==1) {
                        $str = 'Members of the '.$this->complete_assembly_name.' are '.$grp['elected_by'].$term;
                	} else {
                        $x++;
                		$str .= $grp['seats'].' members are '.$grp['elected_by'].$term;
                		if ($x<$groupcount)
                            $str .=', ';
                        else if ($x==$groupcount)
                            $str .=' and ';
                        
                	}
                }
                $houses .= $str.'. ';
            } 

            if (trim($this->assembly_comments)!='') {
                $notes = '<br />*';
                $notesstr = $this->assembly_comments.' ';
            }
            echo $houses.$notes.$notesstr;
        }
    }// end not use custom
        $this->bigarray['electoral'] = array('assembly' => $houses.$houses1,'assemblynotes' => $notesstr);   
        echo '</li></ul>';
    } 
    function get_links() {
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
        $sql = "SELECT * FROM country_links WHERE is_active=1 ";
		$sql .= $sfsql->SafeCompose(
		" AND country=%i", $this->id);
		$sql .= " ORDER BY order_id";
		
        $db->Query($sql);
        if ($db->GetAffectedRows()>0) {
            echo '<h3>Links:</h3>
            <ul>';
            while($data = $db->fetchAssoc()) {
                echo '<li><a href="'.$data['link_url'].'" target="_blank">'.$data['link_name'].'</a></li>';
                $this->bigarray['links'][] = '<a href="'.$data['link_url'].'" target="_blank">'.$data['link_name'].'</a>';
            }
            echo '</ul>';
        }
    }
    
    function get_links_widh_cats() {
        $link_types = Common::get_link_types();
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
		
        $sql = $sfsql->SafeCompose(
		"SELECT * FROM country_links WHERE is_active=1 AND country=%i", $this->id);
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
        CONCAT(
            IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
            IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ' ')),
            YEAR(election_date)) as election_date
        FROM elections, election_types
        LEFT JOIN round_texts ON elections.round_num=round_texts.id ";
		$sql .= $sfsql->SafeCompose(
        " WHERE country=%i", $this->id);
		$sql .= " AND elections.is_active!=3
        AND election_types.id=elections.election_type
        AND election_date>'".date('Y-m-d')."' ORDER BY order_date DESC";
		
        $db->Query($sql);
		
		if ($db->GetAffectedRows()>0) {
            echo '<h3>Future elections</h3><ul>';
            while($data = $db->fetchAssoc()) {
                echo '<li>';
                if ($data['is_active']==4 || $data['is_active']==5) {
                    echo $data['type_name'];
                    if($data['round_num']>0)
                        echo ' ('.$data['round_text'].') ';
                    echo ' - '.$data['election_date'];    
                } else if ($data['is_active']==2) {
                    echo $data['type_name'];
                    if($data['round_num']>0)
                        echo ' ('.$data['round_text'].') ';
                    echo ' - '.$data['election_date']; 
                       
                } else if ($data['is_active']==1) {
                    echo '<a href="election.php?ID='.$data['id'].'">'.$data['type_name'];
                    if($data['round_num']>0)
                        echo ' ('.$data['round_text'].') ';
                    echo ' - '.$data['election_date'].'</a>'; 
                }
                echo '</li>';
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
        CONCAT(
            IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
            IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ' ')),
            YEAR(election_date)) as election_date
        FROM elections, election_types
        LEFT JOIN round_texts ON elections.round_num=round_texts.id ";
		$sql .= $sfsql->SafeCompose(
        " WHERE country=%i", $this->id);
		$sql .= " AND elections.is_active!=3
        AND election_types.id=elections.election_type
        AND election_date<'".date('Y-m-d')."' ORDER BY elections.order_date DESC LIMIT 0,10";
		
        $db->Query($sql);
		if ($db->GetAffectedRows()>0) {
            echo '<h3>Past elections</h3><ul>';
            while($data = $db->fetchAssoc()) {
                echo '<li>';
                if ($data['is_active']==4 || $data['is_active']==5) {
                    echo $data['type_name'];
                    if($data['round_num']>0)
                        echo ' ('.$data['round_text'].') ';
                    echo ' - '.$data['election_date'];    
                } else if ($data['is_active']==2) {
                    echo $data['type_name'];
                    if($data['round_num']>0)
                        echo ' ('.$data['round_text'].') ';
                    echo ' - '.$data['election_date']; 
                       
                } else if ($data['is_active']==1) {
                    echo '<a href="election.php?ID='.$data['id'].'">'.$data['type_name'];
                    if($data['round_num']>0)
                        echo ' ('.$data['round_text'].') ';
                    echo ' - '.$data['election_date'].'</a>'; 
                }
                echo '</li>';
            }
            echo '</ul>';
        }
    }
}
?>
