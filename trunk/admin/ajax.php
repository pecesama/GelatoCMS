<?
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?
	require_once('../config.php');
	include("../classes/user.class.php");
	$user = new user();
	if ($user->isAdmin()) {
		if ($_GET["action"]) {
			
			if ($_GET["action"] == "close") {
				session_start();
				if ($user->closeSession()) {
					echo "&nbsp;ending session...";
				} else {
					echo "&nbsp;failure ending session...";
				}
			}	// $_GET["action"] == "close"
			
			if ($_GET["action"] == "verify") {
				if ($_GET["login"]=="") {
					echo "<div class=\"error\">Required field cannot be left blank.</div>";
				} else {
					if (!$user->userExist($_GET["login"])) {
						echo "<div class=\"exito\">Username available.</div>";
					} else {
						echo "<div class=\"error\">The username is not available.</div>";
					}
				}
			}	// $_GET["action"] == "verify"			
		}	// $_GET["action"]
	}	// $user->isAdmin()
?>