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
	} elseif (isset($_GET["new"])) {	
?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
		<head>
			<title>gelato</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<link rel="shortcut icon" href="<?=$conf->urlGelato;?>/images/favicon.ico" />
			<script language="javascript" type="text/javascript" src="<?=$conf->urlGelato;?>/admin/scripts/tiny_mce/tiny_mce.js"></script>
			<script language="javascript" type="text/javascript" src="<?=$conf->urlGelato;?>/admin/scripts/tools.js"></script>
			<script language="javascript" type="text/javascript" src="<?=$conf->urlGelato;?>/admin/scripts/mootools.js"></script>			
			<style type="text/css" media="screen">	
				@import "<?=$conf->urlGelato;?>/admin/css/style-codice.css";
			</style>
		</head>
		<body>
			<div id="div-process" style="display:none;">Processing request...</div>
			<div id="titulo">
				<img src="<?=$conf->urlGelato;?>/images/logo.jpg" alt="gelato CMS" title="gelato CMS" />	
			</div>
			
			<div id="menuContenedor">
				<ul>
					<li id="active"><a href="#" id="current">Posts</a></li>
						<ul>
							<li id="subactive"><a href="#" id="subcurrent">Add</a></li>
						</ul>
					</li>
					<li><a href="index.php">Control Panel</a></li>
				</ul>
			</div>
			
			<div id="contenido">
				<div class="center">
					<div  class="ventana">
						<p class="titulo"><span class="handle">Add content</span></p>
						<div id="formulario">
							<form action="add.php" method="post" <?=($_GET["new"]=="photo") ? "enctype=\"multipart/form-data\"" : ""?> name="frmAdd">
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
								echo "<div class=\"error\">The specified type is not valid.</div>";
								break;
						}
?>
									<p>
										<input class="submit" type="submit" name="btnAdd" value="Create post" />&nbsp;&nbsp;
										<a href="#" onclick="if (confirm('Cancel editing this post?  All changes will be lost.'))
	{location.href='index.php';}; return false;">Cancel</a>
									</p>
								</fieldset>
							</form>
						</div>
					</div> 
				</div> 		
				<div id="pie">
					<p>
						<a href="http://www.gelatocms.com/" title="gelato CMS" target="_blank">gelato CMS</a> :: PHP/MySQL Tumblelog Content Management System.
					</p>
				</div>				
			</div>
		</body>
		</html>
<?
	}
} else {
	header("Location: ".$conf->urlGelato."/login.php");
}
?>