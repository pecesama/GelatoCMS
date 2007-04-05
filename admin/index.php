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

$user = new user();
$conf = new configuration();
if ($user->isAdmin()) {	
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>gelato :: Control panel</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" href="<?=$conf->urlGelato;?>/images/favicon.ico" />
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
					}
				});
				contenedor.custom(1,0);
			}
		-->
		</script>
		<style type="text/css" media="screen">	
			@import "<?=$conf->urlGelato;?>/admin/css/style-codice.css";
		</style>
	</head>
	<body>
		<div id="div-process" style="display:none;"></div>
		<div id="titulo">
			<img src="<?=$conf->urlGelato;?>/images/logo.jpg" alt="gelato CMS" title="gelato CMS" />	
		</div>
		
		<div id="menuContenedor">
			<ul>
				<li id="active"><a href="#" id="current">Posts</a></li>
					<ul>
						<li id="subactive"><a href="#" id="subcurrent">Manage</a></li>
					</ul>
				</li>
				<li><a href="<?=$conf->urlGelato;?>/admin/admin.php">Manage users</a></li>
				<li><a href="<?=$conf->urlGelato;?>/">Take me to the tumblelog</a></li>				
				<li><a href="close.php" onclick="return exit('div-process','<?=$conf->urlGelato;?>/admin/ajax.php?action=close');">Logoff</a></li>
			</ul>
		</div>
		
		<div id="contenido">
			<div class="center">
				<div  class="ventana">
					<p class="titulo"><span class="handle">Manage the posts</span></p>
					<div id="formulario">
<?					
						if (isset($_GET["deleted"])) {
							if ($_GET["deleted"]=="true") {
								echo "<div class=\"exito\" id=\"divMessages\">The article has been eliminated successfully.</div>";
							}
						}
						
						if (isset($_GET["modified"])) {
							if ($_GET["modified"]=="true") {
								echo "<div class=\"exito\" id=\"divMessages\">The article has been modified successfully.</div>";
							}
						}
						
						if (isset($_GET["added"])) {
							if ($_GET["added"]=="true") {
								echo "<div class=\"exito\" id=\"divMessages\">The article has been added successfully.</div>";
							}
						}
						
						if (isset($_GET["error"])) {
							if ($_GET["error"]==2) {
								echo "<div class=\"error\"><strong>Error on the database server: </strong>".$_GET["des"]."</div>";
							}
						}
						
						if (isset($_GET["mp3"])) {
							if ($_GET["mp3"]=="false") {
								echo "<div class=\"error\" id=\"divMessages\">Not an MP3 file or an upload problem.</div>";
							}
						}
						
						if (isset($_GET["photo"])) {
							if ($_GET["photo"]=="false") {
								echo "<div class=\"error\" id=\"divMessages\">Not a photo file or an upload problem.</div>";
							}
						}
?>
						<div id="menu">
							<h2>&nbsp;</h2>
							<div class="opcion">
								<a href="<?=$conf->urlGelato;?>/admin/add.php?new=post"><img alt="New regular post" src="../images/post.gif" /></a>
							</div>
							<div class="opcion">
								<a href="<?=$conf->urlGelato;?>/admin/add.php?new=photo"><img alt="New photo" src="../images/foto.gif" /></a>
							</div>
							<div class="opcion">
								<a href="<?=$conf->urlGelato;?>/admin/add.php?new=quote"><img alt="New quote" src="../images/cita.gif" /></a>
							</div>
							<div class="opcion">
								<a href="<?=$conf->urlGelato;?>/admin/add.php?new=url"><img alt="New link" src="../images/enlace.gif" /></a>
							</div>							
							<div class="opcion">
								<a href="<?=$conf->urlGelato;?>/admin/add.php?new=conversation"><img alt="New conversation" src="../images/conversacion.gif" /></a>
							</div>
							<div class="opcion">
								<a href="<?=$conf->urlGelato;?>/admin/add.php?new=video"><img alt="New video" src="../images/video.gif" /></a>
							</div>
							<div class="opcion">
								<a href="<?=$conf->urlGelato;?>/admin/add.php?new=mp3"><img alt="New MP3" src="../images/mp3.gif" /></a>
							</div>
						</div>
						<p>&nbsp;</p>
						<h2>Title 1</h2>
						<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas facilisis scelerisque ligula. Aliquam accumsan. In hac habitasse platea dictumst. Nulla ut urna eget felis tempor pellentesque. Fusce sollicitudin ultricies lacus. Duis felis. Mauris posuere enim ac tortor scelerisque tempus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Fusce porta. Integer odio purus, semper malesuada, eleifend in, volutpat non, quam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Aliquam pellentesque aliquet turpis. Quisque non enim sed enim ullamcorper aliquet. Integer sit amet arcu.</p>
						<p>&nbsp;</p>
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
} else {
	header("Location: ".$conf->urlGelato."/login.php");
}
?>