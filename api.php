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
	header("Content-type: text/xml; charset=utf-8");	
	
	$isFeed = true;
	$tumble = new gelato();
	$conf = new configuration();
	
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
	<gelato version="1.0">
<?php
	
	if (isset($_GET["action"]) && $_GET["action"] == "read") {
		if (isset($_GET["start"])) { $start = $_GET["start"]; } else { $start = 0; }
		if (isset($_GET["total"])) { $total = $_GET["total"]; } else { $total = 20; }
		if (isset($_GET["type"])) { $hasType = true; } else { $hasType = false; }
		if ($total > 50) { $total = 50; }		
?>		
		<tumblelog name="<?php echo $_SESSION["user_login"];?>" timezone="<?php echo $conf->offsetCity;?>" title="<?php echo $conf->title;?>"><?php echo $conf->description;?></tumblelog>	

<?php
		switch ($hasType) {
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
		$rs = $tumble->getPosts($total, $start);
		if ($tumble->contarRegistros()>0) {
?>
			<posts start="<?php echo $start; ?>" total="<?php echo $total; ?>">
<?php 
			while($register = mysql_fetch_array($rs)) {
				$desc = $register["description"];
				$url = $conf->urlGelato."/index.php?post=".$register["id_post"];
				$formatedDate = gmdate("D, d M Y H:i:s", strtotime($register["date"])+transform_offset($conf->offsetTime));
				
				switch ($register["type"]) {
					case "1":

						$tit = ($register["title"]=="") ? $register["description"] : $register["title"];
?>
						
						<post id="<?php echo $register["id_post"]; ?>" url="<?php echo $url;?>" type="regular" date="<?php echo $formatedDate;?>">
							<regular-title><?php echo $tit;?></regular-title>
							<regular-body><?php echo $desc;?></regular-body>
						</post>
<?php						
						break;
					case "2":
						$tit = ($register["description"]=="") ? "Photo" : $register["description"];
?>
						<post id="<?php echo $register["id_post"]; ?>" url="<?php echo $url;?>" type="photo" date="<?php echo $formatedDate;?>">
<?php
							$photoPath = str_replace("../", $conf->urlGelato."/", $register["url"]);
?>
                            <photo-caption><?php echo $tit;?></photo-caption>
                            <photo-url><?php echo $photoPath;?></photo-url>                            
                        </post>
<?php
						break;
					case "3":						
?>
						<post id="<?php echo $register["id_post"]; ?>" url="<?php echo $url;?>" type="quote" date="<?php echo $formatedDate;?>">
							<quote-text><?php echo $desc; ?></quote-text>
							<quote-source><?php echo $register["title"]; ?></quote-source>
						</post>
<?php
						break;
					case "4":
						$tit = ($register["title"]=="") ? $register["url"] : $register["title"];
?>
						<post id="<?php echo $register["id_post"]; ?>" url="<?php echo $url;?>" type="link" date="<?php echo $formatedDate;?>">
                            <link-text><?php echo $tit; ?></link-text>
                            <link-url><?php echo $register["url"]; ?></link-url>
                        </post>
<?php
						break;
					case "5":
						$lines = explode("\n", $register["description"]);
						$line = $lines[0];
						$tit = ($register["title"]=="") ? $line : $register["title"];
						$desc = $tumble->formatConversation($register["description"]);
?>
						<post id="<?php echo $register["id_post"]; ?>" url="<?php echo $url;?>" type="conversation" date="<?php echo $formatedDate;?>">
                            <conversation-title><?php echo $tit; ?></conversation-title>
                            <conversation-text><?php echo $register["description"]; ?></conversation-text>
                            <?php echo $tumble->formatApiConversation($register["description"]); ?>
                        </post>
<?php
						break;
/*
					case "6":
						$tit = ($register["description"]=="") ? "Video" : $register["description"];
						$desc = $tumble->getVideoPlayer($register["url"]);
						break;
					case "7":
						$tit = ($register["description"]=="") ? "MP3" : $register["description"];
						$desc = $tumble->getMp3Player($register["url"]);
						break;
*/
				}
				$url = $conf->urlGelato."/index.php/post/".$register["id_post"]."/";
				$formatedDate = gmdate("D, d M Y H:i:s", strtotime($register["date"])+transform_offset($conf->offsetTime));
			}		
 
?>
				</posts>
<?php	
		}
	}
?>
		</gelato>