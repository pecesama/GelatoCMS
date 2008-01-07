<?php
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

 
// PHP settings specific to Gelato
ini_set('pcre.backtrack_limit', '10000');

// Globals to be used throughout the application        
$configFile = dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";

if (!file_exists($configFile)) {
	header("Location: install.php");  
} else {
        require(dirname(__FILE__).DIRECTORY_SEPARATOR."config.php");
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

// Globals to be used throughout the application
$user = new user();
$tumble = new gelato();
$conf = new configuration();
$db = new Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);


session_start();

$feeds = new feeds();
$feeds->updateFeeds();
unset($feeds);

?>
