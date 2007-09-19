<?php
if(!defined('entry'))define('entry', true);

session_start();

function isAjax() { 
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
	$_SERVER ['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'; 
}

function saveForm() {
	$type = getMethod();
	$id = ($type=='GET') ? $_GET['autosaveid'] : $_POST['autosaveid'];
	$_SESSION[$id] = $_SERVER['QUERY_STRING'];
	echo gmdate('H:i | d/m/y',time()+transform_offset($conf->offsetTime));
}

function loadForm() {
	$type = getMethod();
	$id = ($type=='GET') ? $_GET['autosaveid'] : $_POST['autosaveid'];
	if(isset($_SESSION[$id]))
		echo $_SESSION[$id];
}

function isLoad() {
	$type = getMethod();
	if($type=='GET' and isset($_GET['autosave'])) return true;
	elseif(isset($_POST['autosave'])) return true;
	return false;
}

function getMethod() {
	return $_SERVER['REQUEST_METHOD'];
}

if(isAjax()) {
	if(isLoad()) loadForm();
	else saveForm();
}

exit;

?>