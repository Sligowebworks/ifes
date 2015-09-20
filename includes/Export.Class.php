<?php

class Export {
    function get_dates() {
        $ret = '';
            if (sess::get('start_year')!=0 && sess::get('start_month')!=0)
                $ret .= " AND election_date>='".sess::get('start_year')."-".sess::get('start_month')."-01'";
            else if (sess::get('start_year')!=0)
                $ret .= " AND YEAR(election_date)>='".sess::get('start_year')."'";

            if (sess::get('end_year')!=0 && sess::get('end_month')!=0)
                $ret .= " AND election_date<='".sess::get('end_year')."-".sess::get('end_month')."-31'";
            else if (sess::get('end_year')!=0)
                $ret .= " AND YEAR(election_date)<='".sess::get('end_year')."'";
        return $ret;
    }
    
    function report_chief() {
        $sql = "SELECT
            elections.id as elid,
            elections.round_num,
            country.id AS cid,
            CONCAT('http://www.electionguide.org/election.php?ID=', elections.id) AS election_link,
            country.region,
            regions.region as region_name,
            IF(country.show_link=1,
                CONCAT('<a href=\"http://www.electionguide.org/country.php?ID=', country.id, '\">', country.country_name, '</a>'),
                country.country_name
            ) AS thecountry,
            IF(elections.assembly_type='bi', 'Bicameral', 'Unicameral') AS assembly_t,
            elections.assembly_type,
            elections.uh_seats,
            elections.lh_seats,
            elections.assembly_seats,
            elections.chief_title,
            IF(elections.chief_term=0, '', elections.chief_term) AS chief_term,
            TRIM(chiefby.the_way) as chief_electedby,
            TRIM(chieftype.the_way) as chief_elected_type,
            IF(elections.assembly_name='',
                CONCAT('<em>', elections.assembly_name_foreign,'</em>'),
                elections.assembly_name
            ) as assembly_name,
            IF(elections.uh_name='',
                CONCAT('<em>', elections.uh_name_foreign,'</em>'),
                elections.uh_name
            ) as uh_name,
            IF(elections.lh_name='',
                CONCAT('<em>', elections.lh_name_foreign,'</em>'),
                elections.lh_name
            ) as lh_name,
            CONCAT(
                IF (MONTH(election_date)=0, '', CONCAT(MONTH(election_date), '/')),
                IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), '/')),
                YEAR(election_date)
            ) as election_date
            FROM elections, country
            LEFT JOIN regions ON country.region=regions.id
            LEFT JOIN assembly_ways chieftype ON chieftype.id=elections.chief_election_type
            LEFT JOIN election_ways chiefby ON chiefby.id=elections.chief_electedby
            WHERE elections.show_results=1
            AND elections.country=country.id
            AND elections.is_active=1";

            if (sess::get('country')!='')
                $sql .= " AND country.id=".sess::get('country');

            if (sess::get('region')!='')
                $sql .= " AND country.region=".sess::get('region');

            $sql .= $this->get_dates();

            if (sess::get('byway')>1 && sess::get('byway')!='')
                $sql .= " AND elections.chief_electedby=".sess::get('byway');

            if (sess::get('etype')>1 && sess::get('etype')!='')
                $sql .= " AND elections.chief_election_type=".sess::get('etype');

            if (sess::get('term')!=0 && sess::get('term')!='')
                $sql .= " AND elections.chief_term=".sess::get('term');

            $sql .= " GROUP BY elections.election_date
            ORDER BY region_name ASC, country_name ASC, elections.order_date DESC, elections.assembly_name ASC";

                $sqlcurr = "SELECT country.id AS cid,
                country.country_name,
                CONCAT('http://www.electionguide.org/country.php?ID=', country.id) AS election_link,
                country.region,
                regions.region AS region_name,
                IF(country.show_link=1,
                    CONCAT('<a href=\"http://www.electionguide.org/country.php?ID=', country.id, '\">', country.country_name, '</a>'),
                    country.country_name
                ) AS thecountry,
                IF (country.assembly_type = 'bi', 'Bicameral', 'Unicameral') AS assembly_t,
                country.assembly_type,
                country.uh_seats,
                country.lh_seats,
                country.assembly_seats,
                country.chief_title,
                IF (country.chief_term =0, '', country.chief_term) AS chief_term,
                TRIM( chiefby.the_way ) AS chief_electedby,
                TRIM( chieftype.the_way ) AS chief_elected_type,
                IF(country.assembly_name='',
                    CONCAT('<em>', country.assembly_name_foreign,'</em>'),
                    country.assembly_name
                ) as assembly_name,
                IF(country.uh_name='',
                    CONCAT('<em>', country.uh_name_foreign,'</em>'),
                    country.uh_name
                ) as uh_name,
                IF(country.lh_name='',
                    CONCAT('<em>', country.lh_name_foreign,'</em>'),
                    country.lh_name
                ) as lh_name,
                CONCAT(IF(MONTH(country.date_updated)=0,'',CONCAT(MONTH(country.date_updated) , '/')),
                    IF (DAYOFMONTH(country.date_updated)=0,'',CONCAT(DAYOFMONTH(country.date_updated ),'/')), YEAR( country.date_updated )
                ) AS election_date
                FROM country,elections
                LEFT JOIN regions ON country.region = regions.id
                LEFT JOIN assembly_ways chieftype ON chieftype.id = country.chief_election_type
                LEFT JOIN election_ways chiefby ON chiefby.id = country.chief_electedby
                WHERE country.show_link=1
                AND elections.country=country.id
                AND country.is_active=1";
                if (sess::get('country')!='')
                    $sqlcurr .= " AND country.id=".sess::get('country');

                if (sess::get('region')!='')
                    $sqlcurr .= " AND country.region=".sess::get('region');

                $sqlcurr .= " GROUP BY country.id
                ORDER BY region_name ASC, country_name ASC, country.assembly_name ASC";
                $dbcurr = new Db();
                $dbcurr->Query($sqlcurr);
                if($dbcurr->GetAffectedRows()>0) {
                    while($thedata = $dbcurr->fetchAssoc())
                        $currdata[$thedata['cid']] = $thedata;
                }

                $sql2 = "SELECT country.id AS cid,
                curr_groups.int_id AS gid,
                curr_groups.assembly_type,
                curr_groups.elected_by,
                curr_groups.election_type,
                IF(curr_groups.seats='-1', 'N/A', curr_groups.seats) as seats,
                curr_groups.term,
                IF(LCASE(election_ways.the_way)='n/a','', election_ways.the_way) AS elected_by,
                IF(LCASE(assembly_ways.the_way)='n/a','', assembly_ways.the_way) AS election_type
                FROM curr_groups
                LEFT JOIN assembly_ways ON assembly_ways.id=curr_groups.election_type
                LEFT JOIN election_ways ON election_ways.id=curr_groups.elected_by
                LEFT JOIN country ON country.id=curr_groups.country
                WHERE curr_groups.is_active=1
                AND country.is_active=1";

                if (sess::get('country')!='')
                    $sql2 .= " AND country.id=".sess::get('country');
                    
                if (sess::get('region')!='')
                    $sql2 .= " AND country.region=".sess::get('region');
                    
                if (sess::get('byway')>1 && sess::get('byway')!='')
                    $sql2 .= " AND curr_groups.elected_by=".sess::get('byway');

                if (sess::get('etype')>1 && sess::get('etype')!='')
                    $sql2 .= " AND curr_groups.election_type=".sess::get('etype');

                if (sess::get('term')!=0 && sess::get('term')!='')
                    $sql2 .= " AND curr_groups.term=".sess::get('term');
                $sql2 .= " ORDER BY curr_groups.assembly_type DESC, curr_groups.int_id ASC";

                $db1 = new Db();
                $db1->Query($sql2);
                $refrow = $db1->GetAffectedRows();
                while($rows = $db1->fetchAssoc())
                    $currefs[$rows['cid']][$rows['assembly_type']][$rows['gid']] = $rows;


            $db = new Db();
            $db->Query($sql);
            $numrows = $db->GetAffectedRows();
            if($numrows>0) {
                while($data = $db->fetchAssoc()) {
                    $finaldata[$data['cid']][$data['elid']] = $data;
                }

                $sql1 = "SELECT elections.id AS elid,
                groups.int_id AS gid,
                groups.assembly_type,
                groups.elected_by,
                groups.election_type,
                IF(groups.seats='-1', 'N/A', groups.seats) as seats,
                groups.term,
                IF(LCASE(election_ways.the_way)='n/a','', election_ways.the_way) AS elected_by,
                IF(LCASE(assembly_ways.the_way)='n/a','', assembly_ways.the_way) AS election_type
                FROM groups
                LEFT JOIN assembly_ways ON assembly_ways.id=groups.election_type
                LEFT JOIN election_ways ON election_ways.id=groups.elected_by
                LEFT JOIN elections ON elections.id=groups.election
                WHERE groups.is_active=1
                AND elections.is_active=1";

                if (sess::get('country')!='')
                    $sql1 .= " AND elections.country=".sess::get('country');
   
                $sql1 .= $this->get_dates();

                if (sess::get('byway')>1 && sess::get('byway')!='')
                    $sql1 .= " AND groups.elected_by=".sess::get('byway');

                if (sess::get('etype')>1 && sess::get('etype')!='')
                    $sql1 .= " AND groups.election_type=".sess::get('etype');

                if (sess::get('term')!=0 && sess::get('term')!='')
                    $sql1 .= " AND groups.term=".sess::get('term');
                $sql1 .= " ORDER BY groups.assembly_type DESC, elections.order_date DESC, groups.int_id ASC";

                $db1 = new Db();
                $db1->Query($sql1);
                $refrow = $db1->GetAffectedRows();
                while($rows = $db1->fetchAssoc())
                    $refs[$rows['elid']][$rows['assembly_type']][$rows['gid']] = $rows;

                $str = '
                <table><tr>
                    <th>Region/ Country</th>
                    <th>Chief of State Title</th>
                    <th>Method of selection</th>
                    <th>Selected by</th>
                    <th>Term of Office</th>
                    <th>Data as of </th>
                </tr>';
                foreach($finaldata as $country => $eldata) {
                    if(is_array($currdata)) {
                        if (in_array($country, array_keys($currdata))) {
                            $data = $currdata[$country];
                            $str .= '<tr><td>';
                            if($data['region_name']!='')
                                $str .= $data['region_name'].'/ ';
                            $str .= $data['thecountry'].' </td>
                            <td>'.html_entity_decode($data['chief_title']).'</td>
                            <td>'.strtolower($data['chief_electedby']).'</td>
                            <td>'.html_entity_decode($data['chief_elected_type']).'</td>
                            <td>'.($data['chief_term']>0?$data['chief_term'].' years':'&nbsp;').'</td>
                           	<td><a href="'.$data['election_link'].'">Current</a></td>
                            </tr>';
                        }
                    }
                    if (sess::get('show_curr_only')==0) {
                    foreach($eldata as $election => $data) {
                        if ($data['round_num']<=11) {
                            $str .= '<tr><td>';
                            if($data['region_name']!='')
                                $str .= $data['region_name'].'/ ';
                            $str .= $data['thecountry'].' </td>
                            <td>'.html_entity_decode($data['chief_title']).'</td>
                            <td>'.strtolower($data['chief_electedby']).'</td>
                            <td>'.html_entity_decode($data['chief_elected_type']).'</td>
                            <td>'.($data['chief_term']>0?$data['chief_term'].' years':'&nbsp;').'</td>
                           	<td><a href="'.$data['election_link'].'">'.$data['election_date'].'</a></td>
                            </tr>';
                        }
                    }
                    }
                }
                $str .= '</table>';
        }
        return $str;
    }
    
    function report_govt() {
        $sql = "SELECT
            elections.id as elid,
            elections.round_num,
            country.id AS cid,
            CONCAT('http://www.electionguide.org/election.php?ID=', elections.id) AS election_link,
            country.region,
            regions.region as region_name,
            IF(country.show_link=1,
                CONCAT('<a href=\"http://www.electionguide.org/country.php?ID=', country.id, '\">', country.country_name, '</a>'),
                country.country_name
            ) AS thecountry,
            IF(elections.assembly_type='bi', 'Bicameral', 'Unicameral') AS assembly_t,
            elections.assembly_type,
            elections.uh_seats,
            elections.lh_seats,
            elections.assembly_seats,
            elections.chief_title,
            IF(elections.chief_term=0, '', elections.chief_term) AS chief_term,
            TRIM(chiefby.the_way) as chief_electedby,
            TRIM(chieftype.the_way) as chief_elected_type,
            IF(elections.assembly_name='',
                CONCAT('<em>', elections.assembly_name_foreign,'</em>'),
                elections.assembly_name
            ) as assembly_name,
            IF(elections.uh_name='',
                CONCAT('<em>', elections.uh_name_foreign,'</em>'),
                elections.uh_name
            ) as uh_name,
            IF(elections.lh_name='',
                CONCAT('<em>', elections.lh_name_foreign,'</em>'),
                elections.lh_name
            ) as lh_name,
            CONCAT(
                IF (MONTH(election_date)=0, '', CONCAT(MONTH(election_date), '/')),
                IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), '/')),
                YEAR(election_date)
            ) as election_date
            FROM elections, country
            LEFT JOIN regions ON country.region=regions.id
            LEFT JOIN assembly_ways chieftype ON chieftype.id=elections.chief_election_type
            LEFT JOIN election_ways chiefby ON chiefby.id=elections.chief_electedby
            WHERE elections.show_results=1
            AND elections.country=country.id
            AND elections.is_active=1";

            if (sess::get('country')!='')
                $sql .= " AND country.id=".sess::get('country');

            if (sess::get('region')!='')
                $sql .= " AND country.region=".sess::get('region');
                
            if (sess::get('show_curr_only')==0)
                $sql .= $this->get_dates();
            $sql .= " GROUP BY elections.election_date
            ORDER BY region_name ASC, country_name ASC, elections.order_date DESC, elections.assembly_name ASC";

                $sqlcurr = "SELECT country.id AS cid,
                country.country_name,
                CONCAT('http://www.electionguide.org/country.php?ID=', country.id) AS election_link,
                country.region,
                regions.region AS region_name,
                IF(country.show_link=1,
                    CONCAT('<a href=\"http://www.electionguide.org/country.php?ID=', country.id, '\">', country.country_name, '</a>'),
                    country.country_name
                ) AS thecountry,
                IF (country.assembly_type = 'bi', 'Bicameral', 'Unicameral') AS assembly_t,
                country.assembly_type,
                country.uh_seats,
                country.lh_seats,
                country.assembly_seats,
                country.chief_title,
                IF (country.chief_term =0, '', country.chief_term) AS chief_term,
                TRIM( chiefby.the_way ) AS chief_electedby, TRIM( chieftype.the_way ) AS chief_elected_type,
                IF(country.assembly_name='',
                    CONCAT('<em>', country.assembly_name_foreign,'</em>'),
                    country.assembly_name
                ) as assembly_name,
                IF(country.uh_name='',
                    CONCAT('<em>', country.uh_name_foreign,'</em>'),
                    country.uh_name
                ) as uh_name,
                IF(country.lh_name='',
                    CONCAT('<em>', country.lh_name_foreign,'</em>'),
                    country.lh_name
                ) as lh_name,
                CONCAT(IF(MONTH(country.date_updated)=0,'',CONCAT(MONTH(country.date_updated) , '/')),
                    IF (DAYOFMONTH(country.date_updated)=0,'',CONCAT(DAYOFMONTH(country.date_updated ),'/')), YEAR( country.date_updated )
                ) AS election_date
                FROM country,elections
                LEFT JOIN regions ON country.region = regions.id
                LEFT JOIN assembly_ways chieftype ON chieftype.id = country.chief_election_type
                LEFT JOIN election_ways chiefby ON chiefby.id = country.chief_electedby
                WHERE country.show_link=1
                AND elections.country=country.id
                AND country.is_active=1";
                if (sess::get('country')!='')
                    $sqlcurr .= " AND country.id=".sess::get('country');

                if (sess::get('region')!='')
                    $sqlcurr .= " AND country.region=".sess::get('region');

                $sqlcurr .= " GROUP BY country.id
                ORDER BY region_name ASC, country_name ASC, country.assembly_name ASC";
                $dbcurr = new Db();
                $dbcurr->Query($sqlcurr);
                if($dbcurr->GetAffectedRows()>0) {
                    while($thedata = $dbcurr->fetchAssoc())
                        $currdata[$thedata['cid']] = $thedata;
                }

                $sql2 = "SELECT country.id AS cid,
                curr_groups.int_id AS gid,
                curr_groups.assembly_type,
                curr_groups.elected_by,
                curr_groups.election_type,
                IF(curr_groups.seats='-1', 'N/A', curr_groups.seats) as seats,
                curr_groups.term,
                IF(LCASE(election_ways.the_way)='n/a','', election_ways.the_way) AS elected_by,
                IF(LCASE(assembly_ways.the_way)='n/a','', assembly_ways.the_way) AS election_type
                FROM curr_groups
                LEFT JOIN assembly_ways ON assembly_ways.id=curr_groups.election_type
                LEFT JOIN election_ways ON election_ways.id=curr_groups.elected_by
                LEFT JOIN country ON country.id=curr_groups.country
                WHERE curr_groups.is_active=1
                AND country.is_active=1";

                if (sess::get('region')!='')
                    $sql2 .= " AND country.region=".sess::get('region');

                if (sess::get('country')!='')
                    $sql2 .= " AND country.id=".sess::get('country');

                    if (sess::get('byway')>1)
                        $sql2 .= " AND curr_groups.elected_by=".sess::get('byway');

                    if (sess::get('etype')>1)
                        $sql2 .= " AND curr_groups.election_type=".sess::get('etype');

                    if (sess::get('term')>0)
                        $sql2 .= " AND curr_groups.term=".sess::get('term');
                $sql2 .= " ORDER BY curr_groups.assembly_type DESC, curr_groups.int_id ASC";

                $db1 = new Db();
                $db1->Query($sql2);
                $refrow = $db1->GetAffectedRows();
                while($rows = $db1->fetchAssoc())
                    $currefs[$rows['cid']][$rows['assembly_type']][$rows['gid']] = $rows;

            $db = new Db();
            $db->Query($sql);
            $numrows = $db->GetAffectedRows();
            if($numrows>0) {
                while($data = $db->fetchAssoc()) {
                    $finaldata[$data['cid']][$data['elid']] = $data;
                }

                $sql1 = "SELECT elections.id AS elid,
                groups.int_id AS gid,
                groups.assembly_type,
                groups.elected_by,
                groups.election_type,
                IF(groups.seats='-1', 'N/A', groups.seats) as seats,
                groups.term,
                IF(LCASE(election_ways.the_way)='n/a','', election_ways.the_way) AS elected_by,
                IF(LCASE(assembly_ways.the_way)='n/a','', assembly_ways.the_way) AS election_type
                FROM groups
                LEFT JOIN assembly_ways ON assembly_ways.id=groups.election_type
                LEFT JOIN election_ways ON election_ways.id=groups.elected_by
                LEFT JOIN elections ON elections.id=groups.election
                WHERE groups.is_active=1
                AND elections.is_active=1";

                if (sess::get('country')!='')
                    $sql1 .= " AND elections.country=".sess::get('country');

                $sql1 .= $this->get_dates();

                if (sess::get('byway')>1)
                    $sql1 .= " AND groups.elected_by=".sess::get('byway');

                if (sess::get('etype')>1)
                    $sql1 .= " AND groups.election_type=".sess::get('etype');

                if (sess::get('term')>0)
                    $sql1 .= " AND groups.term=".sess::get('term');
                    
                $sql1 .= " ORDER BY groups.assembly_type DESC, elections.order_date DESC, groups.int_id ASC";

                $db1 = new Db();
                $db1->Query($sql1);
                $refrow = $db1->GetAffectedRows();
                while($rows = $db1->fetchAssoc())
                    $refs[$rows['elid']][$rows['assembly_type']][$rows['gid']] = $rows;

                $str = '<table><tr>
                    <th>Region/ Country</th>
                    <th>Assembly type</th>
                    <th>Assembly or House</th>
                    <th>Seats</th>
                    <th>Method of selection</th>
                    <th>Selected by</th>
                    <th>Term of Office</th>
                    <th>Data as of </th>
                </tr>';
                foreach($finaldata as $country => $eldata) {
                    if(is_array($currdata)) {
                        if (in_array($country, array_keys($currdata))) {
                            //$counter = count($refs[$data['id']]);
                            $data1 = $currdata[$country];
                            if(is_array($currefs[$country])) {
                                foreach($currefs[$country] as $refid => $refdata) {
                                    foreach($refdata as $house => $housedata) {
                                        $str .= '<tr><td>';
                                        if($data1['region_name']!='')
                                            $str .= $data1['region_name'].'/ ';
                                        $str .= $data1['thecountry'].' </td>
                                        <td>'.$data1['assembly_t'].'</td>
                                        <td>';

                                        if ($currdata['assembly_t']=='Bicameral') {
                                            $str .= ($refid=='up'
                                            ? html_entity_decode($data1['uh_name'])
                                            : html_entity_decode($data1['lh_name']));
                                        } else {
                                            $str .= html_entity_decode($data1['assembly_name']);
                                        }
                                        $str .= '</td>
                                        <td>'.$housedata['seats'].'</td>
                                        <td>'.strtolower($housedata['elected_by']).'&nbsp;</td>
                                        <td>'.html_entity_decode($housedata['election_type']).'&nbsp;</td>
                                        <td>'.($housedata['term']>0?$housedata['term'].' years':'&nbsp;').'</td>
                                       	<td><a href="'.$data1['election_link'].'">Current</a></td>
                                        </tr>';
                                    }
                                }
                            }
                        }
                    }
                    if (sess::get('show_curr_only')==0) {
                        foreach($eldata as $election => $data) {
                            if ($data['round_num']<=11) {
                                $counter = count($refs[$data['elid']]);
                                if(is_array($refs[$data['elid']])) {
                                    foreach($refs[$data['elid']] as $refid => $refdata) {
                                        foreach($refdata as $house => $housedata) {
                                            $str .= '<tr><td>';
                                            if($data['region_name']!='')
                                                $str .= $data['region_name'].'/ ';
                                            $str .= $data['thecountry'].' </td>
                                            <td>'.$data['assembly_t'].'</td>
                                            <td>';
                                            if ($data['assembly_t']=='Bicameral') {
                                                $str .= ($refid=='up'
                                                ? html_entity_decode($data['uh_name'])
                                                : html_entity_decode($data['lh_name']));
                                            } else {
                                                $str .= html_entity_decode($data['assembly_name']);
                                            }
                                            $str .= '</td>
                                            <td>'.$housedata['seats'].'</td>
                                            <td>'.strtolower($housedata['elected_by']).'&nbsp;</td>
                                            <td>'.html_entity_decode($housedata['election_type']).'&nbsp;</td>
                                            <td>'.($housedata['term']>0?$housedata['term'].' years':'&nbsp;');
                                            $str .= '</td>
                                           	<td><a href="'.$data['election_link'].'">'.$data['election_date'].'</a></td>
                                            </tr>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            $str .= '</table>';
        }
        return $str;
    }
    
    function report_presidential() {

        $sql = "SELECT
            elections.id,
            CONCAT('http://www.electionguide.org/election.php?ID=', elections.id) AS election_link,
            round_texts.status_text AS round_num_text,
            country.region,
            regions.region as region_name,
            elections.round_num,
            elections.country,
            elections.election_type,
            IF(country.show_link=1,
                CONCAT('<a href=\"http://www.electionguide.org/country.php?ID=', country.id, '\">', country.country_name, '</a>'),
                country.country_name
            ) AS thecountry,
            IF ((elections.is_active=4 OR elections.is_active=5),
                CONCAT('Presidential', IF(elections.round_num>0, CONCAT(' ', round_texts.status_text), ''), ' (',status_texts.status_text, ')'),
                IF(elections.is_active=2,
                    CONCAT('Presidential', IF(elections.round_num>0, CONCAT(' ', round_texts.status_text), '')),
                    CONCAT('<a href=\"http://www.electionguide.org/election.php?ID=', elections.id, '\">Presidential</a>',
                        IF(elections.round_num>0, CONCAT(' ', round_texts.status_text), ''))
                )
            ) as thetype,
            CONCAT(
                IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
                IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
                YEAR(election_date)
            ) as thedate,
            candidates_pres.candidate_party,
            IF(candidates_pres.party_name_foreign!='',
                CONCAT('<em>', candidates_pres.party_name_foreign, '</em>'),
                ''
            ) AS party_name_foreign,
            IF(candidates_pres.party_acronym!='',
                CONCAT('(', candidates_pres.party_acronym, ')'),
                ''
            ) AS party_acronym,
            IF(candidates_pres.party_acronym_foreign!='',
                CONCAT('(<em>', candidates_pres.party_acronym_foreign, '</em>)'),
                ''
            ) AS party_acronym_foreign,
            CONCAT(candidates_pres.candidate_fname, ' ',candidates_pres.candidate_lname) AS candidate,
            candidates_pres.candidate_votes,
            candidates_pres.candidate_votes_percentage,
            candidates_pres.is_winner,
            elections.round_num,
            elections.votes_valid,
            elections.votes_valid_percentage,
            elections.chief_title,
            elections.chief_term,
            elections.votes_cast_percentage,
            elections.votes_cast
            FROM candidates_pres
            LEFT JOIN status_texts ON elections.is_active=status_texts.id
            LEFT JOIN elections ON elections.id=candidates_pres.election
            LEFT JOIN country ON elections.country=country.id
            LEFT JOIN regions ON country.region=regions.id
            LEFT JOIN round_texts ON elections.round_num=round_texts.id
            WHERE elections.show_results=1
            AND elections.country=country.id
            AND elections.show_results=1
            AND elections.is_active=1
            AND elections.election_type=1";

            if (sess::get('winner')!='')
                $sql .= " AND candidates_pres.is_winner=1";

            if (sess::get('keyword')!='')
                $sql .= " AND (candidates_pres.candidate_fname LIKE '%".sess::get('keyword')."%' OR
                    candidates_pres.candidate_lname LIKE '%".sess::get('keyword')."%')";

            if (sess::get('pkeyword')!='')
                $sql .= " AND (candidates_pres.candidate_party LIKE '%".sess::get('pkeyword')."%' OR
                    candidates_pres.party_name_foreign LIKE '%".sess::get('pkeyword')."%' OR
                    candidates_pres.party_acronym LIKE '%".sess::get('pkeyword')."%' OR
                    candidates_pres.party_acronym_foreign LIKE '%".sess::get('pkeyword')."%')";

            if (sess::get('round_num')>0)
                $sql .= " AND elections.round_num=".sess::get('round_num');

            if (sess::get('country')!='')
                $sql .= " AND country.id=".sess::get('country');

            if (sess::get('region')!='')
                $sql .= " AND country.region=".sess::get('region');

            $sql .= $this->get_dates();

            $sql .= " ORDER BY regions.region ASC, country_name ASC, candidates_pres.candidate_lname ASC, elections.order_date DESC";
        //echo $sql;
        $db = new Db();
        $db->Query($sql);
        $numrows = $db->GetAffectedRows();

        if ($numrows>0) {
            $str = '<table><tr>
                <th>Country</th>
                <th>Election Type</th>
                <th>Date</th>
                <th>Party Name</th>
                <th>Candidate</th>
                <th>Votes for candidate/ %</th>
                <th>Voter Turnout</th>
            </tr>';
            while($data = $db->fetchAssoc()) {
                if ($data['round_num']<=11) {
                    $reg_voters = ($data['reg_voters']<0) ? 'N/A' : number_format($data['reg_voters'],0);
                    //$valid_votes = ($data['votes_valid']<0) ? 'N/A' : number_format($data['votes_valid'],0);
                    //$valid_votesp = ($data['votes_valid_percentage']<0) ? 'N/A' :$data['votes_valid_percentage'].'%';
                    $votes_cast = ($data['votes_cast']<0) ? 'N/A' :number_format($data['votes_cast'],0);
                    $votes_castp = ($data['votes_cast_percentage']<0) ? 'N/A' :$data['votes_cast_percentage'].'%';
                    $cvotes_cast = ($data['candidate_votes']<0) ? 'N/A' : number_format($data['candidate_votes'],0);
                    $cvotes_castp = ($data['candidate_votes_percentage']<0) ? 'N/A' :$data['candidate_votes_percentage'].'%';
                    $str .= '<tr><td>';
                            //if($data['region_name']!='')
                            //    echo $data['region_name'].'/ ';
                    $str .= $data['thecountry'].' </td>
                    <td>'.$data['thetype'].'</td>
                    <td>'.$data['thedate'].'</td>
                    <td>';
                    if ($data['candidate_party']!='')
                        $str .= html_entity_decode($data['candidate_party']);
                    if ($data['candidate_party']!='' && $data['party_name_foreign']!='')
                        $str .= '/'.$data['party_name_foreign'];
                    $str .= '</td>
                    <td>'.html_entity_decode($data['candidate']);
                    $str .= ($data['is_winner']==1?'*':'');
                    $str .= '</td>
                   	<td>'.($cvotes_cast==$cvotes_castp?$cvotes_cast:$cvotes_cast.' / '.$cvotes_castp).'</td>
                    <td>'.$votes_castp.'</td>
                    </tr>';
                }
            }
            $str .= '</table>';
        } 
        return $str;
    }
    
    function report_parties() {
        $sql = "SELECT
            elections.id,
            CONCAT('http://www.electionguide.org/election.php?ID=', elections.id) AS election_link,
            round_texts.status_text AS round_num_text,
            country.region,
            regions.region as region_name,
            elections.round_num,
            elections.country,
            elections.election_type,
            IF(country.show_link=1,
                CONCAT('<a href=\"http://www.electionguide.org/country.php?ID=', country.id, '\">', country.country_name, '</a>'),
                country.country_name
            ) AS thecountry,
            IF ((elections.is_active=4 OR elections.is_active=5),
                CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT(' ', round_texts.status_text), ''), ' (',status_texts.status_text, ')'),
                IF(elections.is_active=2,
                    CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT(' ', round_texts.status_text), '')),
                    CONCAT('<a href=\"http://www.electionguide.org/election.php?ID=', elections.id, '\">', election_types.type_name, '</a>',
                        IF(elections.round_num>0, CONCAT(' ', round_texts.status_text), ''))
                )
            ) as thetype,
            CONCAT(
                IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
                IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
                YEAR(election_date)
            ) as thedate,
            candidates.party_name,
            candidates.party_name_foreign,
            candidates.party_acronym,
            candidates.party_acronym_foreign,
            CONCAT(candidates.party_leader_fname, ' ',candidates.party_leader_lname) AS party_leader,
            candidates.party_seats,
            candidates.party_seats_won,
            candidates.party_seats1,
            candidates.party_seats1_won,
            candidates.party_votes,
            candidates.party_votes_percentage,
            candidates.party_votes1,
            candidates.party_votes1_percentage,
            candidates.is_winner,
            elections.assembly_type,
            elections.assembly_seats_at_stake,
            elections.round_num,
            elections.votes_cast,
            elections.chief_title,
            elections.chief_term,
            elections.votes_cast_percentage,
            elections.votes_cast1,
            elections.votes_cast1_percentage,
            elections.uh_seats_at_stake,
            elections.lh_seats_at_stake,
            elections.show_uh_results,
            elections.show_lh_results,
            IF(elections.assembly_name='', CONCAT('<em>', elections.assembly_name_foreign,'</em>'), elections.assembly_name) as assembly_name,
            IF(elections.uh_name='', CONCAT('<em>', elections.uh_name_foreign,'</em>'), elections.uh_name) as uh_name,
            IF(elections.lh_name='', CONCAT('<em>', elections.lh_name_foreign,'</em>'), elections.lh_name) as lh_name,
            elections.votes_cast
            FROM candidates
            LEFT JOIN status_texts ON elections.is_active=status_texts.id
            LEFT JOIN elections ON elections.id=candidates.election
            LEFT JOIN election_types ON election_types.id=elections.election_type
            LEFT JOIN country ON elections.country=country.id
            LEFT JOIN regions ON country.region=regions.id
            LEFT JOIN round_texts ON elections.round_num=round_texts.id
            WHERE elections.show_results=1
            AND elections.country=country.id
            AND elections.is_active=1
            AND election_types.id=elections.election_type";

            if (sess::get('winner')!='')
                $sql .= " AND candidates.is_winner=1";
                
            if (sess::get('pkeyword')!='') {
                $sql .= " AND (candidates.party_leader_fname LIKE '%".sess::get('pkeyword')."%' OR
                    candidates.party_leader_lname LIKE '%".sess::get('pkeyword')."%')";
            }

            if (sess::get('keyword')!='') {
                $sql .= " AND (candidates.party_name LIKE '%".sess::get('keyword')."%' OR
                    candidates.party_name_foreign LIKE '%".sess::get('keyword')."%' OR
                    candidates.party_acronym LIKE '%".sess::get('keyword')."%' OR
                    candidates.party_acronym_foreign LIKE '%".sess::get('keyword')."%')";
            }
            
            if (sess::get('type')!='')
                $sql .= " AND elections.election_type=".sess::get('type');

            if (sess::get('round_num')>0)
                $sql .= " AND elections.round_num=".sess::get('round_num');

            if (sess::get('country')!='')
                $sql .= " AND country.id=".sess::get('country');

            if (sess::get('region')!='')
                $sql .= " AND country.region=".sess::get('region');

            $sql .= $this->get_dates();

            $sql .= " ORDER BY country_name ASC, candidates.party_name ASC, elections.order_date DESC";
        //echo $sql;
        $db = new Db();
        $db->Query($sql);
        $numrows = $db->GetAffectedRows();

        if ($numrows>0) {
            $str = '<table><tr>
                <th>Country</th>
                <th>Party Name</th>
                <th>Party Leader</th>
                <th>Election Type</th>
                <th>Date</th>
                <th>Seats at Stake</th>
                <th>Seats Won</th>
                <th>Turnout</th>
                <th>Seats Won Last Election</th>
                <th>Change</th>
            </tr>';
        while($data = $db->fetchAssoc()) {
            if ($data['round_num']<=11) {
                $reg_voters = ($data['reg_voters']<0) ? 'N/A' : number_format($data['reg_voters'],0);
                    if ($data['assembly_type']=='uni') {
                        $votes_cast = ($data['votes_cast']<0) ? 'N/A' :number_format($data['votes_cast'],0);
                        $votes_castp = ($data['votes_cast_percentage']<0) ? 'N/A' :$data['votes_cast_percentage'].'%';
                        $str .= '<tr><td>';
                        //if($data['region_name']!='')
                        //    echo $data['region_name'].'/ ';
                        $str .= $data['thecountry'].' </td>
                        <td>'.html_entity_decode($data['party_name']).($data['is_winner']==1?'*':'').'</td>
                        <td>'.html_entity_decode($data['party_leader']).'</td>
                        <td>'.$data['thetype'].'</td>
                        <td>'.$data['thedate'].'</td>
                       	<td>';
                        if ($data['assembly_seats_at_stake']>0) {
                            $str .= $data['assembly_seats_at_stake'].' seats in '.html_entity_decode($data['assembly_name']);
                        }
                        
                        $diff = $this->get_diff($data['party_seats'],$data['party_seats_won']);
                        $str .= '<td>'.($data['party_seats_won']=='-1'?'N/A':$data['party_seats_won']).'</td>
                        <td>'.$votes_castp.'</td>
                       	<td>'.($data['party_seats']=='-1'?'N/A':$data['party_seats']).'</td>
                       	<td>'.$diff.'</td>
                        </tr>';
                    } else {
                        $votes_cast = ($data['votes_cast']<0) ? 'N/A' :number_format($data['votes_cast'],0);
                        $votes_castp = ($data['votes_cast_percentage']<0) ? 'N/A' :$data['votes_cast_percentage'].'%';
                        $votes_cast1 = ($data['votes_cast1']<0) ? 'N/A' :number_format($data['votes_cast1'],0);
                        $votes_castp1 = ($data['votes_cast1_percentage']<0) ? 'N/A' :$data['votes_cast1_percentage'].'%';
                        $diff = $this->get_diff($data['party_seats'],$data['party_seats_won']);
                        $diff1 = $this->get_diff($data['party_seats1'],$data['party_seats1_won']);
                        if ($data['show_uh_results']==1) {
                            $str .= '<tr><td>';
                            //if($data['region_name']!='')
                            //    echo $data['region_name'].'/ ';
                            $str .= $data['thecountry'].' </td>
                            <td>'.html_entity_decode($data['party_name']).'</td>
                            <td>'.html_entity_decode($data['party_leader']).'</td>
                           	<td>'.$data['thetype'].' '.($data['show_lh_results']==1?' '.html_entity_decode($data['uh_name']):'').'</td>
                           	<td>'.$data['thedate'].'</td>
                           	<td>';
                            if ($data['uh_seats_at_stake']>0) {
                                $str .= $data['uh_seats_at_stake'].' seats in '.html_entity_decode($data['uh_name']);
                            }
                            $str .= '<td>'.($data['party_seats_won']=='-1'?'N/A':$data['party_seats_won']).'</td>
                            <td>'.$votes_castp.'</td>
                            	<td>'.($data['party_seats']=='-1'?'N/A':$data['party_seats']).'</td>
                            	<td>'.$diff.'</td>
                            </tr>';
                        }

                        if ($data['show_lh_results']==1) {
                            $str .= '<tr><td>';
                            //if($data['region_name']!='')
                            //    echo $data['region_name'].'/ ';
                            $str .= $data['thecountry'].' </td>
                            <td>'.html_entity_decode($data['party_name']).'</td>
                            <td>'.html_entity_decode($data['party_leader']).'</td>
                           	<td>'.$data['thetype'].' '.($data['show_uh_results']==1?' '.html_entity_decode($data['lh_name']):'').'</td>
                           	<td>'.$data['thedate'].'</td>
                           	<td>';
                            if ($data['lh_seats_at_stake']>0) {
                                $str .= $data['lh_seats_at_stake'].' seats in '.html_entity_decode($data['lh_name']);
                            }
                            $str .= '<td>'.($data['party_seats1_won']=='-1'?'N/A':$data['party_seats1_won']).'</td>
                            <td>'.$votes_castp1.'</td>
                           	<td>'.($data['party_seats1']=='-1'?'N/A':$data['party_seats1']).'</td>
                           	<td>'.$diff1.'</td>
                            </tr>';
                        }
                    }
            }
        }
        $str .= '</table>';
    } 
    return $str;
}

    function get_diff($seats, $seats_won) {
        $s = ($seats=='-1')? 'na' : $seats;
        $w = ($seats_won=='-1')? 'na' : $seats_won;
        if ($s=='na' || $w=='na') {
            return 'N/A';
        } else {
            $ret = ($w-$s);
            return ($ret<=0) ? $ret :'+'.$ret;
        }
    }
    
    //COUNTRY,DATE,ELECTION TYPE,REGISTERED VOTERS,VOTES CAST,% OF REGISTERED VOTERS
    function report_turnout() {
        $order = ($_REQUEST['_orderby']!='') ? $_REQUEST['_orderby']: 'order_date';
        $dir = ($_REQUEST['_dir']!='') ? $_REQUEST['_dir']: 'ASC';
        $sql = "SELECT
            elections.id,
            CONCAT('http://www.electionguide.org/election.php?ID=', elections.id) AS election_link,
            round_texts.status_text AS round_num_text,
            country.region,
            regions.region as region_name,
            elections.round_num,
            elections.country,
            elections.election_type,
            IF(country.show_link=1,
                CONCAT('<a href=\"http://www.electionguide.org/country.php?ID=', country.id, '\">', country.country_name, '</a>'),
                country.country_name
            ) AS thecountry,
            IF ((elections.is_active=4 OR elections.is_active=5),
                CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT(' ', round_texts.status_text), ''), ' (',status_texts.status_text, ')'),
                IF(elections.is_active=2,
                    CONCAT(election_types.type_name, IF(elections.round_num>0, CONCAT(' ', round_texts.status_text), '')),
                    CONCAT('<a href=\"http://www.electionguide.org/election.php?ID=', elections.id, '\">', election_types.type_name, '</a>',
                        IF(elections.round_num>0, CONCAT(' ', round_texts.status_text), ''))
                )
            ) as thetype,
            CONCAT(
                IF (MONTHNAME(election_date)=0, '', CONCAT(MONTHNAME(election_date), ' ')),
                IF (DAYOFMONTH(election_date)=0, '', CONCAT(DAYOFMONTH(election_date), ', ')),
                YEAR(election_date)
            ) as thedate,
            IF(elections.reg_voters<=0, 0, elections.reg_voters) as reg_voters,
            elections.assembly_type,
            elections.assembly_seats_at_stake,
            elections.round_num,
            elections.votes_cast,
            elections.chief_title,
            elections.chief_term,
            elections.votes_cast_percentage,
            elections.votes_cast1,
            elections.votes_cast1_percentage,
            elections.uh_seats_at_stake,
            elections.lh_seats_at_stake,
            elections.show_uh_results,
            elections.show_lh_results,
            elections.election_summary,
            IF(elections.assembly_name='', CONCAT('<em>', elections.assembly_name_foreign,'</em>'), elections.assembly_name) as assembly_name,
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
            LEFT JOIN regions ON country.region=regions.id
            LEFT JOIN round_texts ON elections.round_num=round_texts.id
            WHERE elections.show_results=1
            AND elections.country=country.id
            AND elections.is_active=1
            AND election_types.id=elections.election_type";
           
            if (sess::get('type')!='')
                $sql .= " AND elections.election_type=".sess::get('type');

            if (sess::get('round_num')>0)
                $sql .= " AND elections.round_num=".sess::get('round_num');

            if (sess::get('country')!='')
                $sql .= " AND country.id=".sess::get('country');

            if (sess::get('region')!='')
                $sql .= " AND country.region=".sess::get('region');

            $sql .= $this->get_dates();

            $sql .= " GROUP BY elections.id
            ORDER BY regions.region ASC, country.country_name ASC, elections.order_date DESC";
        //echo $sql;
        $db = new Db();
        $db->Query($sql);
        $numrows = $db->GetAffectedRows();
        if (sess::get('type')=='' || sess::get('type')=='any' || sess::get('type')==4) {
            $sql1 = "SELECT
                elections.id AS elid,
                candidates_ref.order_id AS order_id,
                candidates_ref.provision_at_stake,
                candidates_ref.id AS refid,
                candidates_ref.votes_cast,
                candidates_ref.votes_cast_percentage
            FROM candidates_ref
            LEFT JOIN elections ON elections.id=candidates_ref.election
            LEFT JOIN round_texts ON elections.round_num=round_texts.id
            LEFT JOIN country ON elections.country=country.id
            WHERE elections.show_results=1
                AND elections.id=candidates_ref.election
                AND elections.is_active=1";
            if (sess::get('round_num')>0)
                $sql .= " AND elections.round_num=".sess::get('round_num');

            if (sess::get('country')!='')
                $sql .= " AND country.id=".sess::get('country');

            if (sess::get('region')!='')
                $sql .= " AND country.region=".sess::get('region');

            $sql .= $this->get_dates();
            $sql .= " ORDER BY elections.order_date DESC, candidates_ref.order_id ASC";

            $db1 = new Db();
            $db1->Query($sql1);
            $refrow = $db1->GetAffectedRows();
            while($rows = $db1->fetchAssoc())
                $refs[$rows['elid']][$rows['refid']] = $rows;
        }
        
        if ($numrows>0) {
            $str = '<table><tr>
                <th>Region/ Country</th>
                <th>Election Type</th>
                <th>Date</th>
                <th>Office at Stake</th>
                <th>Term of Office</th>
                <th>Voter Turnout</th>
            </tr>';
        while($data = $db->fetchAssoc()) {
            if ($data['round_num']<=11) {
                if ($data['election_type']==4) {
                    foreach($refs[$data['id']] as $refid => $refdata) {
                        $counter = count($refs[$data['id']]);
                        $reg_voters = ($data['reg_voters']<0) ? 'N/A' : number_format($data['reg_voters'],0);
                        $refvotes_cast = ($refdata['votes_cast']<0) ? 'N/A' : number_format($refdata['votes_cast'],0);
                        $refvotes_castp = ($refdata['votes_cast_percentage']<0) ? 'N/A' : $refdata['votes_cast_percentage'].'%';
                        //($refvotes_cast==$refvotes_castp?$refvotes_cast:$refvotes_cast.' '.$refvotes_castp)
                        $str .= '<tr><td>';
                        if($data['region_name']!='')
                            $str .= $data['region_name'].'/ ';
                        $str .= $data['thecountry'].' </td>
                        <td>'.$data['thetype'].($counter>1?' Provision '.$refdata['order_id']:'').'</td>
                        <td>'.$data['thedate'].'</td>
                        <td>'.html_entity_decode($refdata['provision_at_stake']).'</td>
                        <td>&nbsp;</td>
                        <td>'.$refvotes_castp.'</td>
                        </tr>';
                    }
                } else if ($data['election_type']==2 || $data['election_type']==3) {
                    $reg_voters = ($data['reg_voters']<0) ? 'N/A' : number_format($data['reg_voters'],0);
                    if ($data['assembly_type']=='uni') {
                        $votes_cast = ($data['votes_cast']<0) ? 'N/A' :number_format($data['votes_cast'],0);
                        $votes_castp = ($data['votes_cast_percentage']<0) ? 'N/A' :$data['votes_cast_percentage'].'%';
                        $str .= '<tr><td>';
                        if($data['region_name']!='')
                            $str .= $data['region_name'].'/ ';
                        $str .= $data['thecountry'].' </td>
                        <td>'.$data['thetype'].'</td>
                        <td>'.$data['thedate'].'</td>
                       	<td>';
                        if ($data['assembly_seats_at_stake']>0) {
                            $str .= $data['assembly_seats_at_stake'].' seats in '.$data['assembly_name'];
                        }
                        $str .= '&nbsp;</td>
                       	<td>&nbsp;</td>
                       	<td>'.$votes_castp.'</td></tr>';
                    } else {
                        $votes_cast = ($data['votes_cast']<0) ? 'N/A' :number_format($data['votes_cast'],0);
                        $votes_castp = ($data['votes_cast_percentage']<0) ? 'N/A' :$data['votes_cast_percentage'].'%';
                        $votes_cast1 = ($data['votes_cast1']<0) ? 'N/A' :number_format($data['votes_cast1'],0);
                        $votes_castp1 = ($data['votes_cast1_percentage']<0) ? 'N/A' :$data['votes_cast1_percentage'].'%';
                        if ($data['show_uh_results']==1) {
                            $str .= '<tr><td>';
                            if($data['region_name']!='')
                                $str .= $data['region_name'].'/ ';
                            $str .= $data['thecountry'].' </td>
                               	<td>'.$data['thetype'].' '.($data['show_lh_results']==1?' '.$data['uh_name']:'').'</td>
                               	<td>'.$data['thedate'].'</td>
                               	<td>';
                            if ($data['uh_seats_at_stake']>0) {
                                $str .= $data['uh_seats_at_stake'].' seats in '.$data['uh_name'];
                            }
                            $str .= '&nbsp;</td>
                            	<td>&nbsp;</td>
                            	<td>'.$votes_castp.'</td></tr>';
                        }

                        if ($data['show_lh_results']==1) {
                            $str .= '<tr><td>';
                            if($data['region_name']!='')
                                $str .= $data['region_name'].'/ ';
                            $str .= $data['thecountry'].' </td>
                            	<td>'.$data['thetype'].' '.($data['show_uh_results']==1?' '.$data['lh_name']:'').'</td>
                            	<td>'.$data['thedate'].'</td>
                            	<td>';
                                if ($data['lh_seats_at_stake']>0) {
                                    $str .= $data['lh_seats_at_stake'].' seats in '.$data['lh_name'];
                                }
                                $str .= '&nbsp;</td>
                            	<td>&nbsp;</td>
                            	<td>'.$votes_castp1.'</td></tr>';
                        }
                    }
                } else {
                    $reg_voters = ($data['reg_voters']<0) ? 'N/A' : number_format($data['reg_voters'],0);
                    $votes_cast = ($data['votes_cast']<0) ? 'N/A' :number_format($data['votes_cast'],0);
                    $votes_castp = ($data['votes_cast_percentage']<0) ? 'N/A' :$data['votes_cast_percentage'].'%';
                    $str .= '<tr><td>';
                    if($data['region_name']!='')
                        $str .= $data['region_name'].'/ ';
                    $str .= $data['thecountry'].' </td>
                        <td>'.$data['thetype'].'</td>
                    	<td>'.$data['thedate'].'</td>
                    	<td>'.$data['chief_title'].'</td>
                    	<td>'.$data['chief_term'].'</td>
                    	<td>'.$votes_castp.'</td>
                    </tr>';
                }
            }
        }
        $str .= '</table>';
    }
    return $str;
}
    function report_average_turnout() {
        $sql = "SELECT regions.region as region_name,
            election_types.type_name,
            elections.id AS electionid,
            elections.election_type,
            elections.show_lh_results,
            elections.show_uh_results,
            votes_cast_percentage as votes_cast_percentage,
            votes_cast1_percentage as votes_cast1_percentage,
            elections.assembly_type
            FROM elections, election_types, country, regions
            WHERE elections.show_results=1
            AND elections.is_active=1
            AND elections.round_num<11
            AND elections.country=country.id
            AND regions.id=country.region
            AND election_types.id=elections.election_type
            AND country.region=".sess::get('region');

            $sql .= $this->get_dates();
            $sql .= " GROUP BY elections.id
            ORDER BY elections.election_type ASC";

            $sql1 = "SELECT
            elections.id AS elid,
            COUNT(candidates_ref.id) as counter,
            SUM(candidates_ref.votes_cast_percentage) as percentage
            FROM candidates_ref
            LEFT JOIN elections ON elections.id=candidates_ref.election
            LEFT JOIN country ON elections.country=country.id
            WHERE elections.show_results=1
                AND elections.id=candidates_ref.election
                AND elections.is_active=1
                AND candidates_ref.votes_cast_percentage>0
                AND country.region=".sess::get('region');

            $sql1 .= $this->get_dates();
            $sql1 .= " GROUP BY elections.election_type ORDER BY elections.order_date DESC";

            $db1 = new Db();
            $db1->Query($sql1);
            $rows = $db1->fetchAssoc();

        $db = new Db();
        $db->Query($sql);
        $numrows = $db->GetAffectedRows();
        if ($numrows>0) {
            $total = 0;
            $ctr = 0;
            while($data = $db->fetchAssoc()) {
                $thetype = $data['election_type'];
                $region = $data['region_name'];
                $id = $data['electionid'];
                $newarray[$thetype][$id] = $data;
                if ($thetype==1) {
                    $realpresctr++;
                    if ($data['votes_cast_percentage']>0) {
                        $presctr++;
                        $prestotal = $prestotal+$data['votes_cast_percentage'];
                    }
                } else if ($thetype==2) {
                    $reallegctr++;
                    if ($data['assembly_type']=='bi') {
                        if ($data['show_uh_results']==1 && $data['votes_cast_percentage']>0) {
                            $legctr++;
                            $legtotal = $legtotal+$data['votes_cast_percentage'];
                        }

                        if ($data['show_lh_results']==1 && $data['votes_cast1_percentage']>0) {
                            $legctr++;
                            $legtotal = $legtotal+$data['votes_cast1_percentage'];
                        }
                    } else if ($data['votes_cast_percentage']>0) {
                        $legctr++;
                        $legtotal = $legtotal+$data['votes_cast_percentage'];
                    }
                } else if ($thetype==3) {
                    $realparlctr++;
                    if ($data['assembly_type']=='bi' && $data['votes_cast_percentage']>0) {
                        if ($data['show_uh_results']==1) {
                            $parlctr++;
                            $parltotal = $parltotal+$data['votes_cast_percentage'];
                        }

                        if ($data['show_lh_results']==1 && $data['votes_cast1_percentage']>0) {
                            $parlctr++;
                            $parltotal = $parltotal+$data['votes_cast1_percentage'];
                        }
                    } else if ($data['votes_cast_percentage']>0) {
                        $parlctr++;
                        $parltotal = $parltotal+$data['votes_cast_percentage'];
                    }
                } else if ($thetype==4) {
                    $realrefctr++;
                }
            }
            $timerange = Common::get_double_months(sess::get('start_month')).' '.sess::get('start_year').' -
            '.Common::get_double_months(sess::get('end_month')).' '.sess::get('end_year');

            $thetype = $data['election_type'];
            $str = '<table><tr>
                <th>Region</th>
                <th>Election Type</th>
                <th>Number of Elections</th>
                <th>Average Turnout</th>
                <th>Date(s)</th>
            </tr>
            <tr>
                <td>'.$region.' </td>
                <td>Presidential</td>
                <td>'.($presctr>0?$presctr:'N/A').($realpresctr>0?' ('.$realpresctr.')':'').'</td>
                <td>';
                $str .= ($presctr>0) ? round($prestotal/$presctr,2).'%' : 'N/A';
                $str .= '</td>
                <td>'.$timerange.'</td>
            </tr>
            <tr>
                <td>'.$region.' </td>
                <td>Parliamentary</td>
                <td>'.($parlctr>0?$parlctr:'N/A').($realparlctr>0?' ('.$realparlctr.')':'').'</td>
                <td>';
                $str .= ($parlctr>0) ? round($parltotal/$parlctr,2).'%' : 'N/A';
                $str .= '</td>
                <td>'.$timerange.'</td>
            </tr>
            <tr>
                <td>'.$region.' </td>
                <td>Legislative</td>
                <td>'.($legctr>0?$legctr:'N/A').($reallegctr>0?' ('.$reallegctr.')':'').'</td>
                <td>';
                $str .= ($legctr>0) ? round($legtotal/$legctr,2).'%' : 'N/A';
                $str .= '</td>
                <td>'.$timerange.'</td>
            </tr>
            <tr>
                <td>'.$region.' </td>
                <td>Referendum</td>
                <td>'.($rows['counter']>0?$rows['counter']:'N/A').($realrefctr>0?' ('.$realrefctr.')':'').'</td>
                <td>';
                $str .= ($rows['counter']>0) ? round($rows['percentage']/$rows['counter'],2).'%' : 'N/A';
                $str .= '</td>
                <td>'.$timerange.'</td>
            </tr>
            </table>';
            return $str;
        }
    }
}
?>
