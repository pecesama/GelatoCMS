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
require_once('../config.php');
include("../classes/functions.php");
include("../classes/user.class.php");
include("../classes/comments.class.php");
include("../classes/templates.class.php");
include("../classes/pagination.class.php");
require_once("../classes/configuration.class.php");

$user = new user();
$comment = new comments();
$conf = new configuration();
$template = new plantillas("admin");
$isAdmin = $user->isAdmin();
$isEdition = isset($_GET["edit"]);
$commentId = ($isEdition) ? $_GET["edit"] : NULL;
	
if(isset($_POST["btnAdd"]))	{		
	unset($_POST["btnAdd"]);
	$_POST["username"] = strip_tags($_POST["username"]);
	$_POST["email"] = strip_tags($_POST["email"]);	
	$_POST["web"] = strip_tags($_POST["web"]);
		
	if (isset($_POST["id_comment"])) {
		if ($isAdmin) {
			if ($comment->modifyComment($_POST, $_POST["id_comment"])) {
				header("Location: comments.php?modified=true");
				die();
			} else {
				header("Location: comments.php?modified=false");
				die();
			}
		}
	} else {
		$comment->generateCookie($_POST);
		$_POST["spam"] = ($comment->isSpam($_POST)) ? "1" : "0";		
		$_POST["ip_user"] = $_SERVER["REMOTE_ADDR"];
		
		if ($comment->addComment($_POST)) {
			header("Location: ".$conf->urlGelato."/index.php/post/".$_POST["id_post"]);
			die();
		}
	}		
} 

if ($isAdmin) {

	if (isset($_GET["delete"])) {
		if ($comment->deleteComment($_GET['delete'])) {
			header("Location: comments.php?deleted=true");
			die();
		} else {
			header("Location: comments.php?deleted=false");
			die();
		}		
	}
	
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>gelato :: <?php echo __("comments")?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="gelato cms <?php echo version();?>" />
		<link rel="shortcut icon" href="<?php echo $conf->urlGelato;?>/images/favicon.ico" />
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/mootools.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/tools.js"></script>
		<script language="javascript" type="text/javascript">
		<!--
			window.onload = function() {
				contenedor = new Fx.Style('divMessages', 'opacity', {duration: 5000, onComplete:
					function() {
						document.getElementById('divMessages').style.display="none";
					}
				});
				contenedor.custom(1,0);
			}
		-->
		</script>
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
						<h3><?=__("Manage comments")?></h3>
						<li><a href="index.php"><?=__("Posts")?></a></li>
						<li <?php if (isset($_GET["spam"])) { ?> class="selected" <?php } ?>><a href="comments.php?spam=true"><?=__("Spam")?></a></li>
						<li <?php if (!isset($_GET["spam"])) { ?> class="selected" <?php } ?> ><a href="comments.php"><?php echo ($isEdition) ? __("Edit") : __("List"); ?></a></li>
					</ul>
					<p>&nbsp;</p>					
<?php	
					if (isset($_GET["deleted"])) {
						if ($_GET["deleted"]=="true") {
							echo "<div class=\"exito\" id=\"divMessages\">".__("The comment has been eliminated successfully.")."</div>";
						}
						if ($_GET["deleted"]=="false") {
							echo "<div class=\"error\" id=\"divMessages\">".__("The post has NOT been eliminated.")."</div>";
						}
					}
					
					if (isset($_GET["modified"])) {
						if ($_GET["modified"]=="true") {
							echo "<div class=\"exito\" id=\"divMessages\">".__("The comment has been modified successfully.")."</div>";
						}
						if ($_GET["modified"]=="false") {
							echo "<div class=\"error\" id=\"divMessages\">".__("The post has NOT been modified.")."</div>";
						}
					}
?>
					<div class="tabla">						
<?php
						if ($isEdition) {						
							
							$row = $comment->getComment($_GET["edit"]);
							$date = strtotime($row["comment_date"]);
							
							$input = array("{User}", "{Email}", "{Web}", "{Comment}", "{Id_Post}", "{Date_Added}", "{Id_Comment}", "{Form_Action}");
							$output = array($row["username"], $row["email"], $row["web"], $row["content"], $row["id_post"], $date, $row["id_comment"], $conf->urlGelato."/admin/comments.php");
							
							$template->cargarPlantilla($input, $output, "template_comment_post");
							$template->mostrarPlantilla(); 

						} else {
							
							if (isset($_GET["page"]) && is_numeric($_GET["page"]) ) {
								$page_num = $_GET["page"];
							} else {
								$page_num = NULL;
							}
							
							$limit=$conf->postLimit;
							
							if(isset($page_num) && is_numeric($page_num) && $page_num>0) {
								$from = (($page_num-1) * $limit);
							} else {
								$from = 0;
							}
							
							if (isset($_GET["spam"]) && $_GET["spam"]=="true") { $sp = "1"; } else { $sp = null; }
							
							$rs = $comment->getComments(null, $limit, $from, $sp);
					
							if ($comment->contarRegistros()>0) {				
								while($rowComment = mysql_fetch_array($rs)) {	
							
									$commentAuthor = ($rowComment["web"]=="") ? $rowComment["username"]." | ".$rowComment["email"]  : "<a href=\"".$rowComment["web"]."\" rel=\"external\">".$rowComment["username"]."</a> | ".$rowComment["email"];
									
									$input = array("{Permalink}", "{URL_Tumble}", "{Id_Comment}", "{Comment_Author}", "{Comment}");				
									$output = array($conf->urlGelato."/index.php/post/".$rowComment["id_post"]."#comment-".$rowComment["id_comment"], $conf->urlGelato, $rowComment["id_comment"], $commentAuthor, $rowComment["content"]);
									
									$template->cargarPlantilla($input, $output, "template_comment");
									$template->mostrarPlantilla();
								}
								
								$p = new pagination;
								$p->items($comment->countComments());
								$p->limit($limit);
								$p->currentPage(isset($page_num) ? $page_num : 1);
								$p->show();
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
} else {
	header("Location: ".$conf->urlGelato."/login.php");
}
?>