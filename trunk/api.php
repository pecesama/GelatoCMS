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
	
	require('entry.php');
	global $user, $conf, $tumble;
	$f = new feeds();
	
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
		<tumblelog name="<?php echo $_SESSION["user_login"];?>" timezone="<?php echo $conf->offsetCity;?>" title="<?php echo $conf->title;?>"><?php 	
		echo "\n\t".$conf->description."\n";
?>
			<feeds>
<?php
				$actual_feeds = $f->getFeedList();
				foreach($actual_feeds as $feed){
					$error_text = ($feed["error"]>0) ? "false" : "true";
?>
					<feed id="<?php echo $feed["id_feed"];?>" url="<?php echo htmlspecialchars($feed["url"]);?>" import-type="<?php echo type2Text($feed["type"]);?>" next-update-in-seconds="<? echo $f->getNextUpdate($feed["id_feed"]);?>" title="<?php echo htmlspecialchars($feed["title"]);?>" error-text="<? echo $error_text;?>"/>
<?php
				}
?>
            </feeds>
        </tumblelog>	

<?php
		if ($hasType) {
			$postType = type2Number($_GET["type"]);
		}
		$rs = $tumble->getPosts($total, $start);
		if ($tumble->contarRegistros()>0) {
?>
			<posts start="<?php echo $start; ?>" total="<?php echo $total; ?>">
<?php 
			while($register = mysql_fetch_array($rs)) {
				$desc = htmlspecialchars($register["description"]);
				$url = $conf->urlGelato."/index.php?post=".$register["id_post"];
				$formatedDate = gmdate("D, d M Y H:i:s", strtotime($register["date"])+transform_offset($conf->offsetTime));
				
				switch ($register["type"]) {
					case "1":

						$tit = ($register["title"]=="") ? $desc : $register["title"];
?>
						
						<post id="<?php echo $register["id_post"]; ?>" url="<?php echo $url;?>" type="regular" date="<?php echo $formatedDate;?>">
							<regular-title><?php echo $tit;?></regular-title>
							<regular-body><?php echo $desc;?></regular-body>
						</post>
<?php						
						break;
					case "2":
						$tit = ($register["description"]=="") ? "Photo" : $desc;
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
						$lines = explode("\n", $desc);
						$line = $lines[0];
						$tit = ($register["title"]=="") ? $line : $register["title"];
						$desc = $tumble->formatConversation($desc);
?>
						<post id="<?php echo $register["id_post"]; ?>" url="<?php echo $url;?>" type="conversation" date="<?php echo $formatedDate;?>">
                            <conversation-title><?php echo $tit; ?></conversation-title>
                            <conversation-text><?php echo $desc; ?></conversation-text>
                            <?php echo $tumble->formatApiConversation($desc); ?>
                        </post>
<?php
						break;
					case "6":
						$tit = ($register["description"]=="") ? "Video" : $desc;
						$desc = $tumble->getVideoPlayer($register["url"]);
?>
						<post id="<?php echo $register["id_post"]; ?>" url="<?php echo $url;?>" type="video" date="<?php echo $formatedDate;?>">
                            <video-caption><?php echo $tit; ?></video-caption>
                            <video-source><?php echo $register["url"]; ?></video-source>
                            <video-player><?php echo htmlspecialchars($desc); ?></video-player>                            
                        </post>
<?php
						break;

					case "7":
						$tit = ($register["description"]=="") ? "Audio" : $desc;
						$desc = $tumble->getMp3Player($register["url"]);
?>
						<post id="<?php echo $register["id_post"]; ?>" url="<?php echo $url;?>" type="audio" date="<?php echo $formatedDate;?>">
                            <audio-caption><?php echo $tit; ?></audio-caption>
                            <audio-player><?php echo htmlspecialchars($desc); ?></audio-player>                            
                        </post>
<?php
						break;

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