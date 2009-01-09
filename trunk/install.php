<?php
if(!defined('entry') || !entry) define('entry',true);
/* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */

// Received a valid request, better start setting globals we'll need throughout the app in entry.php
require_once('entry.php');

$configFile = Absolute_Path."config.php";
if(file_exists($configFile)){
	require_once($configFile);
}else{
	exit('You need to rename config-sample.php to config.php and fill out the required details.');
}

global $user, $conf, $tumble;
$install = new Install();
if($install->is_gelato_installed()){
	header("location: index.php");
	exit;
}
$install->data = $_POST;

$install->check_form();
$theme = new themes;
$theme->set('version',util::version());
$theme->set('showForm',$install->showForm);

$theme->set('db_login',isset($install->data['db_login'])? $install->data['db_login'] : '');
$theme->set('login',isset($install->data['login'])?$install->data['login']:'');
$theme->set('email',isset($install->data['email'])?$install->data['email']:'');
$theme->set('title',isset($install->data['title'])?$install->data['title']:'');
$theme->set('description',isset($install->data['description'])?$install->data['description']:'');

$theme->set('url_installation',(isset($install->data['url_installation']) and $install->data['url_installation'])?$install->data['url_installation']:(isset($_SERVER['SCRIPT_URI'])?substr($_SERVER["SCRIPT_URI"], 0, -12):''));
$theme->set('themes',util::getThemes());

for($c=1;$c<=10;$c++)$errores[$c] = $install->mostrarerror($c);
$theme->set('error',$errores);

$theme->display(Absolute_Path.'admin/themes/admin/install.htm');
?>
