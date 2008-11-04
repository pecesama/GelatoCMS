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

if ($user->isAdmin()) {

	if(isset($_POST["btnAdd"]))	{
		unset($_POST["btnAdd"]);

		if ($_POST["type"]=="2") { // is Photo type
			if (isset($_POST["url"]) && $_POST["url"]!="")  {
				$photoName = getFileName($_POST["url"]);
				if (!$tumble->savePhoto($_POST["url"])) {
					header("Location: ".$conf->urlGelato."/admin/index.php?photo=false");
					die();
				}
				$_POST["url"] = "../uploads/".util::sanitizeName($photoName);
			}

			if ( move_uploaded_file( $_FILES['photo']['tmp_name'], "../uploads/".sanitizeName($_FILES['photo']['name']) ) ) {
				$_POST["url"] = "../uploads/".util::sanitizeName($_FILES['photo']['name']);
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
		
		$_POST["title"] = removeBadTags($_POST["title"],true);
		$_POST["description"] = removeBadTags($_POST["description"]);

		if ($tumble->addPost($_POST)) {
			$input = array("{type}");
			$output = array("1");

			$template->cargarPlantilla($input, $output, "template_bm");
			$template->mostrarPlantilla();
			die();
		} else {
			header("Location: ".$conf->urlGelato."/admin/index.php?error=2&des=".$this->merror);
			die();
		}
	} else {

		if (isset($_GET["url"])) {
			$url = $_GET["url"];
		} else {
			$url = null;
		}
		if (isset($url)) {

			if (util::isMP3($url)) {
				$postType = "mp3";
			} elseif (util::isGoEar($url)) {
				$postType = "mp3";
			} elseif (util::isImageFile($url)) {
				$postType = "photo";
			} elseif (util::isVideo($url)) {
				$postType = "video";
			} else {
				if (isset($_GET["sel"]) && !$_GET["sel"]=="" ) {
					$postType = "post";
				} else {
					$postType = "url";
				}
			}

		} else {
			die(__("Must be a valid URL"));
		}
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="gelato cms <?php echo util::version();?>" />
		<title>gelato :: <?php echo __("bookmarklet")?></title>
		<link rel="shortcut icon" href="<?php echo $conf->urlGelato;?>/images/favicon.ico" />
        <script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/jquery.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/tools.js"></script>
        <script type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/markitup/jquery.markitup.pack.js"></script>
		<script type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/markitup/sets/html/set.js"></script>			
        <link rel="stylesheet" type="text/css" href="<?php echo $conf->urlGelato;?>/admin/scripts/markitup/skins/simple/style.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $conf->urlGelato;?>/admin/scripts/markitup/sets/html/style.css" />			
        <script type="text/javascript" >
           $(document).ready(function() {
              $("#description").markItUp(my_html);
           });
        </script>
		<style type="text/css" media="screen">
			@import "<?php echo $conf->urlGelato;?>/admin/css/style.css";
		</style>
	</head>

	<body>
		<div id="cont">
			<div id="main">
				<div class="box">
					<h3><?php echo __("New Post")?></h3>
					<ul class="menu">
<?php
						switch ($postType) {
								case "post":
?>
									<li class="selected"><a href="#"><img src="css/images/page.png" alt="New post" /> <?php echo __("Regular")?></a></li>
<?php
									break;
								case "photo":
?>
									<li class="selected"><a href="#"><img src="css/images/image.png" alt="New picture" /> <?php echo __("Picture")?></a></li>
<?php
									break;
								case "url":
?>
									<li class="selected"><a href="#"><img src="css/images/world.png" alt="New link" /> <?php echo __("Link")?></a></li>
<?php
									break;
								case "video":
?>
									<li class="selected"><a href="#"><img src="css/images/film.png" alt="New video" /> <?php echo __("Video")?></a></li>
<?php
									break;
								case "mp3":
?>
									<li class="selected"><a href="#"><img src="css/images/music.png" alt="New audio" /> <?php echo __("Audio")?></a></li>
<?php
									break;
							}
?>
					</ul>
					<p>&nbsp;</p>
					<form action="bm.php" method="post" <?php echo (isset($_GET["new"]) && $_GET["new"]=="photo") ? "enctype=\"multipart/form-data\"" : ""?> name="frmAdd" class="newpost">
						<fieldset>
<?php
							$date = gmmktime();
							$title = "";
							$body = (isset($_GET["sel"])) ? $_GET["sel"] : "";
							$url = (isset($url)) ? $url : "";

							switch ($postType) {
								case "post":
									$input = array("{type}", "{date}", "{id_user}", "{editTitle}", "{editBody}");
									$output = array("1", $date, $_SESSION['user_id'], $title, $body);

									$template->cargarPlantilla($input, $output, "template_add_post");
									$template->mostrarPlantilla();
									break;
								case "photo":
									$input = array("{type}", "{date}", "{id_user}", "{editUrl}", "{editBody}");
									$output = array("2", $date, $_SESSION['user_id'], $url, $body);

									$template->cargarPlantilla($input, $output, "template_add_photo_bm");
									$template->mostrarPlantilla();
									break;
								case "url":

									$input = array("{type}", "{date}", "{id_user}", "{editTitle}", "{editUrl}", "{editBody}");
									$output = array("4", $date, $_SESSION['user_id'], $title, $url, $body);

									$template->cargarPlantilla($input, $output, "template_add_link");
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
							}
?>
								<p>
									<span style="color: rgb(136, 136, 136); margin-bottom: 10px; font-size: 10px;">
                                    	<?php echo __("Some HTML allowed")?>:<br />
										&nbsp;&nbsp;&nbsp;&nbsp;
										<code>
                                            &lt;strong&gt; &lt;em&gt; &lt;del&gt; &lt;ul&gt;  &lt;ol&gt;  &lt;li&gt; &lt;a&gt;
                                            <br />
                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;blockquote&gt;
                                            &lt;code&gt; &lt;pre&gt; &lt;img&gt;
										</code>
										<br /><br />
                                    </span>
								</p>
								<p>
									<input class="btn" type="submit" name="btnAdd" value="Create post" />
								</p>
						</fieldset>
					</form>
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
} else {
	$target = (isset($_GET["url"]))? "/login.php?redirect_url=".$_GET["url"]."&sel=".$_GET["sel"] : "/login.php";
	header("Location: ".$conf->urlGelato.$target);
}
?>