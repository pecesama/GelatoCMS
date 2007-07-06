<?php
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?php

$configFile = dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";
	
if (!file_exists($configFile)) {
	$mensaje = "
			<h3 class=\"important\">Error reading configuration file</h3>			
			<p>There doesn't seem to be a <code>config.php</code> file. I need this before we can get started.</p>
			<p>This either means that you did not rename the <code>config-sample.php</code> file to <code>config.php</code>.</p>";
	die($mensaje);
} else {
	require(dirname(__FILE__).DIRECTORY_SEPARATOR."config.php");
	$showForm = true;
}

include("classes/functions.php");
 
$errors_d=array();
$errors_d[1]="The login field cannot be empty";
$errors_d[2]="The password field cannot be empty";
$errors_d[3]="Password does not match the confirm password";
$errors_d[4]="The e-mail field cannot be empty";
$errors_d[5]="The installation URL field cannot be empty";
$errors_d[6]="Error establishing a database connection";

$action="";
$errors="";

if (isset($_POST['action'])){
	$action=$_POST['action'];
}

if ($action=="config") {
	
	$sep_err="";
	
	if (!$_POST['login']) {
		$errors=$errors.$sep_err."1";
		$sep_err="|";
	}
	if (!$_POST['password']) {
		$errors=$errors.$sep_err."2";
		$sep_err="|";
	}
	if (!$_POST['email']) {
		$errors=$errors.$sep_err."4";
		$sep_err="|";
	}
	if (!$_POST['url_installation']) {
		$errors=$errors.$sep_err."5";
		$sep_err="|";
	}
	if ($_POST['password']!=$_POST['password2']) {
		$errors=$errors.$sep_err."3";
		$sep_err="|";
	}
	
	
	if (!$errors) {		
		
		if (installdb($_POST['login'], $_POST['password'], $_POST['email'], $_POST['title'], $_POST['description'], $_POST['url_installation'], $_POST['posts_limit'], $_POST['lang'], $_POST['template'], $_POST['website'], $_POST['about'], $_POST['url_friendly'], $_POST['rich_text'])) {
			$showForm=false;
		} else {
			$errors=$errors.$sep_err."6";
			$sep_err="|";
			$showForm=true;
		}		
	} else {
		$showForm=true;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>gelato :: installation</title>
	<meta name="generator" content="sabros.us <?php echo version();?>" />	
	<link rel="shortcut icon" href="images/favicon.ico" />
	<style type="text/css" media="screen">	
		@import "admin/css/style.css";		
	</style>		
</head>
<body>
<div id="cont">
	<div id="head">
		<h1><a href="index.php" title="gelato :: home">gelato cms</a></h1>
	</div>
	
	<div id="main">
	
<?

	if ($showForm) {
?>
	
	<div class="box">
		<ul class="menu manage">
		<h3>gelato :: installation</h3>

		<li class="selected"><a>Install</a></li>
		</ul>
	
		<div class="tabla">
			<form action="install.php" method="post" id="config_form" autocomplete="off" class="newpost">
				<fieldset>
					<legend class="install">Admin user</legend>
					<ul>
						<li><label for="login">User:</label>
							<input type="text" name="login" id="login" value="" class="txt"/><?php echo mostrarerror($errors,$errors_d,"1")?></li>
						<li><label for="password">Password:</label>
							<input type="password" name="password" id="password" value="" class="txt"/><?php echo mostrarerror($errors,$errors_d,"2")?></li>
						<li><label for="password2">Re-type password:</label>
							<input type="password" name="password2" id="password2" value="" class="txt"/><?php echo mostrarerror($errors,$errors_d,"3")?></li>						
						<li><label for="email">E-mail:</label>
							<input type="text" name="email" id="email" value="" class="txt"/><?php echo mostrarerror($errors,$errors_d,"4")?></li>						
					</ul>
				</fieldset><br  />
				<fieldset>
					<legend class="install">Tumblelog configuration</legend>
					<ul>							
						<li><label for="title">Title:</label>
							<input type="text" name="title" id="title" value="" class="txt"/></li>
						<li><label for="description">Description:</label>
							<input type="text" name="description" id="description" value="" class="txt"/></li>
						<li><label for="url_installation">Installation URL</label>
							<input type="text" name="url_installation" id="url_installation" value="" class="txt"/><?php echo mostrarerror($errors,$errors_d,"5")?></li>
						<li><label for="posts_limit">Post limit:</label>
							<input type="text" name="posts_limit" id="posts_limit" value="10" class="txt"/></li>
						<li><label for="lang">Language:</label>
							<select id="lang" name="lang">
								<option value="en" selected="selected">english</option>
							</select>
						</li>
						<li><label for="template">Template:</label>
							<select id="template" name="template">
<?php
							$themes = getThemes();
							foreach ($themes as $theme) {									
								echo "<option value=\"".$theme."\" selected=\"true\">".$theme."</option>\n";
								
							}
?>
							</select>
						</li>
					</ul>
				</fieldset>
				<p>	
					<input type="hidden" name="website" id="website" value="" />
					<input type="hidden" name="about" id="about" value="" />
					<input type="hidden" name="url_friendly" id="url_friendly" value="0" />
					<input type="hidden" name="rich_text" id="rich_text" value="0" />		
					<input type="hidden" name="action" id="action" value="config" />
					<input type="submit" name="btnsubmit" id="btnsubmit" value="<< Install >>" class="submit"/>
				</p>
			</form>		
		</div>
		<div class="footer-box">&nbsp;</div>
	</div>
	
<?php
	} else {
		echo "<p><em>Finished!</em></p>";
		echo "<p>Now you can <a href=\"login.php\" class=\"inslnl\">log in</a> with your <strong>username</strong> and <strong>password</strong></p>";
	}

?>
	</div>
	<div id="foot">
		<a href="http://www.gelatocms.com/" title="gelato CMS">gelato CMS</a> :: PHP/MySQL Tumblelog Content Management System.
	</div>
	
</div>
</body>
</html>

<?php
function installdb($login, $password, $email, $title, $description, $url_installation, $posts_limit, $lang, $template, $website, $about, $url_friendly, $rich_text){

		$db = new Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);		
		
		$sqlStr = "CREATE TABLE `".Table_prefix."data` (
			  `id_post` int(11) NOT NULL auto_increment,
			  `title` text NULL,
			  `url` varchar(250)  default NULL,
			  `description` text NULL,
			  `type` tinyint(4) NOT NULL default '1',
			  `date` datetime NOT NULL,
			  `id_user` int(10) NOT NULL,
			  PRIMARY KEY  (`id_post`)
			) ENGINE = MYISAM ;";
		
		$db->ejecutarConsulta($sqlStr);
		
		$sqlStr = "CREATE TABLE `".Table_prefix."users` (
			  `id_user` int(10) unsigned NOT NULL auto_increment,
			  `name` varchar(100) default NULL,
			  `login` varchar(100) NOT NULL default '',
			  `password` varchar(64) NOT NULL default '',
			  `email` varchar(100) default NULL,
			  `website` varchar(150) default NULL,
			  `about` text,
			  PRIMARY KEY  (`id_user`)
			) ENGINE = MYISAM;";
		
		$db->ejecutarConsulta($sqlStr);
			
		$sqlStr = "CREATE TABLE `".Table_prefix."config` (
			  `posts_limit` int(3) NOT NULL,
			  `title` varchar(250) NOT NULL,
			  `description` text NOT NULL,
			  `lang` varchar(10) NOT NULL,
			  `template` varchar(100) NOT NULL,
			  `url_installation` varchar(250) NOT NULL,
			  `url_friendly` tinyint(1) NOT NULL,
			  `rich_text` tinyint(1) NOT NULL,
			  PRIMARY KEY  (`title`)
			) ENGINE = MYISAM ;";
			
		$db->ejecutarConsulta($sqlStr);
				
		$url_installation = (endsWith($url_installation, "/")) ? substr($url_installation, 0, strlen($url_installation)-1) : $url_installation ;
		
		$sqlStr = "INSERT INTO `".Table_prefix."config` VALUES (".$posts_limit.", '".$title."', '".$description."', '".$lang."', '".$template."', '".$url_installation."', ".$url_friendly.", ".$rich_text.");";		
			
		$db->ejecutarConsulta($sqlStr);
		
		$sqlStr = "INSERT INTO `".Table_prefix."users` VALUES ('', '', '".$login."', '".md5($password)."', '".$email."', '".$website."', '".$about."');";
			
		$db->ejecutarConsulta($sqlStr);

		return true;
}

function inerrors($errors,$n) {
	if (strpos($errors,$n)===false)
		return false;
	else
		return true;
}

function mostrarerror($errors,$errors_d,$n) {
	if (inerrors($errors,$n))
		return '<span class="error">'.$errors_d[$n].'</span>';
	else
		return "";
}
?>
