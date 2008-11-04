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
	require_once('../entry.php');
	global $user;
	
	if ($user->isAdmin()) {
		if ($_GET["action"]) {
			
			if ($_GET["action"] == "close") {
				if ($user->closeSession()) {
					echo __("&nbsp;ending session&hellip;");
				} else {
					echo __("&nbsp;failure ending session&hellip;");
				}
			}	// $_GET["action"] == "close"
			
			if ($_GET["action"] == "verify") {
				if ($_GET["login"]=="") {
					echo "<div class=\"error\">".__("Required field cannot be left blank.")."</div>";
				} else {
					if (!$user->userExist($_GET["login"])) {
						echo "<div class=\"exito\">".__("Username available.")."</div>";
					} else {
						echo "<div class=\"error\">".__("The username is not available.")."</div>";
					}
				}
			}	// $_GET["action"] == "verify"			
		}	// $_GET["action"]
	}	// $user->isAdmin()
?>