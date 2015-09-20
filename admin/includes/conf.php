<?php
session_start();
ob_start();
ini_set('display_errors', 1);

// Report all errors except E_NOTICE
// This is the default value set in php.ini
error_reporting(E_ALL ^ E_NOTICE);

ini_set('default_charset', 'UTF-8');
ini_set('include_path', '.:/webs/php-4.3.5/lib/php:./includes/:'.$_SERVER['DOCUMENT_ROOT'].'/admin/');

define("DB_HOST", "localhost");
define("DB_USER", "eguide");
define("DB_PASS", 'n3w$iTE!');
define("DB_NAME", "eguide");

define('CLIENT_NAME', 'ElectionGuide');
define('APP_NAME', 'Administrator');
define('SESS_NAME', 'ifes_sess'); //call it anything unique

define('REL_ROOT', '/');
// define('REL_ROOT', '');  - changed with previous on March 08
define('REL_ADMIN_ROOT', '/admin/');

define('WEB_ROOT', 'http://www.electionguide.org'.REL_ROOT);
define('ADMIN_WEB_ROOT', 'http://www.electionguide.org'.REL_ADMIN_ROOT);
define('MACHINE_ROOT', '/var/www/html/eguide_site'.REL_ADMIN_ROOT);

require_once(MACHINE_ROOT.'includes/compat/array_diff_key.php');
require_once(MACHINE_ROOT.'includes/compat/http_build_query.php');
require_once(MACHINE_ROOT.'includes/common.php');
require_once(MACHINE_ROOT.'includes/Login.Class.php');
require_once(MACHINE_ROOT.'includes/Sticky.Class.php');
require_once(MACHINE_ROOT.'includes/Db.Class.php');
require_once(MACHINE_ROOT.'includes/Dbtable.Class.php');
require_once(MACHINE_ROOT.'includes/Common.Dbtable.Class.php');
require_once(MACHINE_ROOT.'includes/Pager/Pager.php');
require_once(MACHINE_ROOT.'includes/Pager/Common.php');
require_once(MACHINE_ROOT.'includes/Pager/Sliding.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/SafeSQL.class.php');
?>
