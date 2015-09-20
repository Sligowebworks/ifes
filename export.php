<?php
session_start();
ob_start();
ini_set('max_execution_time', 0);
require_once("includes/conf.php");
require_once("includes/Export.Class.php");
$id = (is_numeric($_REQUEST['ID'])) ? $_REQUEST['ID']: 0;
switch($id) {
    case 7:
    $rpt = new Export();
    $str = $rpt->report_average_turnout();
    break;

    case 6:
    $rpt = new Export();
    $str = $rpt->report_chief();
    break;
    
    case 5:
    $rpt = new Export();
    $str = $rpt->report_govt();
    break;
    
    case 3:
    $rpt = new Export();
    $str = $rpt->report_presidential();
    break;
    
    case 2:
    $rpt = new Export();
    $str = $rpt->report_parties();
    break;
    
    case 1:
    $rpt = new Export();
    $str = $rpt->report_turnout();
    break;
    
    default:
    ob_end_clean();
    exit();
    break;
}

header("Pragma: must-revalidate");
header("Cache-Control: no-store, no-cache");
header("Content-type: application/ms-excel");
header("Content-disposition: filename=export_".date('Y-m-d').".xls");
header("Content-length: " . strlen($str));
echo $str;

?>
