<?
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?
header("Cache-Control: no-cache, must-revalidate");
require( dirname(__FILE__) . '/config.php' );
include(dirname(__FILE__)."/classes/user.class.php");
require_once(dirname(__FILE__)."/classes/configuration.class.php");

$user = new user();
$conf = new configuration();
if ($user->isAdmin()) {
	header("Location: ".$conf->urlGelato."/admin/index.php");
} else {
	if (isset($_POST["pass"]) && isset($_POST["login"])) {		
		if ($user->validateUser($_POST['login'], md5($_POST['pass']))) {
			header("Location: ".$conf->urlGelato."/admin/index.php");
		} else {
			header("Location: ".$conf->urlGelato."/login.php?error=1");
		}
	}
	else {
?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
		<head>
			<title>gelato :: Login screen</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<link rel="shortcut icon" href="<?=$conf->urlGelato;?>/images/favicon.ico" />
			<script src="<?=$conf->urlGelato;?>/admin/scripts/mootools.js" type="text/javascript"></script>
			<style type="text/css" media="screen">	
				@import "<?=$conf->urlGelato;?>/admin/css/style-codice.css";
			</style>
		</head>
		<body>
	
			<div id="titulo">
				<img src="<?=$conf->urlGelato;?>/images/logo.jpg" alt="gelato CMS" title="gelato CMS" />	
			</div>
				
			 <div id="menuContenedor">
				<ul>
					<li id="active"><a href="<?=$conf->urlGelato;?>/login.php" id="current">Session</a></li>
						<ul>
							<li id="subactive"><a href="#" id="subcurrent">Begin session</a></li>
						</ul>
					</li>
					<li><a href="index.php">Back to the tumblelog</a></li>
				</ul>
			</div>
			
			<div id="contenido">		
				<div class="piccola">
					<div  class="ventana">
						<p class="titulo"><span class="handle" style="cursor:move;">Ingresar al sistema</span></p>
						<div id="formulario">
								<form action="login.php" method="post" id="valida" autocomplete="off">
									<fieldset>
										<p><label for="login">User:</label>
											<input id="login" name="login" type="text" class="input-corto" /></p>
										<p><label for="pass">Password:</label>
											<input id="pass" name="pass" type="password" class="input-corto" /></p>
										<p><label for="save_pass">Remember me:</label>
											<input id="save_pass" name="save_pass" type="checkbox" class="check" /></p>
										<p><input class="submit" name="btnLogin" type="submit" value="Login" /></p>
									</fieldset>
								</form>
<?					
								if (isset($_GET["error"])) {
									if ($_GET["error"]==1) {
?>
								<div class="error">
									&nbsp;You must be registered to use gelato.
								</div>
<?
									}
									elseif ($_GET["error"]==2) {
?>
								<div class="error">
									&nbsp;You must be logged on the system.
								</div>
<?
									}									
								}
?>		
					  </div>
						</p>  
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
}
?>