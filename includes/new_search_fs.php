<?php
function referendum_results($data) {
    $ret = '<table class="main-results-table"><thead><tr>
    <th>Region/ Country</th>
    <th>Election Type</th>
    <th>Date</th>
    <th>Referenda Issue</th>
    <th>Chief Of State</th>
    <th>Head of Government</th>
    <th>Assembly or Houses</th>
    </tr></thead><tbody>';
    foreach($data as $election => $edata) {
        if($edata['assembly_type']=='bi') {
            $assembly = html_entity_decode($edata['upper_house_name'].'/ '. $edata['lower_house_name']);
        } else {
            $assembly = html_entity_decode($edata['complete_assembly_name']);
        }
        $ret .= '<tr><td>';
        if($edata['region_name']!='')
            $ret .= $edata['region_name'].'<br />';
        $ret .= html_entity_decode($edata['thecountry']).' </td>
        <td>'.$edata['thetype'].'</td>
        <td>'.$edata['thedate'].'</td>
        <td>'.html_entity_decode($edata['candidate_ref']).'</td>
        <td>'.(trim($edata['chief'])!=''?html_entity_decode($edata['chief']):'&nbsp;').'</td>
        <td>'.(trim($edata['thehead'])!=''?html_entity_decode($edata['thehead']):'&nbsp;').'</td>
        <td>'.html_entity_decode($assembly).'</td>
        </tr>';
    }
    $ret .= '</tbody></table>';
    return $ret;
}

function presidential_results($data) {
    $ret = '<table class="main-results-table"><thead><tr>
    <th>Region/ Country</th>
    <th>Election Type</th>
    <th>Date</th>';

    // if no keyword specified
    if (sess::get('keyword')!='' || sess::get('winner')!='')
        $ret .= '<th>Candidate</th>';

    $ret .= '<th>Chief Of State</th>
    <th>Head of Government</th>
    <th>Assembly or Houses</th>
    </tr></thead><tbody>';
    foreach($data as $election => $edata) {
        if($edata['assembly_type']=='bi') {
            $assembly = html_entity_decode($edata['upper_house_name'].'/ '. $edata['lower_house_name']);
        } else {
            $assembly = html_entity_decode($edata['complete_assembly_name']);
        }

        $ret .= '<tr><td>';
        if($edata['region_name']!='')
            $ret .= $edata['region_name'].'<br />';
        $ret .= html_entity_decode($edata['thecountry']).' </td>
        <td>'.$edata['thetype'].'</td>
        <td>'.$edata['thedate'].'</td>';
        // if no keyword specified
        if (sess::get('keyword')!='' || sess::get('winner')!='')
            $ret .= '<td>'.html_entity_decode($edata['candidate_pres']).'</td>';

        $ret .= '<td>'.(trim($edata['chief'])!=''?html_entity_decode($edata['chief']):'&nbsp;').'</td>';
        $ret .= '<td>'.(trim($edata['thehead'])!=''?html_entity_decode($edata['thehead']):'&nbsp;').'</td>
        <td>'.html_entity_decode($assembly).'</td>
        </tr>';
    }
    $ret .= '</tbody></table>';
    return $ret;
}

function legislative_results($data) {
    $ret = '<table class="main-results-table"><thead><tr>
    <th>Region/ Country</th>
    <th>Election Type</th>
    <th>Date</th>';

    // if no keyword specified
    if (sess::get('keyword')!='' || sess::get('party_leader')!='' || sess::get('winner')!='') {
        $ret .= '<th>Party Name</th>
        <th>Party Leader</th>';
    } else {
        $ret .= '<th>Chief Of State</th>';
    }

    $ret .= '<th>Head of Government</th>
        <th>Assembly or Houses</th>
    </tr></thead><tbody>';
    foreach($data as $election => $edata) {
        if($edata['assembly_type']=='bi') {
            $assembly = html_entity_decode($edata['upper_house_name'].'/ '. $edata['lower_house_name']);
        } else {
            $assembly = html_entity_decode($edata['complete_assembly_name']);
        }

        $ret .= '<tr><td>';
        if($edata['region_name']!='')
            $ret .= $edata['region_name'].'<br />';
        $ret .= html_entity_decode($edata['thecountry']).' </td>
        <td>'.$edata['thetype'].'</td>
        <td>'.$edata['thedate'].'</td>';
        if (sess::get('keyword')!='' || sess::get('party_leader')!='' || sess::get('winner')!='') {
            $ret .= '<td>'.html_entity_decode($edata['candidate']).'</td>';
            $ret .= '<td>'.(trim($edata['party_leader_name'])!=''?html_entity_decode($edata['party_leader_name']):'&nbsp;').'</td>';
        } else {
            $ret .= '<td>'.(trim($edata['chief'])!=''?html_entity_decode($edata['chief']):'&nbsp;').'</td>';
        }
        $ret .= '<td>'.(trim($edata['thehead'])!=''?html_entity_decode($edata['thehead']):'&nbsp;').'</td>
        <td>'.html_entity_decode($assembly).'</td></tr>';
    }
    $ret .= '</tbody></table>';
    return $ret;
}
?>
