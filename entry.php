<?php
 if(!defined('entry') || !entry) die('Not a valid page');
/*
 * Created on Sep 15, 2007
 *
 * Known Entry Points 
 * install.php
 * index.php
 * login.php
 * update.php
 * rss.php
 * admin/index.php
 * admin/close.php
 * admin/ajax.php
 * admin/settings.php
 * admin/options.php
 * admin/admin.php
 * admin/comments.php
 * admin/users.php
 *admin/form.autosave.php
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
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'mysql_connection.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'streams.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'gettext.class.php');
require_once(Absolute_Path.'classes'.DIRECTORY_SEPARATOR.'lang.functions.php');
        
$user = new user();
$conf = new configuration();
$tumble = new gelato();


session_start();

?>
