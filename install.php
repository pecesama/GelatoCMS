<?php
if(!defined('entry'))define('entry', true);
/* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */

$configFile = dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";

include('classes/functions.php');
include('classes/install.class.php');
 
$install = new Install(); 
$install->data = $_POST;
$install->check_form();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="generator" content="gelato cms <?php echo version();?>" />
	<title>gelato :: installation</title>	
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
	
<?php

	if ($install->showForm) {
?>
	
	<div class="box">
		<ul class="menu manage">
		<h3>gelato :: installation</h3>

		<li class="selected"><a>Install</a></li>
		</ul>
	
		<div class="tabla">
			<form action="install.php" method="post" id="config_form" autocomplete="off" class="newpost">
				<fieldset>
					<legend class="install">Database Settings</legend>
					<ul>
						<li><label for="login">User:</label>
							<input type="text" name="db_login" id="db_login" value="" class="txt"/><?php echo $install->mostrarerror("1")?></li>
						<li><label for="password">Password:</label>
							<input type="password" name="db_password" id="db_password" value="" class="txt"/><?php echo $install->mostrarerror("2")?></li>
						<li><label for="password2">Re-type password:</label>
							<input type="password" name="db_password2" id="db_password2" value="" class="txt"/><?php echo $install->mostrarerror("3")?></li>						
						<li><label for="email">Database Host:</label>
							<input type="text" name="db_host" id="db_host" value="localhost" class="txt"/><?php echo $install->mostrarerror("7")?></li>	
						<li><label for="email">Database Name:</label>
							<input type="text" name="db_name" id="db_name" value="gelatocms" class="txt"/><?php echo $install->mostrarerror("8")?></li>											
					</ul>
				</fieldset><br  />
				<fieldset>
					<legend class="install">Admin user</legend>
					<ul>
						<li><label for="login">User:</label>
							<input type="text" name="login" id="login" value="" class="txt"/><?php echo $install->mostrarerror("1")?></li>
						<li><label for="password">Password:</label>
							<input type="password" name="password" id="password" value="" class="txt"/><?php echo $install->mostrarerror("2")?></li>
						<li><label for="password2">Re-type password:</label>
							<input type="password" name="password2" id="password2" value="" class="txt"/><?php echo $install->mostrarerror("3")?></li>						
						<li><label for="email">E-mail:</label>
							<input type="text" name="email" id="email" value="" class="txt"/><?php echo $install->mostrarerror("4")?></li>						
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
							<input type="text" name="url_installation" id="url_installation" value="<?php if(isset($_SERVER['SCRIPT_URI']))echo substr($_SERVER["SCRIPT_URI"], '0', '-12'); ?>" class="txt"/><?php echo $install->mostrarerror("5")?></li>
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
?>							</select>
						<li>
						<li><label for="time_offsets">Time Offset:</label>
							<select id="time_offsets" name="time_offsets">
								<option value="-12,Pacific/Kwajalein">(GMT -12:00) International Date Line West</option>
								<option value="-11,Pacific/Samoa">(GMT -11:00) Midway Island, Samoa</option>
								<option value="-10,Pacific/Honolulu">(GMT -10:00) Hawaii</option>
								<option value="-9,US/Alaska">(GMT -9:00) Alaska</option>
								<option value="-8,US/Pacific">(GMT -8:00) Pacific Time (US &amp; Canada); Tijuana</option>
								<option value="-7,US/Mountain">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
								<option value="-7,US/Arizona">(GMT -7:00) Arizona</option>
								<option value="-7,Mexico/BajaNorte">(GMT -7:00) Chihuahua, La Paz, Mazatlan</option>
								<option value="-6,US/Central">(GMT -6:00) Central Time (US &amp; Canada)</option>
								<option value="-6,America/Costa_Rica">(GMT -6:00) Central America</option>
								<option value="-6,Mexico/General" selected="selected">(GMT -6:00) Guadalajara, Mexico City, Monterrey</option>
								<option value="-6,Canada/Saskatchewan">(GMT -6:00) Saskatchewan</option>
								<option value="-5,US/Eastern">(GMT -5:00) Eastern Time (US &amp; Canada)</option>
								<option value="-5,America/Bogota">(GMT -5:00) Bogota, Lima, Quito</option>
								<option value="-5,US/East-Indiana">(GMT -5:00) Indiana (East)</option>
								<option value="-4,Canada/Eastern">(GMT -4:00) Atlantic Time (Canada)</option>
								<option value="-4,America/Caracas">(GMT -4:00) Caracas, La Paz</option>
								<option value="-4,America/Santiago">(GMT -4:00) Santiago</option>
								<option value="-3.50,Canada/Newfoundland">(GMT -3:30) Newfoundland</option>
								<option value="-3,Canada/Atlantic">(GMT -3:00) Brasilia, Greenland</option>
								<option value="-3,America/Buenos_Aires">(GMT -3:00) Buenos Aires, Georgetown</option>
								<option value="-1,Atlantic/Cape_Verde">(GMT -1:00) Cape Verde Is.</option>
								<option value="-1,Atlantic/Azores">(GMT -1:00) Azores</option>
								<option value="0,Africa/Casablanca">(GMT) Casablanca, Monrovia</option>
								<option value="0,Europe/Dublin">(GMT) Greenwich Mean Time : Dublin, Edinburgh, London</option>
								<option value="1,Europe/Amsterdam">(GMT +1:00) Amsterdam, Berlin, Rome, Stockholm, Vienna</option>
								<option value="1,Europe/Prague">(GMT +1:00) Belgrade, Bratislava, Budapest, Prague</option>
								<option value="1,Europe/Paris">(GMT +1:00) Brussels, Copenhagen, Madrid, Paris</option>
								<option value="1,Europe/Warsaw">(GMT +1:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
								<option value="1,Africa/Bangui">(GMT +1:00) West Central Africa</option>
								<option value="2,Europe/Istanbul">(GMT +2:00) Athens, Beirut, Bucharest, Cairo, Istanbul	</option>
								<option value="2,Asia/Jerusalem">(GMT +2:00) Harare, Jerusalem, Pretoria</option>
								<option value="2,Europe/Kiev">(GMT +2:00) Helsinki, Kiev, Riga, Sofia, Tallinn, Vilnius</option>
								<option value="3,Asia/Riyadh">(GMT +3:00) Kuwait, Nairobi, Riyadh</option>
								<option value="3,Europe/Moscow">(GMT +3:00) Baghdad, Moscow, St. Petersburg, Volgograd</option>
								<option value="3.50,Asia/Tehran">(GMT +3:30) Tehran</option>
								<option value="4,Asia/Muscat">(GMT +4:00) Abu Dhabi, Muscat</option>
								<option value="4,Asia/Baku">(GMT +4:00) Baku, Tbilsi, Yerevan</option>
								<option value="4.50,Asia/Kabul">(GMT +4:30) Kabul</option>
								<option value="5,Asia/Yekaterinburg">(GMT +5:00) Yekaterinburg</option>
								<option value="5,Asia/Karachi">(GMT +5:00) Islamabad, Karachi, Tashkent</option>
								<option value="5.50,Asia/Calcutta">(GMT +5:30) Chennai, Calcutta, Mumbai, New Delhi</option>
								<option value="5.75,Asia/Katmandu">(GMT +5:45) Katmandu</option>
								<option value="6,Asia/Almaty">(GMT +6:00) Almaty, Novosibirsk</option>
								<option value="6,Asia/Dhaka">(GMT +6:00) Astana, Dhaka, Sri Jayawardenepura</option>
								<option value="6.50,Asia/Rangoon">(GMT +6:30) Rangoon</option>
								<option value="7,Asia/Bangkok">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
								<option value="7,Asia/Krasnoyarsk">(GMT +7:00) Krasnoyarsk</option>
								<option value="8,Asia/Hong_Kong">(GMT +8:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
								<option value="8,Asia/Irkutsk">(GMT +8:00) Irkutsk, Ulaan Bataar</option>
								<option value="8,Asia/Singapore">(GMT +8:00) Kuala Lumpar, Perth, Singapore, Taipei</option>
								<option value="9,Asia/Tokyo">(GMT +9:00) Osaka, Sapporo, Tokyo</option>
								<option value="9,Asia/Seoul">(GMT +9:00) Seoul</option>
								<option value="9,Asia/Yakutsk">(GMT +9:00) Yakutsk</option>
								<option value="9.50,Australia/Adelaide">(GMT +9:30) Adelaide</option>
								<option value="9.50Australia/Darwin">(GMT +9:30) Darwin</option>
								<option value="10,Australia/Brisbane">(GMT +10:00) Brisbane, Guam, Port Moresby</option>
								<option value="10,Australia/Canberra">(GMT +10:00) Canberra, Hobart, Melbourne, Sydney, Vladivostok</option>
								<option value="11,Asia/Magadan">(GMT +11:00) Magadan, Soloman Is., New Caledonia</option>
								<option value="12,Pacific/Auckland">(GMT +12:00) Auckland, Wellington</option>
								<option value="12,Pacific/Fiji">(GMT +12:00) Fiji, Kamchatka, Marshall Is.</option>
							</select>
						</li>
					</ul>
				</fieldset>
				<p>	
					<input type="hidden" name="website" id="website" value="" />
					<input type="hidden" name="about" id="about" value="" />
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
