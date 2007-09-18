<?php
if(!defined('entry')) define('entry', true);
 /* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */
?>
<?php
require('../entry.php');
global $user, $conf, $tumble;
$template = new plantillas("admin");

$user = new user();
$conf = new configuration();
$isEdition = isset($_GET["edit"]);
$userId = ($isEdition) ? $_GET["edit"] : NULL;
if ($user->isAdmin()) {

	if (isset($_GET["delete"])) {
		$user->deleteUser($_GET['delete']);
		header("Location: admin.php?delete=true");
		die();
	}
	
	if(isset($_POST["btnAdd"]))	{		
		unset($_POST["btnAdd"]);
		if (isset($_POST["repass"])) {
			unset($_POST["repass"]);
		}
		if (isset($_POST["btnVerifyUser"])) {
			unset($_POST["btnVerifyUser"]);
		}
		if (isset($_POST["password"])) {
			$_POST["password"] = md5($_POST["password"]);
		}
		
		if (isset($_POST["id_user"])) {
			$user->modifyUser($_POST, $_POST["id_user"]);
		} else {			
			$user->addUser($_POST);
		}		
	} else {
		if ($isEdition) {
			$register = $user->getUserByID($userId);
		}
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>gelato :: <?php echo __("add user")?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="gelato cms <?php echo version();?>" />
		<link rel="shortcut icon" href="<?php echo $conf->urlGelato;?>/images/favicon.ico" />
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/mootools.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/tools.js"></script>
		<style type="text/css" media="screen">	
			@import "<?php echo $conf->urlGelato;?>/admin/css/style.css";
		</style>
	</head>
	
	<body>
		<div id="div-process" style="display:none;"><?php echo __("Processing request...");?></div>
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
					<li><a href="index.php"><?php echo __("Post")?></a></li>
					<li><a href="admin.php"><?php echo __("Users")?></a></li>
					<li class="selected"><a><?php echo ($isEdition) ? __("Edit") : __("Add"); ?></a></li>
					</ul>
				
					<div class="tabla">

						<form action="user.php" method="post" onSubmit="return validateFrmAddUser();" name="frm_add" class="newpost">
						<fieldset>
						<ul>
<?php
							if ($isEdition) {
?>
							<input type="hidden" name="id_user" id="id_user" value="<?php echo $userId;?>" />
<?php
							}
?>
							<li>
								<label for="login"><?php echo __("user:")?></label>
									<input class="txt" name="login" id="login" type="text" autocomplete="off" value="<?php echo isset($register["login"])?$register["login"]:"";?>" />
<?php
							if (!$isEdition) {
?>
									<script language="javascript" type="text/javascript">						
										document.write("<br /><input class='submit_normal_azul' name='btnVerifyUser' id='btnVerifyUser' type='button' value='Check availability' onclick='verifyExistingUser()' />");
									</script>
<?php
							}
?>
							</li>
							<li>
								<div id="target" style="display:none;"></div>
							</li>
							<li>
								<label for="pass"><?php echo __("password:")?></label>
									<input class="txt" name="password" id="password" type="password" />
							</li>
							<li>
								<label for="repass"><?php echo __("retype password:")?></label>
									<input class="txt" name="repass" id="repass" type="password" />
							</li>
							<li>
								<label for="name"><?php echo __("name:")?></label>
									<input class="txt" name="name" id="name" type="text" value="<?php echo isset($register["name"])?$register["name"]:"";?>" />
							</li>
							<li>
								<label for="email"><?php echo __("e-mail:")?></label>
									<input class="txt" name="email" id="email" type="text" value="<?php echo isset($register["email"])?$register["email"]:"";?>" />
							</li>
							<li>
								<label for="website"><?php echo __("website:")?></label>
									<input class="txt" name="website" id="website" type="text" value="<?php echo isset($register["website"])?$register["website"]:"";?>" />
							</li>								
							<li>
								<label for="about"><?php echo __("about:")?></label><br />
									<textarea rows="5" cols="50" name="about" id="about" tabindex="7"><?php echo isset($register["about"])?$register["about"]:"";?></textarea>									
							</li>								
							<li>
								<input name="btnAdd" type="submit" value="<?php echo ($isEdition) ? __("Modify") : __("Add"); ?> user" />
							</li>
						</ul>
						</fieldset>
						</form>
								
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
} else {
	header("Location: ".$conf->urlGelato."/login.php");
}
?>