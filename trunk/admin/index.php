<?
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?
require_once('../config.php');
include("../classes/user.class.php");
include("../classes/gelato.class.php");
include("../classes/templates.class.php");
require_once("../classes/configuration.class.php");

$user = new user();
$tumble = new gelato();
$conf = new configuration();
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
				$_POST["url"] = $conf->urlGelato."/uploads/".$photoName;
			}
			
			if ( move_uploaded_file( $_FILES['photo']['tmp_name'], "../uploads/".$_FILES['photo']['name'] ) ) {
				$_POST["url"] = $conf->urlGelato."/uploads/".$_FILES['photo']['name'];
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
			$_POST["url"] = $conf->urlGelato."/uploads/".$mp3Name;
		}
		
		if (get_magic_quotes_gpc()) {
			$_POST["title"] = htmlspecialchars(stripslashes($_POST["title"]));
			$_POST["description"] = htmlspecialchars(stripslashes($_POST["description"]));
		} else {
			$_POST["title"] = htmlspecialchars($_POST["title"]);
			$_POST["description"] = htmlspecialchars($_POST["description"]);
		}
		
		$_POST["title"] = strip_tags($_POST["title"]);
		$_POST["description"] = strip_tags($_POST["description"]);
		
		
		if (isset($_POST["id_post"])) {
			//$tumble->modifyPost($_POST, $_POST["id_post"]);
		} else {			
			if ($tumble->addPost($_POST)) {
				header("Location: ".$conf->urlGelato."/admin/index.php?added=true");
				die();
			} else {
				header("Location: ".$conf->urlGelato."/admin/index.php?error=2&des=".$this->merror);
				die();
			}
		}	
	}
?>	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>gelato :: Control panel</title>
		<link rel="shortcut icon" href="<?=$conf->urlGelato;?>/images/favicon.ico" />
		<script language="javascript" type="text/javascript" src="<?=$conf->urlGelato;?>/admin/scripts/tools.js"></script>
		<script language="javascript" type="text/javascript" src="<?=$conf->urlGelato;?>/admin/scripts/mootools.js"></script>
		<script type="text/javascript">
		<!--
			function exit(el, path) {
				el = $(el);
				el.style.display="block";
				el.setHTML('Processing request...');
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
						document.getElementById('pMessages').style.display="none";
					}
				});
				contenedor.custom(1,0);
			}
		-->
		</script>
		<style type="text/css" media="screen">	
			@import "<?=$conf->urlGelato;?>/admin/css/style.css";
		</style>
	</head>
	
	<body>
		<div id="div-process" style="display:none;">Processing request...</div>
		<div id="cont">
			<div id="head">
				<h1><a href="<?=$conf->urlGelato;?>/admin/index.php" title="gelato :: home">gelato cms</a></h1>
				<ul id="nav">
					<li><a href="<?=$conf->urlGelato;?>/" title="Take me to the tumblelog">View Tumblelog</a></li>
					<li><a href="close.php" onclick="return exit('div-process','<?=$conf->urlGelato;?>/admin/ajax.php?action=close');">Log out</a></li>
			  </ul>
			</div>
			<div id="main">
				<div class="box">
					<ul class="menu">
					<h3>New Post</h3>
					<li><a href="<?=$conf->urlGelato;?>/admin/index.php?new=conversation"><img src="css/images/comments.png" alt="New chat" /> Chat</a></li>
					<li><a href="<?=$conf->urlGelato;?>/admin/index.php?new=quote"><img src="css/images/comments.png" alt="New qoute" /> Quote</a></li>
					<li><a href="<?=$conf->urlGelato;?>/admin/index.php?new=url"><img src="css/images/world.png" alt="New link" /> Link</a></li>
					<li><a href="<?=$conf->urlGelato;?>/admin/index.php?new=mp3"><img src="css/images/music.png" alt="New audio" /> Audio</a></li>
					<li><a href="<?=$conf->urlGelato;?>/admin/index.php?new=video"><img src="css/images/film.png" alt="New video" /> Video</a></li>
					<li><a href="<?=$conf->urlGelato;?>/admin/index.php?new=photo"><img src="css/images/image.png" alt="New picture" /> Picture</a></li>
					<li class="selected"><a href="<?=$conf->urlGelato;?>/admin/index.php?new=post"><img src="css/images/page_white_text.png" alt="New post" /> Regular</a></li>
					</ul>
					
<?					
					if (isset($_GET["deleted"])) {
						if ($_GET["deleted"]=="true") {
							echo "<p id=\"pMessages\">&nbsp;</p><div class=\"exito\" id=\"divMessages\">The article has been eliminated successfully.</div>";
						}
					}
					
					if (isset($_GET["modified"])) {
						if ($_GET["modified"]=="true") {
							echo "<p id=\"pMessages\">&nbsp;</p><div class=\"exito\" id=\"divMessages\">The article has been modified successfully.</div>";
						}
					}
					
					if (isset($_GET["added"])) {
						if ($_GET["added"]=="true") {
							echo "<p id=\"pMessages\">&nbsp;</p><div class=\"exito\" id=\"divMessages\">The article has been added successfully.</div>";
						}
					}
					
					if (isset($_GET["error"])) {
						if ($_GET["error"]==2) {
							echo "<p id=\"pMessages\">&nbsp;</p><div class=\"error\"><strong>Error on the database server: </strong>".$_GET["des"]."</div>";
						}
					}
					
					if (isset($_GET["mp3"])) {
						if ($_GET["mp3"]=="false") {
							echo "<p id=\"pMessages\">&nbsp;</p><div class=\"error\" id=\"divMessages\">Not an MP3 file or an upload problem.</div>";
						}
					}
					
					if (isset($_GET["photo"])) {
						if ($_GET["photo"]=="false") {
							echo "<p id=\"pMessages\">&nbsp;</p><div class=\"error\" id=\"divMessages\">Not a photo file or an upload problem.</div>";
						}
					}
?>					
					<form action="index.php" method="post" <?=($_GET["new"]=="photo") ? "enctype=\"multipart/form-data\"" : ""?> name="frmAdd" class="newpost">
						<fieldset>
<?
							switch ($_GET["new"]) {
								case "post":
									$input = array("{type}", "{date}", "{id_user}");
									$output = array("1", time(), $_SESSION['user_id']);
									
									$template->cargarPlantilla($input, $output, "template_add_post");
									$template->mostrarPlantilla();
									break;
								case "photo":
									$input = array("{type}", "{date}", "{id_user}");
									$output = array("2", time(), $_SESSION['user_id']);
									
									$template->cargarPlantilla($input, $output, "template_add_photo");
									$template->mostrarPlantilla();							   
									break;
								case "quote":
									$input = array("{type}", "{date}", "{id_user}");
									$output = array("3", time(), $_SESSION['user_id']);
									
									$template->cargarPlantilla($input, $output, "template_add_quote");
									$template->mostrarPlantilla();
									break;
								case "url":
									$input = array("{type}", "{date}", "{id_user}");
									$output = array("4", time(), $_SESSION['user_id']);
									
									$template->cargarPlantilla($input, $output, "template_add_link");
									$template->mostrarPlantilla();
									break;
								case "conversation":
									$input = array("{type}", "{date}", "{id_user}");
									$output = array("5", time(), $_SESSION['user_id']);
									
									$template->cargarPlantilla($input, $output, "template_add_conversation");
									$template->mostrarPlantilla();
									break;
								case "video":
									$input = array("{type}", "{date}", "{id_user}");
									$output = array("6", time(), $_SESSION['user_id']);
									
									$template->cargarPlantilla($input, $output, "template_add_video");
									$template->mostrarPlantilla();
									break;
								case "mp3":
									$input = array("{type}", "{date}", "{id_user}");
									$output = array("7", time(), $_SESSION['user_id']);
									
									$template->cargarPlantilla($input, $output, "template_add_mp3");
									$template->mostrarPlantilla();
									break;
								default:
									$input = array("{type}", "{date}", "{id_user}");
									$output = array("1", time(), $_SESSION['user_id']);
									
									$template->cargarPlantilla($input, $output, "template_add_post");
									$template->mostrarPlantilla();
									break;
							}
?>
								<p>
									<input class="btn" type="submit" name="btnAdd" value="Create post" />&nbsp;&nbsp;
									<a href="#" onclick="if (confirm('Cancel editing this post?  All changes will be lost.'))
	{location.href='index.php';}; return false;">Cancel</a>
								</p>
						</fieldset>
					</form>
					
					<div class="footer-box">&nbsp;</div>
				</div>
				
				<div class="box">
					<ul class="menu manage">
					<h3>Manage</h3>
					<li><a href="#">Settings</a></li>
					<li><a href="<?=$conf->urlGelato;?>/admin/admin.php">Users</a></li>
					<li class="selected"><a href="#">Posts</a></li>
					</ul>
					
					<div class="entry">
						<div class="info"><form class="compact"><input type="submit" value="Edit" /> <input type="submit" value="Delete" /></form>
							<p>25/05    Regular Post   (0 Comments) </p>
						</div>
						<div class="post">
							<span class="option">(no title)</span>
							<p>I just discovered Gelato</p>
						</div>
					</div>
	
					<div class="entry">
						<div class="info"><form class="compact"><input type="submit" value="Edit" /> <input type="submit" value="Delete" /></form>
							<p>25/05    Regular Post   (0 Comments) </p>
						</div>
						<div class="post">
							<h4>One with a title</h4>
							<p>Another entry, this one has some varations in the content, even dares
							to be in two lines.</p>
						</div>
					</div>
					
					<div class="footer-box">&nbsp;</div>
				</div>
			</div>
			<div id="foot">
				<a href="http://www.gelatocms.com/" title="gelato CMS" target="_blank">gelato CMS</a> :: PHP/MySQL Tumblelog Content Management System.
			</div>
		</div>
	</body>
	</html>
<?
} else {
	header("Location: ".$conf->urlGelato."/login.php");
}
?>