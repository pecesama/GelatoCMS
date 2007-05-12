<?
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?
require_once('../config.php');
include("../classes/functions.php");
include("../classes/user.class.php");
require_once("../classes/configuration.class.php");

$user = new user();
$conf = new configuration();
$isEdition = isset($_GET["edit"]);
$userId = ($isEdition) ? $_GET["edit"] : NULL;
if ($user->isAdmin()) {

	if (isset($_GET["delete"])) {
		$user->deleteUser($_GET['delete']);
		header("Location: admin.php?delete=true");
		die();
	}
	
	if(isset($_POST["btnAdd"]))	{		
		unset($_POST["btnAdd"]);
		if (isset($_POST["repass"])) {
			unset($_POST["repass"]);
		}
		if (isset($_POST["btnVerifyUser"])) {
			unset($_POST["btnVerifyUser"]);
		}
		if (isset($_POST["password"])) {
			$_POST["password"] = md5($_POST["password"]);
		}
		
		if (isset($_POST["id_user"])) {
			$user->modifyUser($_POST, $_POST["id_user"]);
		} else {			
			$user->addUser($_POST);
		}		
	} else {
		if ($isEdition) {
			$register = $user->getUserByID($userId);
		}
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>gelato :: add user</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" href="<?=$conf->urlGelato;?>/images/favicon.ico" />
		<script language="javascript" type="text/javascript" src="<?=$conf->urlGelato;?>/admin/scripts/mootools.js"></script>
		<script language="javascript" type="text/javascript">								
			function validateFrmAddUser() {
				if ($('login').value == "") {
				   alert("The username field cannot be left blank.");
				   document.frm_add.login.select();	
				   return false;
				}
				if ($('password').value == "") {
				   alert("The password field cannot be left blank.");
				   document.frm_add.password.select();	
				   return false;
				}	
				if ($('password').value != $('repass').value) {
				   alert("The password must match,\nplease verify them.");
				   document.frm_add.password.focus();	
				   return false;
				}		
				return true;
			}
			
			function verifyExistingUser() {
				$('div-process').style.display="block";
				el = $('target');
				el.style.display="block";										
				var path = 'ajax.php?action=verify&login='+$('login').value;
				new Ajax(path, {
					onComplete:function(e) {						
						el.setHTML(e);
						$('div-process').style.display="none";
					}
				}).request();
				return false;
			}
		</script>
		<style type="text/css" media="screen">	
			@import "<?=$conf->urlGelato;?>/admin/css/style.css";
		</style>
	</head>
	
	<body>
		<div id="div-process" style="display:none;">Processing request...</div>
		<div id="cont">
			<div id="head">
				<h1><a href="<?=$conf->urlGelato;?>/" title="gelato :: home">gelato cms</a></h1>
				<ul id="nav">
					<li><a href="<?=$conf->urlGelato;?>/" title="Take me to the tumblelog">Back to the Tumblelog</a></li>
			  	</ul>
			</div>
			<div id="main">				
				
				<div class="box">
					<ul class="menu manage">
					<h3>Start session</h3>
					<li><a href="index.php">Post</a></li>
					<li><a href="admin.php">Users</a></li>
					<li class="selected"><a href="#"><? echo ($isEdition) ? "Edit" : "Add"; ?></a></li>
					</ul>
				
					<div class="tabla">

						<form action="user.php" method="post" onSubmit="return validateFrmAddUser();" name="frm_add" class="newpost">
						<fieldset>
						<ul>
<?
							if ($isEdition) {
?>
							<input type="hidden" name="id_user" id="id_user" value="<?=$userId;?>" />
<?
							}
?>
							<li>
								<label for="login">user:</label>
									<input class="txt" name="login" id="login" type="text" autocomplete="off" value="<?=isset($register["login"])?$register["login"]:"";?>" />
<?
							if (!$isEdition) {
?>
									<script language="javascript" type="text/javascript">						
										document.write("<br /><input class='submit_normal_azul' name='btnVerifyUser' id='btnVerifyUser' type='button' value='Check availability' onclick='verifyExistingUser()' />");
									</script>
<?
							}
?>
							</li>
							<li>
								<div id="target" style="display:none;"></div>
							</li>
							<li>
								<label for="pass">password:</label>
									<input class="txt" name="password" id="password" type="password" />
							</li>
							<li>
								<label for="repass">retype password:</label>
									<input class="txt" name="repass" id="repass" type="password" />
							</li>
							<li>
								<label for="name">name:</label>
									<input class="txt" name="name" id="name" type="text" value="<?=isset($register["name"])?$register["name"]:"";?>" />
							</li>
							<li>
								<label for="email">e-mail:</label>
									<input class="txt" name="email" id="email" type="text" value="<?=isset($register["email"])?$register["email"]:"";?>" />
							</li>
							<li>
								<label for="website">website:</label>
									<input class="txt" name="website" id="website" type="text" value="<?=isset($register["website"])?$register["website"]:"";?>" />
							</li>								
							<li>
								<label for="about">about:</label><br />
									<textarea rows="5" cols="50" name="about" id="about" tabindex="7"><?=isset($register["about"])?$register["about"]:"";?></textarea>									
							</li>								
							<li>
								<input name="btnAdd" type="submit" value="<? echo ($isEdition) ? "Modify" : "Add"; ?> user" />
							</li>
						</ul>
						</fieldset>
						</form>
								
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
	}
} else {
	header("Location: ".$conf->urlGelato."/login.php");
}
?>