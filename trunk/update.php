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

$sqlStr = "INSERT INTO `".Table_prefix."options` VALUES ('allow_comments', '0');";
		
$db->ejecutarConsulta($sqlStr);

$sqlStr = "INSERT INTO `".Table_prefix."options` VALUES ('offset_city', 'Mexico/General');";
		
$db->ejecutarConsulta($sqlStr);
		
$sqlStr = "INSERT INTO `".Table_prefix."options` VALUES ('offset_time', '-6');";

$db->ejecutarConsulta($sqlStr);
		
$sqlStr = "INSERT INTO `".Table_prefix."options` VALUES ('shorten_links', '0');";
		
$db->ejecutarConsulta($sqlStr);

echo "<p><em>Finished!</em></p>";
echo "<p>Now you are running on the new <strong>0.90</strong> version!!!</p>";

?>
