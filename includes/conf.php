<?php
session_start();
ob_start();
ini_set('display_errors', 1);

// Report all errors except E_NOTICE
// This is the default value set in php.ini
error_reporting(E_ALL ^ E_NOTICE);

ini_set('max_execution_time', 90);
ini_set('include_path', '.:/webs/php-4.3.5/lib/php:./includes/:'.$_SERVER['DOCUMENT_ROOT'].'/admin/:'.$_SERVER['DOCUMENT_ROOT'].'/admin/includes/');

define("DB_HOST", "localhost");
define("DB_USER", "eguide");
define("DB_PASS", 'n3w$iTE!');
define("DB_NAME", "eguide");

define("USERSESS", "electuser");
define('REL_ROOT', '/');
define('WEB_ROOT', 'http://www.electionguide.org'.REL_ROOT);

require_once('admin/includes/compat/array_diff_key.php');
require_once('admin/includes/compat/http_build_query.php');
require_once('admin/includes/ConvertCharset.class.php');
require_once('admin/includes/common.php');
require_once('admin/includes/Db.Class.php');
require_once('includes/Session.Class.php');
require_once('includes/Message.Class.php');
require_once('admin/includes/Pager/Pager.php');
require_once('admin/includes/Pager/Common.php');
require_once('admin/includes/Pager/Sliding.php');
require_once('includes/SafeSQL.class.php');
?>
