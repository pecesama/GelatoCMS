<?php
if(!defined('entry'))define('entry', true);
/* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */
?>
<?php
header("Cache-Control: no-cache, must-revalidate");

require_once('entry.php');
global $user, $conf;

if ($user->isAuthenticated()) {
	header("Location: ".$conf->urlGelato."/admin/index.php");
} else {
	if (isset($_POST["pass"]) && isset($_POST["login"])) {		
		//print "<pre>"; print_r($_SESSION); print "</pre>";die();
		if ($user->validateUser($_POST['login'], md5($_POST['pass']))) {
			header("Location: ".$conf->urlGelato."/admin/index.php");
		} else {
			header("Location: ".$conf->urlGelato."/login.php?error=1");
		}
	} else {
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>gelato :: <?php echo __("login screen")?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="gelato cms <?php echo version();?>" />
		<link rel="shortcut icon" href="<?php echo $conf->urlGelato;?>/images/favicon.ico" />
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/mootools.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/sortable.js"></script>
		<style type="text/css" media="screen">	
			@import "<?php echo $conf->urlGelato;?>/admin/css/style.css";
		</style>
	</head>
	
	<body>
		<div id="div-process" style="display:none;"><?php echo __("Processing request...")?></div>
		<div id="cont">
			<div id="head">
				<h1><a href="<?php echo $conf->urlGelato;?>/" title="gelato :: <?php echo __("home")?>">gelato cms</a></h1>
				<ul id="nav">
					<li><a href="<?php echo $conf->urlGelato;?>/" title="<?php echo __("Take me to the tumblelog")?>"><?php echo __("Back to the Tumblelog")?></a></li>
			  	</ul>
			</div>
			<div id="main">				
				
				<div class="box">
					<ul class="menu manage">
					<h3><?php echo __("Start session")?></h3>
					<li class="selected"><a><?php echo __("Login")?></a></li>
					</ul>
				
					<div class="tabla">

								<form action="login.php" method="post" id="valida" autocomplete="off" class="newpost">
									<fieldset>
									<ul>
										<li><label for="login"><?php echo __("User:")?></label>
											<input id="login" name="login" type="text" class="txt" /></li>
										<li><label for="pass"><?php echo __("Password:")?></label>
											<input id="pass" name="pass" type="password" class="txt" /></li>
										<li><label for="save_pass"><?php echo __("Remember me:")?></label>
											<input id="save_pass" name="save_pass" type="checkbox" /></li>
										<li><input name="btnLogin" type="submit" value="Login" /></li>
									</ul>
									</fieldset>
								</form>
<?php				
								if (isset($_GET["error"])) {
									if ($_GET["error"]==1) {
?>
								<div class="error">
									<?php echo __("&nbsp;You must be registered to use gelato.")?>
								</div>
<?php
									}
									elseif ($_GET["error"]==2) {
?>
								<div class="error">
									<?php echo __("&nbsp;You must be logged on the system.")?>
								</div>
<?php
									}									
								}
?>
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
	}
}
?>