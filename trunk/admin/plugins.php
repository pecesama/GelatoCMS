<?php
if(!defined('entry')) define('entry',true);  
/* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */
?>
<?php
require_once('../entry.php');
global $user, $conf, $tumble;


if ($user->isAdmin()) {
	
	
	$plugins = array();
	if ($handle = opendir(Absolute_Path."plugins")) {
		while (false !== ($file = readdir($handle))) { 
			if (substr($file, strlen($file)-4, 4) == ".php") {
				$plugins[] = substr($file, 0, strlen($file)-4);
			} 
		} 
		closedir($handle); 
	}
	
	$actives = json_decode($conf->active_plugins,1);	
	
	$actives = $actives[1];
	
	if(isset($_POST["btnsubmit"]))	{
		$actives = array();
		foreach($_POST['plugins'] as $plugin => $val){
			if($val != 'off'){
				$file = $plugin.'.php';
				$actives[$plugin] = $file;
			}
		}
		
		if(!$tumble->saveOption(json_encode(array(array('total'=>count($actives)),$actives)), "active_plugins")){
			header("Location: ".$conf->urlGelato."/admin/plugins.php?error=1&desc=".$conf->merror);
			die();
		}
		header("Location: ".$conf->urlGelato."/admin/plugins.php?modified=true");
		die();
	} else {
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>gelato :: <?php echo __("Plugins")?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="gelato cms <?php echo util::version();?>" />
		<link rel="shortcut icon" href="<?php echo $conf->urlGelato;?>/images/favicon.ico" />
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/jquery.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/tools.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){
			$("#divMessages").fadeOut(5000,function(){
				$("#divMessages").css({display:"none"});
			});
		});
		</script>		
		<style type="text/css" media="screen">	
			@import "<?php echo $conf->urlGelato;?>/admin/css/style.css";
		</style>
	</head>
	
	<body>
		<div id="div-process" style="display:none;"><?php echo __("Processing request&hellip;")?></div>
		<div id="cont">
			<div id="head">
				<h1><a href="<?php echo $conf->urlGelato;?>/admin/index.php" title="gelato :: <?php echo __("home")?>">gelato cms</a></h1>
				<ul id="nav">
					<li><a href="<?php echo $conf->urlGelato;?>/" title="<?php echo __("Take me to the tumblelog")?>"><?php echo __("Back to the Tumblelog")?></a></li>
			  	</ul>
			</div>
			<div id="main">				
				
				<div class="box">
					<ul class="menu manage">
					<h3><?php echo __("Tumblelog options")?></h3>
					<li><a href="index.php"><?php echo __("Post")?></a></li>
					<li><a href="admin.php"><?php echo __("Users")?></a></li>
					<li><a href="settings.php"><?php echo __("Settings")?></a></li>
					<li><a href="options.php"><?php echo __("Options")?></a></li>
					<li class="selected"><a href="plugins.php"><?php echo __("Plugins")?></a></li>
					</ul>
<?php
					if (isset($_GET["modified"])) {
						if ($_GET["modified"]=="true") {
							echo "<div class=\"exito\" id=\"divMessages\">".__("The configuration has been modified successfully.")."</div>";
						}
					}					
					if (isset($_GET["error"])) {
						if ($_GET["error"]==1) {
							echo "<div class=\"error\" id=\"divMessages\"><strong>".__("Error on the database server: ")."</strong>".$_GET["des"]."</div>";
						}
					}
?>
					<div class="tabla">

						<form action="plugins.php" method="post" id="options_form" autocomplete="off" class="newpost">							
							<fieldset>
								<?php
									if(count($plugins) == 0){
										echo __('You dont have any plugin installed, get some <a href="http://www.gelatocms.com/">here</a>');
									}else{
										foreach ($plugins as $key => $plugin) {
											
											//FIXME terminar esto asi se lee la info desde el archivo 'a la' WP
											/*
											$plugin_data = implode( '', file( Absolute_Path."plugins/".$plugin.'.php' ));
											preg_match( '|Plugin Name:(.*)$|mi', $plugin_data, $plugin_name );
											preg_match( '|Plugin URI:(.*)$|mi', $plugin_data, $plugin_uri );
											preg_match( '|Description:(.*)$|mi', $plugin_data, $description );
											preg_match( '|Author:(.*)$|mi', $plugin_data, $author_name );
											preg_match( '|Author URI:(.*)$|mi', $plugin_data, $author_uri );

											if ( preg_match( "|Version:(.*)|i", $plugin_data, $version ))
											$version = trim( $version[1] );
											else
											$version = '';

											$plugin_data = array('Name' => trim($plugin_name[1]), 'URI' => trim($plugin_uri[1]), 'Description' => trim($description[1]), 'Author' => trim($author_name[1]), 'Author_uri' => trim($author_uri[1]), 'Version' => $version);
*/
											$desc = __("There is no info for this plugin jet");
											$activated = array_key_exists($plugin, $actives);
								?>								
								<ul>	
									<li class="select">
										<label for="<?php echo $key;?>" title="<?php echo $desc; ?>" class="help"><?php echo $plugin;	?></label>
										<select name="plugins[<?php echo $plugin;	?>]" id="<?php echo $key;?>">
											<option value="on" <?php if($activated) echo 'selected="selected"'; ?>><?php echo __("On")?></option>
											<option value="off" <?php if(!$activated) echo 'selected="selected"'; ?>><?php echo __("Off")?></option>
										</select>
									</li>
									<?php
										}
									?>
								</ul>
								<?php
									}
								?>
							</fieldset>
							<p>
								<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo __("Modify")?>" class="submit"/>
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