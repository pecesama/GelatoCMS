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
require_once("../classes/configuration.class.php");

session_start();
$user = new user();
$conf = new configuration();
$user->closeSession()
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>gelato :: <?=__("logout")?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="gelato cms <?php echo version();?>" />
		<meta http-equiv="Refresh" content="3;URL=../login.php" />
		<link rel="shortcut icon" href="<?php echo $conf->urlGelato;?>/images/favicon.ico" />
		<style type="text/css" media="screen">	
			@import "<?php echo $conf->urlGelato;?>/admin/css/style.css";
		</style>
	</head>
<body>
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
					<h3><?=__("Closing session")?></h3>
					<li class="selected"><a><?=__("logoff")?></a></li>
					</ul>
				
					<div class="tabla">
						<p>
<?php
						if (@session_destroy()) {
?>		
							<h2><?=__("Ending session...")?></h2>
<?php
						} else {	
?>
							<h2><?=__("Has happened an error when closing the session.")?></h2>
<?php
						}	
?> 
						</p>
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