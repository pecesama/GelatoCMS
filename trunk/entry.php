<?php
ob_start();
 if(!defined('entry') || !entry) die('Not a valid page');
/*
 * Created on Sep 15, 2007
 * Modified on Sep 22, 2007
 *
 * Known Entry Points
 * api.php
 * install.php
 * index.php
 * login.php
 * update.php
 * rss.php
 * admin/bm.php
 * admin/index.php
 * admin/close.php
 * admin/ajax.php
 * admin/settings.php
 * admin/options.php
 * admin/admin.php
 * admin/comments.php
 * admin/user.php
 * admin/feeds.php
 * classes/imgsize.php
 */

error_reporting (E_ALL);
ini_set('display_errors', '1');

 
// PHP settings specific to Gelato
ini_set('pcre.backtrack_limit', '10000');
// Globals to be used throughout the application
define('Absolute_Path', dirname(__FILE__).DIRECTORY_SEPARATOR);
$installed = true;
$configFile = Absolute_Path.'config.php';

$dir = (strpos($_SERVER['REQUEST_URI'],'/admin')) ? "../" : "";

if (!file_exists($configFile) and basename($_SERVER['PHP_SELF'])!='install.php'){
	header("Location: {$dir}install.php");
	exit;
} else {	
	include(Absolute_Path.'classes/install.class.php');	
	$install = new Install();	
	if(!$install->is_gelato_installed()){
		if(basename($_SERVER['PHP_SELF'])!='install.php'){
				header("Location: {$dir}install.php");exit;
			}
		$installed = false;
	}
}

if($installed) {
	require($configFile);
}

require_once("classes/configuration.class.php");
require_once("classes/textile.class.php");
require_once("classes/gelato.class.php");
require_once("classes/templates.class.php");
require_once("classes/pagination.class.php");
require_once("classes/user.class.php");
require_once("classes/comments.class.php");
require_once("classes/feeds.class.php");
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'mysql_connection.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'streams.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'gettext.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'lang.functions.php');

if($installed){

	// Globals to be used throughout the application
	$user = new user();
	$tumble = new gelato();
	$conf = new configuration();
	$db = new Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);
	
	session_start();
	
	$feeds = new feeds();	
	$feeds->updateFeeds();	
	unset($feeds);
}
?>

