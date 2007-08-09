<?php
/* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */
?>
<?php
	header("Content-type: text/xml; charset=utf-8");	
	
	require(dirname(__FILE__)."/config.php");
	include("classes/configuration.class.php");
	include("classes/gelato.class.php");
	include("classes/textile.class.php");
	$isFeed = true;
	$tumble = new gelato();
	$conf = new configuration();
	
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
	<gelato version="1.0">
<?php
	
	if (isset($_GET["action"]) && $_GET["action"] == "read") {
		if (isset($_GET["start"])) { $start = $_GET["start"]; } else { $start = 0; }
		if (isset($_GET["num"])) { $num = $_GET["num"]; } else { $num = 20; }
		if (isset($_GET["type"])) { $type = $_GET["type"]; } else { $type = null; }
		if ($num > 50) { $num = 50; }		
?>		
		<tumblelog name="<?php echo $_SESSION["user_login"];?>" timezone="<?php echo $conf->offsetCity;?>" title="<?php echo $conf->title;?>"><?php echo $conf->description;?></tumblelog>	

<?php
		switch ($type) {
			case "post":
				$_GET["type"] = "1";
				break;
			case "photo":
				$_GET["type"] = "2";							   
				break;
			case "quote":
				$_GET["type"] = "3";
				break;
			case "url":
				$_GET["type"] = "4";
				break;
			case "conversation":
				$_GET["type"] = "5";
				break;
			case "video":
				$_GET["type"] = "6";
				break;
			case "mp3":
				$_GET["type"] = "7";
				break;								
		}
		$rs = $tumble->getPosts($num, $start);
		if ($tumble->contarRegistros()>0) {
?>
			<posts start="<?php echo $start; ?>" total="<?php echo $num; ?>">
<?php/*
			while($register = mysql_fetch_array($rs)) {
				
				$textile = new Textile();				
				$register["description"] = $textile->TextileThis($register["description"]);
				
				switch ($register["type"]) {
					case "1":
						$tit = ($register["title"]=="") ? strip_tags($register["description"]) : $register["title"];
						$desc = $register["description"];
						break;
					case "2":
						$tit = ($register["description"]=="") ? "Photo" : strip_tags($register["description"]);
						$desc = "<img src=\"".$register["url"]."\"/>";
						break;
					case "3":
						$tit = "\"".strip_tags($register["description"])."\"";
						$tmpStr = ($register["title"]!="") ? "<br /><br /> - <em>".$register["title"]."</em>" : "";
						$desc = "\"".$register["description"]."\"".$tmpStr;
						break;
					case "4":
						$tit = ($register["title"]=="") ? $register["url"] : $register["title"];
						$tmpStr = ($register["description"]!="") ? "<br /><br /> - <em>".$register["description"]."</em>" : "";
						$desc = "<a href=\"".$register["url"]."\">".$tit."</a>".$tmpStr;
						break;
					case "5":
						$lines = explode("\n", $register["description"]);
						$line = $lines[0];
						$tit = ($register["title"]=="") ? $line : $register["title"];
						$desc = $tumble->formatConversation($register["description"]);
						break;
					case "6":
						$tit = ($register["description"]=="") ? "Video" : strip_tags($register["description"]);
						$desc = $tumble->getVideoPlayer($register["url"]);
						break;
					case "7":
						$tit = ($register["description"]=="") ? "MP3" : strip_tags($register["description"]);
						$desc = $tumble->getMp3Player($register["url"]);
						break;
				}
				$url = $conf->urlGelato."/index.php/post/".$register["id_post"]."/";
				$formatedDate = gmdate("D, d M Y H:i:s \G\M\T", strtotime($register["date"]));
?>

				<item>
					<title><?php echo $tit;?></title>
					<description><![CDATA[<?php echo $desc;?>]]></description>
					<link><?php echo $url;?></link>
					<guid isPermaLink="true"><?php echo $conf->urlGelato."/index.php/post/".$register["id_post"]."/";?></guid>				
					<pubDate><?php echo $formatedDate;?></pubDate>				
				</item>

<?php	
			}		
*/?>
				</posts>
<?php	
		}
	}
?>
		</gelato>