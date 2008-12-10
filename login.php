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
header("Cache-Control: no-cache, must-revalidate");

require_once('entry.php');
global $user, $conf;

if ($user->isAuthenticated()) {
	header("Location: ".$conf->urlGelato."/admin/index.php");
} else {
	if (isset($_POST["pass"]) && isset($_POST["login"])) {		
		if ($user->validateUser($_POST['login'], md5($_POST['pass']))) {
			if(isset($_POST["url_redirect"])){
				header("Location: ".$conf->urlGelato."/admin/bm.php?url=".$_POST["url_redirect"]."&sel=".$_POST["sel"]);
				exit();
			} else {
				header("Location: ".$conf->urlGelato."/admin/index.php");
				exit();
			}
		} else {			
			header("Location: ".$conf->urlGelato."/login.php?error=1");
			exit();
		}
	} else {
		$theme = new themes;
		$theme->set('version',util::version());
		$theme->set('redirect_url',(isset($_GET['redirect_url'])?$_GET['redirect_url']:''));
		$theme->set('sel',(isset($_GET['sel'])?$_GET['sel']:''));
		$theme->set('error',(isset($_GET['error'])?$_GET['error']:''));
		$theme->set('conf',array(
			"urlGelato"=>$conf->urlGelato
		));
		$theme->display(Absolute_Path.'admin/themes/admin/login.htm');
	}
}
?>
