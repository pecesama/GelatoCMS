<?php
if(!defined('entry')) define('entry',true);  
/* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */
?>
<?php

$configFile = dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";
	
if (!file_exists($configFile)) {
	$mensaje = "
			<h3 class=\"important\">Error reading configuration file</h3>			
			<p>There doesn't seem to be a <code>config.php</code> file. I need this before we can get started.</p>
			<p>This either means that you did not rename the <code>config-sample.php</code> file to <code>config.php</code>.</p>";
	die($mensaje);
} else {
	require($configFile);
} 

$db = new Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);

/*$sqlStr = "INSERT INTO `".Table_prefix."options` VALUES ('allow_comments', '0');";
		
$db->ejecutarConsulta($sqlStr);

$sqlStr = "INSERT INTO `".Table_prefix."options` VALUES ('offset_city', 'Mexico/General');";
		
$db->ejecutarConsulta($sqlStr);
		
$sqlStr = "INSERT INTO `".Table_prefix."options` VALUES ('offset_time', '-6');";

$db->ejecutarConsulta($sqlStr);
		
$sqlStr = "INSERT INTO `".Table_prefix."options` VALUES ('shorten_links', '0');";
		
$db->ejecutarConsulta($sqlStr);

 AFTER v0.90 */
 
$sqlStr = "INSERT INTO `".Table_prefix."options` VALUES ('rss_import_frec', '5 minutes');";
		
$db->ejecutarConsulta($sqlStr);


$sqlStr = "CREATE TABLE `".Table_prefix."feeds` (
  `id_feed` int(11) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL default '1',
  `updated_at` datetime NOT NULL,
  `error` tinyint(1) NOT NULL default '0',
  `credits` int(1) NOT NULL default '0',
  `site_url` varchar(255) NOT NULL,
  `id_user` int(10) NOT NULL,
  PRIMARY KEY  (`id_feed`)
) ENGINE=MyISAM ;";

$db->ejecutarConsulta($sqlStr);

if(!is_dir('upload/CACHE')){
	mkdir('upload/CACHE');
	chmod('upload/CACHE',777);
}

echo "<p><em>Finished!</em></p>";
echo "<p>Now you are running on the new <strong>After 0.90</strong> version!!!</p>";

?>
