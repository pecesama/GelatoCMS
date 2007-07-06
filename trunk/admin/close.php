<?php
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?php
require_once('../config.php');
include("../classes/user.class.php");

session_start();
$user = new user();
$user->closeSession()
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>gelato</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="../images/favicon.ico" />
	<meta http-equiv="Refresh" content="3;URL=../login.php" />
	<style type="text/css" media="screen">	
		@import "css/style-codice.css";
	</style>
</head>	
<body>

	<div id="titulo">
		<img src="../images/logo.jpg" alt="gelato CMS" title="gelato CMS" />	
	</div>

	<div id="contenido">
		<div class="piccola">
			<div  class="ventana">
				<p class="titulo"><span class="handle" style="cursor:move;">Closing session</span></p>
				<p>
<?php
if (@session_destroy()) {
?>
		</p>
				<h3>Ending session...</h3>
<?php
} else {	
?>
		<h3>Has happened an error when closing the session.</h3>
<?php
}	
?> 
				</p>
			</div>
		</div>
		<div id="pie">
			<p>
				<a href="http://www.gelatocms.com/" title="gelato CMS">gelato CMS</a> :: PHP/MySQL Tumblelog Content Management System.
			</p>
		</div>
	</div>	
</div>
</body>
</html>