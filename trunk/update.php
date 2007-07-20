<?php
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

$sqlStr = "CREATE TABLE `".Table_prefix."comments` (
  `id_comment` int(11) NOT NULL auto_increment,
  `id_post` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `web` varchar(250) default NULL,
  `content` text NOT NULL,
  `ip_user` varchar(50) NOT NULL,
  `comment_date` datetime NOT NULL,
  `spam` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id_comment`)
) ENGINE = MYISAM ;";

$db->ejecutarConsulta($sqlStr);

$sqlStr = "CREATE TABLE `".Table_prefix."options` (
  `name` varchar(100) NOT NULL,
  `val` varchar(255) NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE = MYISAM ;";

$db->ejecutarConsulta($sqlStr);

$sqlStr = "INSERT INTO `".Table_prefix."options` VALUES ('url_friendly', '1');";
		
$db->ejecutarConsulta($sqlStr);

$sqlStr = "INSERT INTO `".Table_prefix."options` VALUES ('rich_text', '0');";

$db->ejecutarConsulta($sqlStr);

$sqlStr = "ALTER TABLE ".Table_prefix."config DROP url_friendly";

$db->ejecutarConsulta($sqlStr);

$sqlStr = "ALTER TABLE ".Table_prefix."config DROP rich_text";

$db->ejecutarConsulta($sqlStr);

$sqlStr = "INSERT INTO `".Table_prefix."options` VALUES ('offset_city', 'Mexico/General');";
		
$db->ejecutarConsulta($sqlStr);
		
$sqlStr = "INSERT INTO `".Table_prefix."options` VALUES ('offset_time', '-6');";
		
$db->ejecutarConsulta($sqlStr);

echo "<p><em>Finished!</em></p>";
echo "<p>Now you are running on the new version!!!</p>";

?>
