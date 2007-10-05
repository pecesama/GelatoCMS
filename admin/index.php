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


require('../entry.php');
global $user, $conf, $tumble;
$template = new plantillas("admin");

$isEdition = (isset($_GET["edit"])) ? true : false;
$postId = ($isEdition) ? $_GET["edit"] : NULL;

if ($user->isAdmin()) {

	if (isset($_GET["delete"])) {
		$tumble->deletePost($_GET['delete']);
		header("Location: index.php?deleted=true");
		die();
	}

	if(isset($_POST["btnAdd"]))	{
		unset($_POST["btnAdd"]);
		
		
		if ($_POST["type"]=="2") { // is Photo type			
			if (isset($_POST["url"]) && $_POST["url"]!="")  {			
				$photoName = getFileName($_POST["url"]);
				if (!$tumble->savePhoto($_POST["url"])) {
					header("Location: ".$conf->urlGelato."/admin/index.php?photo=false");
					die();
				}
				$_POST["url"] = "../uploads/".sanitizeName($photoName);
			}
			
			if ( move_uploaded_file( $_FILES['photo']['tmp_name'], "../uploads/".sanitizeName($_FILES['photo']['name']) ) ) {
				$_POST["url"] = "../uploads/".sanitizeName($_FILES['photo']['name']);
			}
			
			unset($_POST["photo"]);
			unset($_POST["MAX_FILE_SIZE"]);
		}
		
		if ($_POST["type"]=="7") { // is MP3 type
			set_time_limit(300);
			$mp3Name = getFileName($_POST["url"]);
			if (!$tumble->saveMP3($_POST["url"])) {
				header("Location: ".$conf->urlGelato."/admin/index.php?mp3=false");
				die();
			}
			if (isMP3($remoteFileName)) {
				$_POST["url"] = $conf->urlGelato."/uploads/".$mp3Name;
			}
		}		
				
		if (!get_magic_quotes_gpc()) {	
			$_POST["title"] = addslashes($_POST["title"]);
			$_POST["description"] = addslashes($_POST["description"]);
		}	
		
		$_POST["title"] = strip_tags($_POST["title"]);
		$_POST["description"] = strip_tags($_POST["description"]);
		
		
		if (isset($_POST["id_post"])) {
			$tumble->modifyPost($_POST, $_POST["id_post"]);
		} else {			
			if ($tumble->addPost($_POST)) {
				header("Location: ".$conf->urlGelato."/admin/index.php?added=true");
				die();
			} else {
				header("Location: ".$conf->urlGelato."/admin/index.php?error=2&des=".$this->merror);
				die();
			}
		}	
	} else {
		if ($isEdition) {
			$post = $tumble->getPost($postId);
		}
	
?>	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="gelato cms <?php echo version();?>" />
		<title>gelato :: <?php echo __("control panel")?></title>
		<link rel="shortcut icon" href="<?php echo $conf->urlGelato;?>/images/favicon.ico" />
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/tools.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/mootools.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/slimbox.js"></script>
		<script language="javascript" type="text/javascript">
		<!--
			function exit(el, path) {
				el = $(el);
				el.style.display="block";
				el.setHTML('Processing request&hellip;');
				new Ajax(path, {
					onComplete:function(e) {
						el.setHTML(e).effect('opacity').custom(0,1);
						window.location='../login.php';
					}
				}).request();
				return false;
			}

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
			@import "<?php echo $conf->urlGelato;?>/admin/css/slimbox.css";
		</style>
	</head>
	
	<body>
		<div id="div-process" style="display:none;"><?php echo __("Processing request&hellip;")?></div>
		<div id="cont">
			<div id="head">
				<h1><a href="<?php echo $conf->urlGelato;?>/admin/index.php" title="gelato :: <?php echo __("home")?>">gelato cms</a></h1>
				<ul id="nav">
					<li><a href="<?php echo $conf->urlGelato;?>/" title="<?php echo __("Take me to the tumblelog")?>"><?php echo __("View Tumblelog")?></a></li>
					<li><a href="close.php" title="Log off" ><?php echo __("Log out")?></a></li>
			  	</ul>
			</div>
			<div id="main">
				<div class="box">
					<ul class="menu">
					<h3><?php echo __("New Post")?></h3>					
					<li<?php echo (isset($_GET["new"]) && $_GET["new"]=="conversation") ? " class=\"selected\"" : ""; ?>><a href="<?php echo $conf->urlGelato;?>/admin/index.php?new=conversation"><img src="css/images/comments.png" alt="New chat" /> <?php echo __("Chat")?></a></li>
					<li<?php echo (isset($_GET["new"]) && $_GET["new"]=="quote") ? " class=\"selected\"" : ""; ?>><a href="<?php echo $conf->urlGelato;?>/admin/index.php?new=quote"><img src="css/images/quote.png" alt="New qoute" /> <?php echo __("Quote")?></a></li>
					<li<?php echo (isset($_GET["new"]) && $_GET["new"]=="url") ? " class=\"selected\"" : ""; ?>><a href="<?php echo $conf->urlGelato;?>/admin/index.php?new=url"><img src="css/images/world.png" alt="New link" /> <?php echo __("Link")?></a></li>
					<li<?php echo (isset($_GET["new"]) && $_GET["new"]=="mp3") ? " class=\"selected\"" : ""; ?>><a href="<?php echo $conf->urlGelato;?>/admin/index.php?new=mp3"><img src="css/images/music.png" alt="New audio" /> <?php echo __("Audio")?></a></li>
					<li<?php echo (isset($_GET["new"]) && $_GET["new"]=="video") ? " class=\"selected\"" : ""; ?>><a href="<?php echo $conf->urlGelato;?>/admin/index.php?new=video"><img src="css/images/film.png" alt="New video" /> <?php echo __("Video")?></a></li>
					<li<?php echo (isset($_GET["new"]) && $_GET["new"]=="photo") ? " class=\"selected\"" : ""; ?>><a href="<?php echo $conf->urlGelato;?>/admin/index.php?new=photo"><img src="css/images/image.png" alt="New picture" /> <?php echo __("Picture")?></a></li>
					<li<?php echo (isset($_GET["new"]) && $_GET["new"]=="post") ? " class=\"selected\"" : ""; echo (!isset($_GET["new"])) ? " class=\"selected\"" : ""; ?>><a href="<?php echo $conf->urlGelato;?>/admin/index.php?new=post"><img src="css/images/page.png" alt="New post" /> <?php echo __("Regular")?></a></li>
					</ul>
					<p>&nbsp;</p>					
<?php			
					$present = version();
					$lastest = _file_get_contents("http://www.gelatocms.com/vgel.txt");
					if ($present < $lastest) {
						echo "<div class=\"information\" id=\"update\">".__("A new gelato version has been released and is ready <a href=\"http://www.gelatocms.com/\">for download</a>.")."</div><br />";
					}
					
					if (isset($_GET["deleted"])) {
						if ($_GET["deleted"]=="true") {
							echo "<div class=\"exito\" id=\"divMessages\">".__("The post has been eliminated successfully.")."</div>";
						}
					}
					
					if (isset($_GET["modified"])) {
						if ($_GET["modified"]=="true") {
							echo "<div class=\"exito\" id=\"divMessages\">".__("The post has been modified successfully.")."</div>";
						}
					}
					
					if (isset($_GET["added"])) {
						if ($_GET["added"]=="true") {
							echo "<div class=\"exito\" id=\"divMessages\">".__("The post has been added successfully.")."</div>";
						}
					}
					
					if (isset($_GET["error"])) {
						if ($_GET["error"]==2) {
							echo "<div class=\"error\"><strong>".__("Error on the database server:")." </strong>".$_GET["des"]."</div>";
						}
					}
					
					if (isset($_GET["mp3"])) {
						if ($_GET["mp3"]=="false") {
							echo "<div class=\"error\" id=\"divMessages\">".__("Not an MP3 file or an upload problem.")."</div>";
						}
					}
					
					if (isset($_GET["photo"])) {
						if ($_GET["photo"]=="false") {
							echo "<div class=\"error\" id=\"divMessages\">".__("Not a photo file or an upload problem.")."</div>";
						}
					}					
?>					
					<form action="index.php" method="post" <?php echo (isset($_GET["new"]) && $_GET["new"]=="photo") ? "enctype=\"multipart/form-data\"" : ""?> name="frmAdd" id="autosave" class="newpost">
						<fieldset>
<?php
							if ($isEdition) {
?>
								<input type="hidden" name="id_post" id="id_post" value="<?php echo $postId;?>" />
<?php
								switch ($post["type"]) {
									case "1":
										$_GET["new"] = "post";
										break;
									case "2":
										$_GET["new"] = "photo";							   
										break;
									case "3":
										$_GET["new"] = "quote";
										break;
									case "4":
										$_GET["new"] = "url";
										break;
									case "5":
										$_GET["new"] = "conversation";
										break;
									case "6":
										$_GET["new"] = "video";
										break;
									case "7":
										$_GET["new"] = "mp3";
										break;								
								}
								
							}
							
							$date = ($isEdition) ? strtotime($post["date"]) : gmmktime();
							$title = ($isEdition) ? htmlspecialchars(stripslashes($post["title"])) : "";
							$body = ($isEdition) ? stripslashes($post["description"]) : "";
							$url = ($isEdition) ? $post["url"] : "";
							
							if (!isset($_GET['new'])) $_GET['new'] = 'default';
							switch ($_GET["new"]) {
								case "post":
									$input = array("{type}", "{date}", "{id_user}", "{editTitle}", "{editBody}");
									$output = array("1", $date, $_SESSION['user_id'], $title, $body);
									
									$template->cargarPlantilla($input, $output, "template_add_post");
									$template->mostrarPlantilla();
									break;
								case "photo":
									$input = array("{type}", "{date}", "{id_user}", "{editUrl}", "{editBody}");
									$output = array("2", $date, $_SESSION['user_id'], $url, $body);
									
									$template->cargarPlantilla($input, $output, "template_add_photo");
									$template->mostrarPlantilla();							   
									break;
								case "quote":
									$input = array("{type}", "{date}", "{id_user}", "{editTitle}", "{editBody}");
									$output = array("3", $date, $_SESSION['user_id'], $title, $body);
									
									$template->cargarPlantilla($input, $output, "template_add_quote");
									$template->mostrarPlantilla();
									break;
								case "url":
									
									$input = array("{type}", "{date}", "{id_user}", "{editTitle}", "{editUrl}", "{editBody}");
									$output = array("4", $date, $_SESSION['user_id'], $title, $url, $body);
									
									$template->cargarPlantilla($input, $output, "template_add_link");
									$template->mostrarPlantilla();
									break;
								case "conversation":
									$input = array("{type}", "{date}", "{id_user}", "{editTitle}", "{editBody}");
									$output = array("5", $date, $_SESSION['user_id'], $title, $body);
									
									$template->cargarPlantilla($input, $output, "template_add_conversation");
									$template->mostrarPlantilla();
									break;
								case "video":
									$input = array("{type}", "{date}", "{id_user}", "{editUrl}", "{editBody}");
									$output = array("6", $date, $_SESSION['user_id'], $url, $body);
									
									$template->cargarPlantilla($input, $output, "template_add_video");
									$template->mostrarPlantilla();
									break;
								case "mp3":
									$input = array("{type}", "{date}", "{id_user}", "{editUrl}", "{editBody}");
									$output = array("7", $date, $_SESSION['user_id'], $url, $body);
									
									$template->cargarPlantilla($input, $output, "template_add_mp3");
									$template->mostrarPlantilla();
									break;
								default:
									$input = array("{type}", "{date}", "{id_user}", "{editTitle}", "{editBody}");
									$output = array("1", $date, $_SESSION['user_id'], $title, $body);
									
									$template->cargarPlantilla($input, $output, "template_add_post");
									$template->mostrarPlantilla();
									break;
							}
?>
								<p>
									<span style="color: rgb(136, 136, 136); margin-bottom: 10px; font-size: 10px;">
                                    	<a href="http://hobix.com/textile/">Textile</a> <?php echo __("syntax is supported.")?>
                                        <br />
                                        Bookmarklet - <a href="javascript:var w; setTimeout('w.focus()',100);w=window.open(' http://www.pecesama.net/quotes/admin/bm.php?url='+encodeURIComponent(location.href)+'&sel='+encodeURIComponent(window.getSelection()),'bookmarklet','toolbar=0,resizable=0,status=1,width=650,height=460,dependent=yes' ); w.focus();"><?php echo __("add to gelato")?></a>
                                    </span>
								</p>
								<p>
									<input class="btn" type="submit" name="btnAdd" value="<?php echo ($isEdition) ? "Modify" : "Create"; ?> post" />
								</p>
						</fieldset>
					</form>					
					<div class="footer-box">&nbsp;</div>
				</div>
<?php
				if (!$isEdition) {
?>
				<div class="box">
					<ul class="menu manage">
					<h3>Manage</h3>
					<li><a href="<?php echo $conf->urlGelato;?>/admin/settings.php"><?php echo __("Settings")?></a></li>
					<li><a href="<?php echo $conf->urlGelato;?>/admin/options.php"><?php echo __("Options")?></a></li>
                    <li><a href="<?php echo $conf->urlGelato;?>/admin/feeds.php"><?php echo __("Feeds")?></a></li>
					<li><a href="<?php echo $conf->urlGelato;?>/admin/admin.php"><?php echo __("Users")?></a></li>
					<li><a href="<?php echo $conf->urlGelato;?>/admin/comments.php"><?php echo __("Comments")?></a></li>
					<li class="selected"><a><?php echo __("Posts")?></a></li>
					</ul>

<?php
					if (isset($_GET["page"])) {
						$page_num = $_GET["page"];
					} else {
						$page_num = NULL;
					}
					
					$limit=$conf->postLimit;
					
					if(isset($page_num) && is_numeric($page_num) && $page_num>0) { // Is defined the page and is numeric?
						$from = (($page_num-1) * $limit);
					} else {
						$from = 0;
					}
					
					$rs = $tumble->getPosts($limit, $from);
					
					if ($tumble->contarRegistros()>0) {				
						while($register = mysql_fetch_array($rs)) {			
							$formatedDate = gmdate("M d", strtotime($register["date"])+transform_offset($conf->offsetTime));
							$permalink = $conf->urlGelato."/index.php/post/".$register["id_post"]."/";							
							
							$textile = new Textile();				
							$register["description"] = $textile->TextileThis($register["description"]);
							
							$register["title"] = stripslashes($register["title"]);
							$register["description"] = stripslashes($register["description"]);
							
							switch ($tumble->getType($register["id_post"])) {
								case "1":
									$input = array("{Id_Post}", "{Date_Added}", "{Permalink}", "{Title}", "{Body}", "{URL_Tumble}");
									$output = array($register["id_post"], $formatedDate, $permalink, $register["title"], $register["description"], $conf->urlGelato);
														
									$template->cargarPlantilla($input, $output, "template_regular_post");
									$template->mostrarPlantilla();
									break;
								case "2":
									$fileName = "../uploads/".getFileName($register["url"]);
									
									$x = @getimagesize($fileName);						
									if ($x[0] > 100) {							
										$photoPath = $conf->urlGelato."/classes/imgsize.php?w=100&img=".$register["url"];
									} else {
										$photoPath = $register["url"];
									}

									$effect = " style=\"cursor: pointer;\" onclick=\"Lightbox.show('".$register["url"]."', '".strip_tags($register["description"])."');\" ";
									
									$input = array("{Id_Post}", "{Date_Added}", "{Permalink}", "{PhotoURL}", "{PhotoAlt}", "{Caption}", "{Effect}", "{URL_Tumble}");
									$output = array($register["id_post"], $formatedDate, $permalink, $photoPath, strip_tags($register["description"]), $register["description"], $effect, $conf->urlGelato);
									
									$template->cargarPlantilla($input, $output, "template_photo");
									$template->mostrarPlantilla();							   
									break;
								case "3":
									$input = array("{Id_Post}", "{Date_Added}", "{Permalink}", "{Quote}", "{Source}", "{URL_Tumble}");
									$output = array($register["id_post"], $formatedDate, $permalink, $register["description"], $register["title"], $conf->urlGelato);
									
									$template->cargarPlantilla($input, $output, "template_quote");
									$template->mostrarPlantilla();
									break;
								case "4":
									if($conf->shorten_links){
										$register["url"] = _file_get_contents("http://api.abbrr.com/api.php?out=link&url=".$register["url"]);
									}
									$register["title"] = ($register["title"]=="")? $register["url"] : $register["title"];
									$input = array("{Id_Post}", "{Date_Added}", "{Permalink}", "{URL}", "{Name}", "{Description}", "{URL_Tumble}");
									$output = array($register["id_post"], $formatedDate, $permalink, $register["url"], $register["title"], $register["description"], $conf->urlGelato);
									
									$template->cargarPlantilla($input, $output, "template_url");
									$template->mostrarPlantilla();
									break;
								case "5":
									$input = array("{Id_Post}", "{Date_Added}", "{Permalink}", "{Title}", "{Conversation}", "{URL_Tumble}");
									$output = array($register["id_post"], $formatedDate, $permalink, $register["title"], $tumble->formatConversation($register["description"]), $conf->urlGelato);
									
									$template->cargarPlantilla($input, $output, "template_conversation");
									$template->mostrarPlantilla();
									break;
								case "6":
									$input = array("{Id_Post}", "{Date_Added}", "{Permalink}", "{Video}", "{Caption}", "{URL_Tumble}");
									$output = array($register["id_post"], $formatedDate, $permalink, $tumble->getVideoPlayer($register["url"]), $register["description"], $conf->urlGelato);
									
									$template->cargarPlantilla($input, $output, "template_video");
									$template->mostrarPlantilla();
									break;
								case "7":
									$input = array("{Id_Post}", "{Date_Added}", "{Permalink}", "{Mp3}", "{Caption}", "{URL_Tumble}");
									$output = array($register["id_post"], $formatedDate, $permalink, $tumble->getMp3Player($register["url"]), $register["description"], $conf->urlGelato);
									
									$template->cargarPlantilla($input, $output, "template_mp3");
									$template->mostrarPlantilla();
									break;
							}
						}

						$p = new pagination;
						$p->items($tumble->getPostsNumber());
						$p->limit($limit);
						$p->currentPage(isset($page_num) ? $page_num : 1);
						$p->show();
			
			
					} else {
						$template->renderizaEtiqueta(__("No posts in this tumblelog."), "div","error");
					}			
					
?>				
					<div class="footer-box">&nbsp;</div>
				</div>
			</div>
<?php
			}
?>
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