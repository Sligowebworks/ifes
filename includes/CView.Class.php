<?php

class CView extends CountryView{
    var $id         = '';
    var $table      = '';
    var $bigarray   = array();
    
    function view() {
        if ($this->region>0) {
            $this->bigarray = array(
                'country_id' => $this->id,
                'country_name'  =>$this->country_name,
                'official_name' =>$this->official_name,
                'last_election' => $this->last_election_notes);

            $this->get_government();
            $this->get_electoral();
            $this->get_population();
            //$this->get_future_elections();
            $this->get_last_elections();
            //$this->get_links();
        }
    }
    
    function get_country_table() {
		echo '<table id="country-meta"><tbody>
		<tr>';
        if (file_exists('images/flags/'.$this->country_flag) && is_file('images/flags/'.$this->country_flag)) {
            echo '<td><img src="images/flags/'.$this->country_flag.'" alt="'.$this->country_name.'" style="width:100px;" /></td>';
        }
		echo '<td><ul>
                <li>'.$this->country_name.'</li>
                <li>'.$this->official_name.'</li>
				<li>Region: <a href="region.php?ID='.$this->region.'">'.Common::get_region_name($this->region).'</a></li>
				</ul>
            </td>
		</tr>
		</tbody></table>';
    }
    
    function get_future_elections() {
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
		
        $db->Query("SELECT elections.id,
        election_types.type_name AS election_type_str,
        CONCAT(
            IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
            IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ' ')),
            YEAR(election_date)) as election_date
        FROM elections, election_types
        WHERE " . $sfsql->SafeCompose("country=%i ", $this->id) ."
        AND election_types.id=elections.election_type
        AND (elections.is_active=1 OR elections.is_active=4 OR elections.is_active=5)
        AND election_date>'".date('Y-m-d')."' ORDER BY election_date DESC");
        if ($db->GetAffectedRows()>0) {
            echo '<h3>Future elections</h3><ul>';
            while($data = $db->fetchAssoc()) {
                echo '<li><a href="election.php?ID='.$data['id'].'">'.$data['election_type_str'].' - '.$data['election_date'].'</a></li>';
            }
            echo '</ul>';
        }
    }
    
    function get_last_elections() {
        $db = new Db();
		$sfsql =& new SafeSQL_MySQL();
        $db->Query("SELECT elections.id,
        election_types.type_name AS election_type_str,
        CONCAT(
            IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
            IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ' ')),
            YEAR(election_date)) as election_date
        FROM elections, election_types
        WHERE " . $sfsql->SafeCompose("country=%i ", $this->id) ."
        AND election_types.id=elections.election_type
        AND (elections.is_active=1 OR elections.is_active=4 OR elections.is_active=5)
        AND election_date<'".date('Y-m-d')."' ORDER BY election_date DESC");
        if ($db->GetAffectedRows()>0) {
            echo '<h3>Past elections</h3><ul>';
            while($data = $db->fetchAssoc()) {
                echo '<li><a href="election.php?ID='.$data['id'].'">'.$data['election_type_str'].' - '.$data['election_date'].'</a></li>';
            }
            echo '</ul>';
        }
    }
}
?>
