<?php
ob_start();
if(!defined('entry') || !entry) die('Not a valid page');
/*
 * Created on Sep 15, 2007
 * Modified on Oct 30, 2008
 *
 * Known Entry Points
 * api.php
 * archive.php
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
	include_once(Absolute_Path.'classes/install.class.php');
	$install = new Install();
	if(!$install->is_gelato_installed()){
		if(basename($_SERVER['PHP_SELF'])!='install.php'){
				header("Location: {$dir}install.php");exit;
			}
		$installed = false;
	}
}

if($installed) {
	require_once($configFile);
}

require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'configuration.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'functions.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'gelato.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'templates.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'themes.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'pagination.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'user.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'comments.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'feeds.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'mysql_connection.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'streams.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'gettext.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'lang.functions.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'plugin.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'plugins.class.php');

if($installed){

	// Globals to be used throughout the application
	$db = new Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);
	$user = new user();
	$tumble = new gelato();
	$conf = new configuration();	

	session_start();
	
	//print_r($conf->plugins);
	//die();
	init_plugins();
	$trigger = plugin::instance();
	
	//echo "plugins.instances: ";
	//print_r(plugins::$instances);
	//echo "<br />";
	//die();
	
	//echo "plugin.actions: ";
	//$plugEngine = plugin::instance();
	//print_r($plugEngine->actions);
	//die();
	
	$trigger->call('gelato_init');	

	$feeds = new feeds();
	$feeds->updateFeeds();
	unset($feeds);
}
?>