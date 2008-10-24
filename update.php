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

require('entry.php');
global $configFile, $db;

if (!file_exists($configFile)) {
	$mensaje = "
			<h3 class=\"important\">Error reading configuration file</h3>
			<p>There doesn't seem to be a <code>config.php</code> file. I need this before we can get started.</p>
			<p>This either means that you did not rename the <code>config-sample.php</code> file to <code>config.php</code>.</p>";
	die($mensaje);
} else {
	require_once($configFile);
}

$sqlStr = "INSERT INTO `".Table_prefix."options` VALUES ('check_version', '1');";

$db->ejecutarConsulta($sqlStr);

echo "<p><em>Finished!</em></p>";
echo "<p>Now you are running on the new <strong>gelato vaniglia</strong> version!!!</p>";
echo "<p><a href="index.php">Return to your tumblelog</a></p>";

?>
