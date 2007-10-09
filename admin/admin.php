<?php
if(!defined('entry')) define('entry',true);  
/* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */

include("../classes/user.class.php");
include("../classes/functions.php");
require_once("../classes/configuration.class.php");
require_once('../entry.php');

if ($user->isAdmin()) {
	
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>gelato :: <?php echo __("admin users")?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="gelato cms <?php echo version();?>" />
		<link rel="shortcut icon" href="<?php echo $conf->urlGelato;?>/images/favicon.ico" />
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/jquery.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/tools.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/jquery.tablesorter.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){
			$("#admin-table").tablesorter({        
				headers: {            
					2: {sorter: false},            
					3: {sorter: false} 
				} 
			}); 			
			$("#divMessages").fadeOut(5000,function(){
				$("#divMessages").css({display:"none"});
			});
		});
		</script>
		<style type="text/css" media="screen">	
			@import "<?php echo $conf->urlGelato;?>/admin/css/style.css";
		</style>
	</head>
	
	<body>
		<div id="div-process" style="display:none;"><?php echo __("Processing request&hellip;")?></div>
		<div id="cont">
			<div id="head">
				<h1><a href="<?php echo $conf->urlGelato;?>/admin/index.php" title="gelato :: <?php echo __("home")?>">gelato cms</a></h1>
				<ul id="nav">
					<li><a href="<?php echo $conf->urlGelato;?>/" title="Take me to the tumblelog"><?php echo __("View Tumblelog")?></a></li>
					<li><a href="close.php" title="Log off" onclick="return exit('div-process','<?php echo $conf->urlGelato;?>/admin/ajax.php?action=close');"><?php echo __("Log out")?></a></li>
			  	</ul>
			</div>
			<div id="main">				
				
				<div class="box">
					<ul class="menu manage">
					<h3>Manage</h3>
					<li><a href="<?php echo $conf->urlGelato;?>/admin/settings.php"><?php echo __("Settings")?></a></li>
					<li><a href="<?php echo $conf->urlGelato;?>/admin/index.php"><?php echo __("Posts")?></a></li>
					<li><a href="<?php echo $conf->urlGelato;?>/admin/user.php"><?php echo __("Add user")?></a></li>
					<li class="selected"><a><?php echo __("Users")?></a></li>
					</ul>
					<p>&nbsp;</p>
<?php				
						if (isset($_GET["added"])) {
							if ($_GET["added"]=="true") {
								echo "<div class=\"exito\" id=\"divMessages\">".__("The user has been added successfully.")."</div>";
							}
						}
						
						if (isset($_GET["delete"])) {
							if ($_GET["delete"]=="true") {
								echo "<div class=\"exito\" id=\"divMessages\">".__("The user has been eliminated successfully.")."</div>";
							}
						}
						
						if (isset($_GET["modified"])) {
							if ($_GET["modified"]=="true") {
								echo "<div class=\"exito\" id=\"divMessages\">".__("The user has been modified successfully.")."</div>";
							}
						}
						
						if (isset($_GET["error"])) {
							if ($_GET["error"]==1) {
								echo "<div class=\"error\" id=\"divMessages\">".__("The username is not available.")."</div>";
							} elseif ($_GET["error"]==2) {
								echo "<div class=\"error\" id=\"divMessages\"><strong>".__("Error on the database server:.")." </strong>".$_GET["des"]."</div>";
							}
						}
?>						
						<div class="tabla">
						<table border="0" cellpadding="0" cellspacing="0" id="admin-table">
							<thead>
								<tr>
									<th scope="col"><?php echo __("Login")?></th>
									<th scope="col"><?php echo __("Name")?></th>
									<th scope="col"><?php echo __("Actions")?></th>
									<th scope="col"></th>
								</tr>
							</thead>
							<tbody>
<?php
					$odd=false;
					$rs = $user->getUsers();
					if ($user->contarRegistros()>0) {
						while($register = mysql_fetch_array($rs)) {
?>
							<tr <?php if ($odd) { echo 'class="odd"'; } $odd=!$odd; ?>>
								<td><?php echo $register["login"]."\n"; ?></td>
								<td><?php echo $register["name"]."\n"; ?></td>
								<td><a href="user.php?edit=<?php echo $register["id_user"]; ?>"><?php echo __("Edit")?></a></td>								
								<td><?php if ($_SESSION["user_id"] != $register["id_user"]) { ?><a href="user.php?delete=<?php echo $register["id_user"]; ?>"><?php echo __("Delete")?></a><?php } ?>&nbsp;</td>
								
							</tr>
<?php
						}
					}
					else {
?>
						<tr> 
							<td colspan="4"><div class="exito"><?php echo __("No users available.")?></div></td>
						</tr>
<?php
					}
?>
							</tbody>
						</table>
					</div>

					<div class="footer-box">&nbsp;</div>
				</div>
			</div>
			<div id="foot">
				<a href="http://www.gelatocms.com/" title="gelato CMS">gelato CMS</a> :: PHP/MySQL Tumblelog Content Management System.
			</div>
		</div>
	</body>
	</html>
<?php
} else {
	header("Location: ".$conf->urlGelato."/login.php");
}
?>