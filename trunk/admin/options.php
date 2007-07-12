<?php
/* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?php
require_once('../config.php');
include("../classes/functions.php");
include("../classes/user.class.php");
include("../classes/gelato.class.php");
require_once("../classes/configuration.class.php");

$user = new user();
$tumble = new gelato();
$conf = new configuration();

if ($user->isAdmin()) {
	
	if(isset($_POST["btnsubmit"]))	{		
		unset($_POST["btnsubmit"]);		
		$tumble->saveOption($_POST["rich_text"], "rich_text");
		$tumble->saveOption($_POST["urlFriendly"], "rich_text");
	} else {
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>gelato :: options</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="gelato cms <?php echo version();?>" />
		<link rel="shortcut icon" href="<?php echo $conf->urlGelato;?>/images/favicon.ico" />
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/mootools.js"></script>
		<script type="text/javascript">
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
		<div id="div-process" style="display:none;">Processing request...</div>
		<div id="cont">
			<div id="head">
				<h1><a href="<?php echo $conf->urlGelato;?>/" title="gelato :: home">gelato cms</a></h1>
				<ul id="nav">
					<li><a href="<?php echo $conf->urlGelato;?>/" title="Take me to the tumblelog">Back to the Tumblelog</a></li>
			  	</ul>
			</div>
			<div id="main">				
				
				<div class="box">
					<ul class="menu manage">
					<h3>Tumblelog options</h3>
					<li><a href="index.php">Post</a></li>
					<li><a href="admin.php">Users</a></li>
					<li><a href="settings.php">Settings</a></li>
					<li class="selected"><a>Options</a></li>
					</ul>
					<p>&nbsp;</p>
<?php
					if (isset($_GET["modified"])) {
						if ($_GET["modified"]=="true") {
							echo "<div class=\"exito\" id=\"divMessages\">The configuration has been modified successfully.</div>";
						}
					}					
					if (isset($_GET["error"])) {
						if ($_GET["error"]==1) {
							echo "<div class=\"error\" id=\"divMessages\"><strong>Error on the database server: </strong>".$_GET["des"]."</div>";
						}
					}
?>
					<div class="tabla">

						<form action="options.php" method="post" id="options_form" autocomplete="off" class="newpost">							
							<fieldset>								
								<ul>																	
									<li><label for="rich_text">Rich text editor:</label>
										<select name="rich_text" id="rich_text">										
											<option value="1" <?php if($conf->richText) echo "selected"; ?>>Active</option>
											<option value="0" <?php if(!$conf->richText) echo "selected"; ?>>Deactive</option>
										</select>
									</li>
									<li><label for="rich_text">URL friendly:</label>
										<select name="url_friendly" id="url_friendly">
											<option value="1" <?php if($conf->urlFriendly) echo "selected"; ?>>Active</option>
											<option value="0" <?php if(!$conf->urlFriendly) echo "selected"; ?>>Deactive</option>
										</select>
									</li>
								</ul>
							</fieldset>
							<p>
								<input type="submit" name="btnsubmit" id="btnsubmit" value="Modify" class="submit"/>
							</p>
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