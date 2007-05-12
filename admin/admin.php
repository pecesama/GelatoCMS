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
require_once("../classes/configuration.class.php");

$user = new user();
$conf = new configuration();

if ($user->isAdmin()) {
	
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>gelato :: admin users</title>
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
				<h1><a href="<?=$conf->urlGelato;?>/admin/index.php" title="gelato :: home">gelato cms</a></h1>
				<ul id="nav">
					<li><a href="<?=$conf->urlGelato;?>/" title="Take me to the tumblelog">View Tumblelog</a></li>
					<li><a href="close.php" title="Log off" onclick="return exit('div-process','<?=$conf->urlGelato;?>/admin/ajax.php?action=close');">Log out</a></li>
			  	</ul>
			</div>
			<div id="main">				
				
				<div class="box">
					<ul class="menu manage">
					<h3>Manage</h3>
					<li><a href="#">Settings</a></li>
					<li><a href="<?=$conf->urlGelato;?>/admin/index.php">Posts</a></li>
					<li><a href="<?=$conf->urlGelato;?>/admin/user.php">Add user</a></li>
					<li class="selected"><a href="#">Users</a></li>
					</ul>

<?					
						if (isset($_GET["added"])) {
							if ($_GET["added"]=="true") {
								echo "<div class=\"exito\">The user has been added successfully.</div>";
							}
						}
						
						if (isset($_GET["delete"])) {
							if ($_GET["delete"]=="true") {
								echo "<div class=\"exito\">The user has been eliminated successfully.</div>";
							}
						}
						
						if (isset($_GET["modified"])) {
							if ($_GET["modified"]=="true") {
								echo "<div class=\"exito\">The user has been modified successfully.</div>";
							}
						}
						
						if (isset($_GET["error"])) {
							if ($_GET["error"]==1) {
								echo "<div class=\"error\">The username is not available.</div>";
							} elseif ($_GET["error"]==2) {
								echo "<div class=\"error\"><strong>Error on the database server: </strong>".$_GET["des"]."</div>";
							}
						}
?>						
						<div class="tabla">
						<table class="sortable" id="admin-table">
							<thead>
								<tr>
									<th scope="col">Login</th>
									<th scope="col">Name</th>
									<th colspan="2" scope="col" class="unsortable">Actions</th>
								</tr>
							</thead>
							<tbody>
<?	
					$odd=false;
					$rs = $user->getUsers();
					if ($user->contarRegistros()>0) {
						while($register = mysql_fetch_array($rs)) {
?>
							<tr <? if ($odd) { echo 'class="odd"'; } $odd=!$odd; ?>>
								<td>
									<? echo $register["login"]."\n"; ?>
								</td>
								<td>
									<? echo $register["name"]."\n"; ?>
								</td>
								<td>
									<a href="user.php?edit=<?=$register["id_user"]; ?>">Edit</a>
								</td>
								<td>
									<a href="user.php?delete=<?=$register["id_user"]; ?>">Delete</a>	
								</td>
							</tr>
<?
						}
					}
					else {
?>
						<tr> 
							<td colspan="4"><div class="exito">No users available.</div></td>
						</tr>
<?
					}
?>
							</tbody>
						</table>
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
} else {
	header("Location: ".$conf->urlGelato."/login.php");
}
?>