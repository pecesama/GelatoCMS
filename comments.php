<?php
if(!defined('entry') || !entry) die('Not a valid page');
/* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */
?>
<?php
require_once('config.php');
include("classes/functions.php");
include("classes/comments.class.php");
require_once("classes/configuration.class.php");

$comment = new comments();
$conf = new configuration();
$isEdition = isset($_GET["edit"]);
$commentId = ($isEdition) ? $_GET["edit"] : NULL;


if (isset($_GET["delete"])) {
	$comment->deleteComment($_GET['delete']);
	header("Location: comments.php?delete=true");
	die();
}
	
if(isset($_POST["btnAdd"]))	{		
	unset($_POST["btnAdd"]);
	$_POST["username"] = strip_tags($_POST["username"]);
	$_POST["email"] = strip_tags($_POST["email"]);	
	$_POST["web"] = strip_tags($_POST["web"]);
		
	if (isset($_POST["id_comment"])) {
		$comment->modifyComment($_POST, $_POST["id_comment"]);
	} else {
		$comment->generateCookie($_POST);
		$_POST["spam"] = ($comment->isSpam($_POST)) ? "1" : "0";		
		$_POST["ip_user"] = $_SERVER["REMOTE_ADDR"];		
		
		if ($comment->addComment($_POST)) {
			header("Location: comments.php?added=true");
			die();
		} else {
			header("Location: comments.php?added=false");
			die();
		}
	}		
} else {
	if ($isEdition) {
		$row = $comment->getCommentByID($userId);
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
		<div id="div-process" style="display:none;"><?=__("Processing request...");?></div>
		<div id="cont">
			<div id="head">
				<h1><a href="<?php echo $conf->urlGelato;?>/" title="gelato :: <?=__("home")?>">gelato cms</a></h1>
				<ul id="nav">
					<li><a href="<?php echo $conf->urlGelato;?>/" title="<?=__("Take me to the tumblelog")?>"><?=__("Back to the Tumblelog")?></a></li>
			  	</ul>
			</div>
			<div id="main">				
				
				<div class="box">
					<ul class="menu manage">
					<h3><?=__("Start session")?></h3>
					<li><a href="index.php"><?=__("Post")?></a></li>
					<li><a href="admin.php"><?=__("Users")?></a></li>
					<li class="selected"><a><?php echo ($isEdition) ? __("Edit") : __("Add"); ?></a></li>
					</ul>
				
					<div class="tabla">

						
<?php
							if ($isEdition) {
?>
							<input type="hidden" name="id_user" id="id_user" value="<?php echo $userId;?>" />
<?php
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
?>