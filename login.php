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
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>gelato :: login screen</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" href="<?=$conf->urlGelato;?>/images/favicon.ico" />
		<script language="javascript" type="text/javascript" src="<?=$conf->urlGelato;?>/admin/scripts/mootools.js"></script>
		<script language="javascript" type="text/javascript" src="<?=$conf->urlGelato;?>/admin/scripts/sortable.js"></script>
		<style type="text/css" media="screen">	
			@import "<?=$conf->urlGelato;?>/admin/css/style.css";
		</style>
	</head>
	
	<body>
		<div id="div-process" style="display:none;">Processing request...</div>
		<div id="cont">
			<div id="head">
				<h1><a href="<?=$conf->urlGelato;?>/" title="gelato :: home">gelato cms</a></h1>
				<ul id="nav">
					<li><a href="<?=$conf->urlGelato;?>/" title="Take me to the tumblelog">Back to the Tumblelog</a></li>
			  	</ul>
			</div>
			<div id="main">				
				
				<div class="box">
					<ul class="menu manage">
					<h3>Start session</h3>
					<li class="selected"><a href="#">Login</a></li>
					</ul>
				
					<div class="tabla">

								<form action="login.php" method="post" id="valida" autocomplete="off" class="newpost">
									<fieldset>
									<ul>
										<li><label for="login">User:</label>
											<input id="login" name="login" type="text" class="txt" /></li>
										<li><label for="pass">Password:</label>
											<input id="pass" name="pass" type="password" class="txt" /></li>
										<li><label for="save_pass">Remember me:</label>
											<input id="save_pass" name="save_pass" type="checkbox" /></li>
										<li><input name="btnLogin" type="submit" value="Login" /></li>
									</ul>
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
	}
}
?>