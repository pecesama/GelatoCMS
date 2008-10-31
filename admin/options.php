<?php
if(!defined('entry')) define('entry',true);  
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
global $user, $conf, $tumble;

if ($user->isAdmin()) {
	
	if(isset($_POST["btnsubmit"]))	{
		if (!$tumble->saveOption($_POST["rich_text"], "rich_text")) {
			header("Location: ".$conf->urlGelato."/admin/options.php?error=1&des=".$conf->merror);
			die();
		}
		if (!$tumble->saveOption($_POST["url_friendly"], "url_friendly")) {
			header("Location: ".$conf->urlGelato."/admin/options.php?error=1&des=".$conf->merror);
			die();
		}
		
		$off_r= split("," , $_POST['time_offsets']);
		$_POST['offset_time'] = $off_r[0];
		$_POST['offset_city'] = $off_r[1];
		unset($_POST['time_offsets']);
		if (!$tumble->saveOption($_POST["offset_city"], "offset_city")) {
			header("Location: ".$conf->urlGelato."/admin/options.php?error=1&des=".$conf->merror);
			die();
		}
		if (!$tumble->saveOption($_POST["offset_time"], "offset_time")) {
			header("Location: ".$conf->urlGelato."/admin/options.php?error=1&des=".$conf->merror);
			die();
		}
		
		if (!$tumble->saveOption($_POST["allow_comments"], "allow_comments")) {
			header("Location: ".$conf->urlGelato."/admin/options.php?error=1&des=".$conf->merror);
			die();
		}
		
		if (!$tumble->saveOption($_POST["shorten_links"], "shorten_links")) {
			header("Location: ".$conf->urlGelato."/admin/options.php?error=1&des=".$conf->merror);
			die();
		}
		
		if (!$tumble->saveOption($_POST["rss_import_frec"], "rss_import_frec")) {
			header("Location: ".$conf->urlGelato."/admin/options.php?error=1&des=".$conf->merror);
			die();
		}
		
		if (!$tumble->saveOption($_POST["check_version"], "check_version")) {
			header("Location: ".$conf->urlGelato."/admin/options.php?error=1&des=".$conf->merror);
			die();
		}

		header("Location: ".$conf->urlGelato."/admin/options.php?modified=true");
		die();
	} else {
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>gelato :: <?php echo __("options")?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="gelato cms <?php echo version();?>" />
		<link rel="shortcut icon" href="<?php echo $conf->urlGelato;?>/images/favicon.ico" />
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/jquery.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){
			$("#divMessages").fadeOut(5000,function(){
				$("#divMessages").css({display:"none"});
			});
		});
		</script>		
		<style type="text/css" media="screen">	
			@import "<?php echo $conf->urlGelato;?>/admin/css/style.css";
		</style>
	</head>
	
	<body>
		<div id="div-process" style="display:none;"><?php echo __("Processing request&hellip;")?></div>
		<div id="cont">
			<div id="head">
				<h1><a href="<?php echo $conf->urlGelato;?>/admin/index.php" title="gelato :: <?php echo __("home")?>">gelato cms</a></h1>
				<ul id="nav">
					<li><a href="<?php echo $conf->urlGelato;?>/" title="<?php echo __("Take me to the tumblelog")?>"><?php echo __("Back to the Tumblelog")?></a></li>
			  	</ul>
			</div>
			<div id="main">				
				
				<div class="box">
					<ul class="menu manage">
					<h3><?php echo __("Tumblelog options")?></h3>
					<li><a href="index.php"><?php echo __("Post")?></a></li>
					<li><a href="admin.php"><?php echo __("Users")?></a></li>
					<li><a href="settings.php"><?php echo __("Settings")?></a></li>
					<li class="selected"><a><?php echo __("Options")?></a></li>
					</ul>
<?php
					if (isset($_GET["modified"])) {
						if ($_GET["modified"]=="true") {
							echo "<div class=\"exito\" id=\"divMessages\">".__("The configuration has been modified successfully.")."</div>";
						}
					}					
					if (isset($_GET["error"])) {
						if ($_GET["error"]==1) {
							echo "<div class=\"error\" id=\"divMessages\"><strong>".__("Error on the database server: ")."</strong>".$_GET["des"]."</div>";
						}
					}
?>
					<div class="tabla">

						<form action="options.php" method="post" id="options_form" autocomplete="off" class="newpost">							
							<fieldset>								
								<ul>																	
									<li class="select"><label for="rich_text"><?php echo __("Rich text editor:")?></label>
										<select name="rich_text" id="rich_text">
											<option value="1" <?php if($conf->richText) echo "selected"; ?>><?php echo __("Active")?></option>
											<option value="0" <?php if(!$conf->richText) echo "selected"; ?>><?php echo __("Deactive")?></option>
										</select>
									</li>
									<li class="select"><label for="url_friendly"><?php echo __("URL friendly:")?></label>
										<select name="url_friendly" id="url_friendly">
											<option value="1" <?php if($conf->urlFriendly) echo "selected"; ?>><?php echo __("Active")?></option>
											<option value="0" <?php if(!$conf->urlFriendly) echo "selected"; ?>><?php echo __("Deactive")?></option>
										</select>
									</li>
									<li class="select"><label for="allow_comments"><?php echo __("Allow readers comments:")?></label>
										<select name="allow_comments" id="allow_comments">
											<option value="1" <?php if($conf->allowComments) echo "selected"; ?>><?php echo __("Active")?></option>
											<option value="0" <?php if(!$conf->allowComments) echo "selected"; ?>><?php echo __("Deactive")?></option>
										</select>
									</li>
									<li class="select"><label for="time_offsets"><?php echo __("Time Offset:")?></label>
										<select id="time_offsets" name="time_offsets">
											<option value="-12,Pacific/Kwajalein" <?php echo ($conf->offsetCity=="Pacific/Kwajalein")? "selected=\"selected\"":"" ?>>(GMT -12:00) International Date Line West</option>
											<option value="-11,Pacific/Samoa" <?php echo ($conf->offsetCity=="Pacific/Samoa")? "selected=\"selected\"":"" ?>>(GMT -11:00) Midway Island, Samoa</option>
											<option value="-10,Pacific/Honolulu" <?php echo ($conf->offsetCity=="Pacific/Honolulu")? "selected=\"selected\"":"" ?>>(GMT -10:00) Hawaii</option>
											<option value="-9,US/Alaska" <?php echo ($conf->offsetCity=="US/Alaska")? "selected=\"selected\"":"" ?>>(GMT -9:00) Alaska</option>
											<option value="-8,US/Pacific" <?php echo ($conf->offsetCity=="US/Pacific")? "selected=\"selected\"":"" ?>>(GMT -8:00) Pacific Time (US &amp; Canada); Tijuana</option>
											<option value="-7,US/Mountain" <?php echo ($conf->offsetCity=="US/Mountain")? "selected=\"selected\"":"" ?>>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
											<option value="-7,US/Arizona" <?php echo ($conf->offsetCity=="US/Arizona")? "selected=\"selected\"":"" ?>>(GMT -7:00) Arizona</option>
											<option value="-7,Mexico/BajaNorte" <?php echo ($conf->offsetCity=="Mexico/BajaNorte")? "selected=\"selected\"":"" ?>>(GMT -7:00) Chihuahua, La Paz, Mazatlan</option>
											<option value="-6,US/Central" <?php echo ($conf->offsetCity=="US/Central")? "selected=\"selected\"":"" ?>>(GMT -6:00) Central Time (US &amp; Canada)</option>
											<option value="-6,America/Costa_Rica" <?php echo ($conf->offsetCity=="America/Costa_Rica")? "selected=\"selected\"":"" ?>>(GMT -6:00) Central America</option>
											<option value="-6,Mexico/General" <?php echo ($conf->offsetCity=="Mexico/General")? "selected=\"selected\"":"" ?>>(GMT -6:00) Guadalajara, Mexico City, Monterrey</option>
											<option value="-6,Canada/Saskatchewan" <?php echo ($conf->offsetCity=="Canada/Saskatchewan")? "selected=\"selected\"":"" ?>>(GMT -6:00) Saskatchewan</option>
											<option value="-5,US/Eastern" <?php echo ($conf->offsetCity=="US/Eastern")? "selected=\"selected\"":"" ?>>(GMT -5:00) Eastern Time (US &amp; Canada)</option>
											<option value="-5,America/Bogota" <?php echo ($conf->offsetCity=="America/Bogota")? "selected=\"selected\"":"" ?>>(GMT -5:00) Bogota, Lima, Quito</option>
											<option value="-5,US/East-Indiana" <?php echo ($conf->offsetCity=="US/East-Indiana")? "selected=\"selected\"":"" ?>>(GMT -5:00) Indiana (East)</option>
											<option value="-4,Canada/Eastern" <?php echo ($conf->offsetCity=="Canada/Eastern")? "selected=\"selected\"":"" ?>>(GMT -4:00) Atlantic Time (Canada)</option>
											<option value="-4,America/Caracas" <?php echo ($conf->offsetCity=="America/Caracas")? "selected=\"selected\"":"" ?>>(GMT -4:00) Caracas, La Paz</option>
											<option value="-4,America/Santiago" <?php echo ($conf->offsetCity=="America/Santiago")? "selected=\"selected\"":"" ?>>(GMT -4:00) Santiago</option>
											<option value="-3.50,Canada/Newfoundland" <?php echo ($conf->offsetCity=="Canada/Newfoundland")? "selected=\"selected\"":"" ?>>(GMT -3:30) Newfoundland</option>
											<option value="-3,Canada/Atlantic" <?php echo ($conf->offsetCity=="Canada/Atlantic")? "selected=\"selected\"":"" ?>>(GMT -3:00) Brasilia, Greenland</option>
											<option value="-3,America/Buenos_Aires" <?php echo ($conf->offsetCity=="America/Buenos_Aires")? "selected=\"selected\"":"" ?>>(GMT -3:00) Buenos Aires, Georgetown</option>
											<option value="-2,Atlantic/Central" <?php echo ($conf->offsetCity=="Atlantic/Central")? "selected=\"selected\"":"" ?>>(GMT -2:00) Atlantic Central</option>
											<option value="-1,Atlantic/Cape_Verde" <?php echo ($conf->offsetCity=="Atlantic/Cape_Verde")? "selected=\"selected\"":"" ?>>(GMT -1:00) Cape Verde Is.</option>
											<option value="-1,Atlantic/Azores" <?php echo ($conf->offsetCity=="Atlantic/Azores")? "selected=\"selected\"":"" ?>>(GMT -1:00) Azores</option>
											<option value="0,Africa/Casablanca" <?php echo ($conf->offsetCity=="Africa/Casablanca")? "selected=\"selected\"":"" ?>>(GMT) Casablanca, Monrovia</option>
											<option value="0,Europe/Dublin" <?php echo ($conf->offsetCity=="Europe/Dublin")? "selected=\"selected\"":"" ?>>(GMT) Greenwich Mean Time : Dublin, Edinburgh, London</option>
											<option value="1,Europe/Amsterdam" <?php echo ($conf->offsetCity=="Europe/Amsterdam")? "selected=\"selected\"":"" ?>>(GMT +1:00) Amsterdam, Berlin, Rome, Stockholm, Vienna</option>
											<option value="1,Europe/Prague" <?php echo ($conf->offsetCity=="Europe/Pragu")? "selected=\"selected\"":"" ?>>(GMT +1:00) Belgrade, Bratislava, Budapest, Prague</option>
											<option value="1,Europe/Paris" <?php echo ($conf->offsetCity=="Europe/Paris")? "selected=\"selected\"":"" ?>>(GMT +1:00) Brussels, Copenhagen, Madrid, Paris</option>
											<option value="1,Europe/Warsaw" <?php echo ($conf->offsetCity=="Europe/Warsaw")? "selected=\"selected\"":"" ?>>(GMT +1:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
											<option value="1,Africa/Bangui" <?php echo ($conf->offsetCity=="Africa/Bangui")? "selected=\"selected\"":"" ?>>(GMT +1:00) West Central Africa</option>
											<option value="2,Europe/Istanbul" <?php echo ($conf->offsetCity=="Europe/Istanbul")? "selected=\"selected\"":"" ?>>(GMT +2:00) Athens, Beirut, Bucharest, Cairo, Istanbul	</option>
											<option value="2,Asia/Jerusalem" <?php echo ($conf->offsetCity=="Asia/Jerusalem")? "selected=\"selected\"":"" ?>>(GMT +2:00) Harare, Jerusalem, Pretoria</option>
											<option value="2,Europe/Kiev" <?php echo ($conf->offsetCity=="Europe/Kiev")? "selected=\"selected\"":"" ?>>(GMT +2:00) Helsinki, Kiev, Riga, Sofia, Tallinn, Vilnius</option>
											<option value="3,Asia/Riyadh" <?php echo ($conf->offsetCity=="Asia/Riyadh")? "selected=\"selected\"":"" ?>>(GMT +3:00) Kuwait, Nairobi, Riyadh</option>
											<option value="3,Europe/Moscow" <?php echo ($conf->offsetCity=="Europe/Moscow")? "selected=\"selected\"":"" ?>>(GMT +3:00) Baghdad, Moscow, St. Petersburg, Volgograd</option>
											<option value="3.50,Asia/Tehran" <?php echo ($conf->offsetCity=="Asia/Tehran")? "selected=\"selected\"":"" ?>>(GMT +3:30) Tehran</option>
											<option value="4,Asia/Muscat" <?php echo ($conf->offsetCity=="Asia/Muscat")? "selected=\"selected\"":"" ?>>(GMT +4:00) Abu Dhabi, Muscat</option>
											<option value="4,Asia/Baku" <?php echo ($conf->offsetCity=="Asia/Baku")? "selected=\"selected\"":"" ?>>(GMT +4:00) Baku, Tbilsi, Yerevan</option>
											<option value="4.50,Asia/Kabul" <?php echo ($conf->offsetCity=="Asia/Kabul")? "selected=\"selected\"":"" ?>>(GMT +4:30) Kabul</option>
											<option value="5,Asia/Yekaterinburg" <?php echo ($conf->offsetCity=="Asia/Yekaterinburg")? "selected=\"selected\"":"" ?>>(GMT +5:00) Yekaterinburg</option>
											<option value="5,Asia/Karachi" <?php echo ($conf->offsetCity=="Asia/Karachi")? "selected=\"selected\"":"" ?>>(GMT +5:00) Islamabad, Karachi, Tashkent</option>
											<option value="5.50,Asia/Calcutta" <?php echo ($conf->offsetCity=="Asia/Calcutta")? "selected=\"selected\"":"" ?>>(GMT +5:30) Chennai, Calcutta, Mumbai, New Delhi</option>
											<option value="5.75,Asia/Katmandu" <?php echo ($conf->offsetCity=="Asia/Katmandu")? "selected=\"selected\"":"" ?>>(GMT +5:45) Katmandu</option>
											<option value="6,Asia/Almaty" <?php echo ($conf->offsetCity=="Asia/Almaty")? "selected=\"selected\"":"" ?>>(GMT +6:00) Almaty, Novosibirsk</option>
											<option value="6,Asia/Dhaka" <?php echo ($conf->offsetCity=="Asia/Dhaka")? "selected=\"selected\"":"" ?>>(GMT +6:00) Astana, Dhaka, Sri Jayawardenepura</option>
											<option value="6.50,Asia/Rangoon" <?php echo ($conf->offsetCity=="Asia/Rangoo")? "selected=\"selected\"":"" ?>>(GMT +6:30) Rangoon</option>
											<option value="7,Asia/Bangkok" <?php echo ($conf->offsetCity=="Asia/Bangkok")? "selected=\"selected\"":"" ?>>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
											<option value="7,Asia/Krasnoyarsk" <?php echo ($conf->offsetCity=="Asia/Krasnoyarsk")? "selected=\"selected\"":"" ?>>(GMT +7:00) Krasnoyarsk</option>
											<option value="8,Asia/Hong_Kong" <?php echo ($conf->offsetCity=="Asia/Hong_Kong")? "selected=\"selected\"":"" ?>>(GMT +8:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
											<option value="8,Asia/Irkutsk" <?php echo ($conf->offsetCity=="Asia/Irkutsk")? "selected=\"selected\"":"" ?>>(GMT +8:00) Irkutsk, Ulaan Bataar</option>
											<option value="8,Asia/Singapore" <?php echo ($conf->offsetCity=="Asia/Singapore")? "selected=\"selected\"":"" ?>>(GMT +8:00) Kuala Lumpar, Perth, Singapore, Taipei</option>
											<option value="9,Asia/Tokyo" <?php echo ($conf->offsetCity=="Asia/Tokyo")? "selected=\"selected\"":"" ?>>(GMT +9:00) Osaka, Sapporo, Tokyo</option>
											<option value="9,Asia/Seoul" <?php echo ($conf->offsetCity=="Asia/Seoul")? "selected=\"selected\"":"" ?>>(GMT +9:00) Seoul</option>
											<option value="9,Asia/Yakutsk" <?php echo ($conf->offsetCity=="Asia/Yakutsk")? "selected=\"selected\"":"" ?>>(GMT +9:00) Yakutsk</option>
											<option value="9.50,Australia/Adelaide" <?php echo ($conf->offsetCity=="Australia/Adelaide")? "selected=\"selected\"":"" ?>>(GMT +9:30) Adelaide</option>
											<option value="9.50,Australia/Darwin" <?php echo ($conf->offsetCity=="Australia/Darwin")? "selected=\"selected\"":"" ?>>(GMT +9:30) Darwin</option>
											<option value="10,Australia/Brisbane" <?php echo ($conf->offsetCity=="Australia/Brisbane")? "selected=\"selected\"":"" ?>>(GMT +10:00) Brisbane, Guam, Port Moresby</option>
											<option value="10,Australia/Canberra" <?php echo ($conf->offsetCity=="Australia/Canberra")? "selected=\"selected\"":"" ?>>(GMT +10:00) Canberra,Melbourne, Sydney, Vladivostok</option>
											<option value="11,Asia/Magadan" <?php echo ($conf->offsetCity=="Asia/Magadan")? "selected=\"selected\"":"" ?>>(GMT +11:00) Magadan, Soloman Is., New Caledonia</option>
											<option value="12,Pacific/Auckland" <?php echo ($conf->offsetCity=="Pacific/Auckland")? "selected=\"selected\"":"" ?>>(GMT +12:00) Auckland, Wellington</option>
											<option value="12,Pacific/Fiji" <?php echo ($conf->offsetCity=="Pacific/Fiji")? "selected=\"selected\"":"" ?>>(GMT +12:00) Fiji, Kamchatka, Marshall Is.</option>
										</select>
									</li>
									<li class="select"><label for="shorten_links"><?php echo __("Shorten long URLs:")?></label>
										<select name="shorten_links" id="shorten_links">
											<option value="1" <?php if($conf->shorten_links) echo "selected=\"selected\""; ?>><?php echo __("Active")?></option>
											<option value="0" <?php if(!$conf->shorten_links) echo "selected=\"selected\""; ?>><?php echo __("Deactive")?></option>
										</select>
									</li>
                                    <li class="select"><label for="rss_import_frec"><?php echo __("Import feeds every:")?></label>
                                    	<select name="rss_import_frec" id="rss_import_frec">
                                        
                                        	<option value="5 minutes" <?php if($conf->rssImportFrec == '5 minutes') echo "selected=\"selected\""; ?>>5 <?php echo __("minutes");?></option>
                                        	<option value="10 minutes" <?php if($conf->rssImportFrec == '10 minutes') echo "selected=\"selected\""; ?>>10 <?php echo __("minutes");?></option>
                                            <option value="15 minutes" <?php if($conf->rssImportFrec == '15 minutes') echo "selected=\"selected\""; ?>>15 <?php echo __("minutes");?></option>
                                            <option value="30 minutes" <?php if($conf->rssImportFrec == '30 minutes') echo "selected=\"selected\""; ?>>30 <?php echo __("minutes");?></option>
                                            <option value="45 minutes" <?php if($conf->rssImportFrec == '45 minutes') echo "selected=\"selected\""; ?>>45 <?php echo __("minutes");?></option>
                                            <option value="1 hour" <?php if($conf->rssImportFrec == '1 hour') echo "selected=\"selected\""; ?>>1 <?php echo __("hour");?></option>
                                            <option value="2 hours" <?php if($conf->rssImportFrec == '2 hours') echo "selected=\"selected\""; ?>>2 <?php echo __("hours");?></option>
                                            <option value="3 hours" <?php if($conf->rssImportFrec == '3 hours') echo "selected=\"selected\""; ?>>3 <?php echo __("hours");?></option>
                                            <option value="4 hours" <?php if($conf->rssImportFrec == '4 hours') echo "selected=\"selected\""; ?>>4 <?php echo __("hours");?></option>
                                            <option value="6 hours" <?php if($conf->rssImportFrec == '6 hours') echo "selected=\"selected\""; ?>>6 <?php echo __("hours");?></option>
                                            <option value="12 hours" <?php if($conf->rssImportFrec == '12 hours') echo "selected=\"selected\""; ?>>12 <?php echo __("hours");?></option>
                                            <option value="1 day" <?php if($conf->rssImportFrec == '1 day') echo "selected=\"selected\""; ?>>24 <?php echo __("hours");?></option>
										</select>
									</li>
									<li class="select"><label for="check_version"><?php echo __("Check for updates:")?></label>
										<select name="check_version" id="check_version">
											<option value="1" <?php if($conf->check_version) echo "selected=\"selected\""; ?>><?php echo __("Active")?></option>
											<option value="0" <?php if(!$conf->check_version) echo "selected=\"selected\""; ?>><?php echo __("Deactive")?></option>
										</select>
									</li>
<?php	
									$trigger->call('add_options_panel');	
?>									
								</ul>
							</fieldset>
							<p>
								<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo __("Modify")?>" class="submit"/>
							</p>
						</form>	
								
					</div>

					<div class="footer-box">&nbsp;</div>
				</div>
			</div>
			<div id="foot">
				<a href="http://www.gelatocms.com/" title="gelato CMS">gelato CMS</a> :: PHP/MySQL Tumblelog Content Management System.
			</div>
		</div>
	</body>
	</html>
<?php
	}
} else {
	header("Location: ".$conf->urlGelato."/login.php");
}
?>