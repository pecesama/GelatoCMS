<?php
ob_start();
if(!defined('entry') || !entry) die('Not a valid page');


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

if (!extension_loaded('json')) {			
	require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'JSON.php');
	$GLOBALS['JSON_OBJECT'] = new Services_JSON();
	
	function json_encode($value) {
		return $GLOBALS['JSON_OBJECT']->encode($value); 
	}
	
	function json_decode($value, $none) {
		return $GLOBALS['JSON_OBJECT']->decode($value); 
	}
}

require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'configuration.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'util.class.php');
//require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'functions.php');
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
	$conf = new configuration();
	$tumble = new gelato();
	$user = new user();
	
	session_start();
	
	util::init_plugins();
	$trigger = plugin::instance();
	
	$trigger->call('gelato_init');	

	$feeds = new feeds();
	$feeds->updateFeeds();
	unset($feeds);
}
?>