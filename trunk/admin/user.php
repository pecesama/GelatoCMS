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
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
		<head>
			<title>gelato :: Add user</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<link rel="shortcut icon" href="<?=$conf->urlGelato;?>/images/favicon.ico" />
			<script language="javascript" type="text/javascript" src="<?=$conf->urlGelato;?>/admin/scripts/tiny_mce/tiny_mce.js"></script>
			<script language="javascript" type="text/javascript" src="<?=$conf->urlGelato;?>/admin/scripts/mootools.js"></script>
			<script language="javascript" type="text/javascript">
				tinyMCE.init({
					width : "100%",
					mode : "textareas",
					theme : "simple"
				});
				
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
				@import "<?=$conf->urlGelato;?>/admin/css/style-codice.css";
			</style>
		</head>
		<body>
			<div id="div-process" style="display:none;">Processing request...</div>
			<div id="titulo">
				<img src="<?=$conf->urlGelato;?>/images/logo.jpg" alt="gelato CMS" title="gelato CMS" />	
			</div>
			
			<div id="menuContenedor">
				<ul>
					<li id="active"><a href="#" id="current">Users</a></li>
						<ul>
							<li id="subactive"><a href="#" id="subcurrent"><? echo ($isEdition) ? "Edit" : "Add"; ?></a></li>
							<li><a href="admin.php">Manage</a></li>
						</ul>
					</li>
					<li><a href="index.php">Control Panel</a></li>
				</ul>
			</div>
			
			<div id="contenido">
				<div class="center">
					<div  class="ventana">
						<p class="titulo"><span class="handle"><? echo ($isEdition) ? "Edit" : "Add"; ?> the user information</span></p>
						<div id="formulario">
						
							<form action="user.php" method="post" onSubmit="return validateFrmAddUser();" name="frm_add">
							<fieldset>
<?
								if ($isEdition) {
?>
								<input type="hidden" name="id_user" id="id_user" value="<?=$userId;?>" />
<?
								}
?>
								<p>
									<label for="login">user:</label>
										<input class="input-text" name="login" id="login" type="text" autocomplete="off" value="<?=isset($register["login"])?$register["login"]:"";?>" />
<?
								if (!$isEdition) {
?>
										<script language="javascript" type="text/javascript">						
											document.write("<br /><input class='submit_normal_azul' name='btnVerifyUser' id='btnVerifyUser' type='button' value='Check availability' onclick='verifyExistingUser()' />");
										</script>
<?
								}
?>
								</p>
								<p>
									<div id="target" style="display:none;"></div>
								</p>
								<p>
									<label for="pass">password:</label>
										<input class="input-text" name="password" id="password" type="password" />
								</p>
								<p>
									<label for="repass">retype password:</label>
										<input class="input-text" name="repass" id="repass" type="password" />
								</p>
								<p>
									<label for="name">name:</label>
										<input class="input-text" name="name" id="name" type="text" value="<?=isset($register["name"])?$register["name"]:"";?>" />
								</p>
								<p>
									<label for="email">e-mail:</label>
										<input class="input-text" name="email" id="email" type="text" value="<?=isset($register["email"])?$register["email"]:"";?>" />
								</p>
								<p>
									<label for="website">website:</label>
										<input class="input-text" name="website" id="website" type="text" value="<?=isset($register["website"])?$register["website"]:"";?>" />
								</p>								
								<p>
									<label for="about">about:</label><br />
										<textarea rows="5" cols="50" name="about" id="about" tabindex="7"><?=isset($register["about"])?$register["about"]:"";?></textarea>
									<script type="text/javascript">
										//<!--
										edCanvas = document.getElementById('about');
											// This code is meant to allow tabbing from website to about (TinyMCE).
										if ( tinyMCE.isMSIE )
											document.getElementById('website').onkeydown = function (e)
												{
													e = e ? e : window.event;
													if (e.keyCode == 9 && !e.shiftKey && !e.controlKey && !e.altKey) {
														var i = tinyMCE.selectedInstance;
														if(typeof i ==  'undefined')
															return true;
																		tinyMCE.execCommand("mceStartTyping");
														this.blur();
														i.contentWindow.focus();
														e.returnValue = false;
														return false;
													}
												}
										else
											document.getElementById('website').onkeypress = function (e)
												{
													e = e ? e : window.event;
													if (e.keyCode == 9 && !e.shiftKey && !e.controlKey && !e.altKey) {
														var i = tinyMCE.selectedInstance;
														if(typeof i ==  'undefined')
															return true;
																		tinyMCE.execCommand("mceStartTyping");
														this.blur();
														i.contentWindow.focus();
														e.returnValue = false;
														return false;
													}
												}
											//-->
									</script>
								</p>								
								<p>
									<input class="submit" name="btnAdd" type="submit" value="<? echo ($isEdition) ? "Modify" : "Add"; ?> user" />
								</p>
							</fieldset>
							</form>
						</div>
					</div> 
				</div> 		
				<div id="pie">
					<p>
						<a href="http://www.gelatocms.com/" title="gelato CMS" target="_blank">gelato CMS</a> :: PHP/MySQL Tumblelog Content Management System.
					</p>
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