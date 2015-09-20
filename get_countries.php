<?php
include('includes/conf.php');

$region = ($_REQUEST['ID']) ? $_REQUEST['ID']: 0;
$str = '';
    if ($region!=0) {
        $countries = Common::get_countries_by_region($region);
        $str = '<select name="country" size="1" style="width:200px">';
        
        foreach($countries as $code => $event) {
             $chk = ($code==sess::get('country')) ? ' selected="selected"': '';
            $str .= '<option value="'.$code.'"'.$chk.'> '.$event.' </option>';
        }
        
        $str .= '</select>';
        echo $str;
        exit();
    } 
?>
