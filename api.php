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
	$isFeed = true;
	
	require('entry.php');
	global $user, $conf, $tumble;
	$f = new feeds();
	
	$theme = new themes;

	if (isset($_GET["action"]) && $_GET["action"] == "read") {
		if (isset($_GET["start"])) { $start = $_GET["start"]; } else { $start = 0; }
		if (isset($_GET["total"])) { $total = $_GET["total"]; } else { $total = 20; }
		if (isset($_GET["type"])) { $hasType = true; } else { $hasType = false; }
		if ($total > 50) { $total = 50; }
		$user = new user();
		$userData = $user->getUserByID(1);
		$username = ($userData["name"] == "") ? "gelato" : $userData["name"];

		$theme->set("username",$username);
		$theme->set("conf",array(
			"offsetCity"=>$conf->offsetCity,
			"title"=>$conf->title,
			"description"=>$conf->description
		));

		$feeds = array();		$actual_feeds = $f->getFeedList();
		foreach($actual_feeds as $feed){
			$error_text = ($feed["error"]>0) ? "false" : "true";
			$feed['url'] = htmlspecialchars($feed['url']);
			$feed['type'] = util::type2Text($feed['type']);
			$feed['getNextUpdate'] = $f->getNextUpdate($feed['id_feed']);
			$feed['title'] = htmlspecialchars($feed['title']);
			$feed['error_text'] = $error_text;
			$feeds[] = $feed;
		}
		
		$theme->set("feeds",$feeds);

		if ($hasType) {
			$postType = type2Number($_GET["type"]);
		}
		$rs = $tumble->getPosts($total, $start);
		if ($db->contarRegistros()>0) {
			$theme->set("start",$start);
			$theme->set("total",$total);

			while($post = mysql_fetch_assoc($rs)){
				$post['desc'] = util::trimString($post["description"]);				
				$strEnd = ($conf->urlFriendly) ? "/" : "";
				$post['url'] = $conf->urlGelato.($conf->urlFriendly ? "/post/" : "/index.php?post=").$post["id_post"].$strEnd;
				$post['formatedDate'] = gmdate("D, d M Y H:i:s", strtotime($post["date"]) + util::transform_offset($conf->offsetTime));
				
				$post['type'] = util::type2Text($post["type"]);

				switch ($post["type"]) {
					case "post":
						$post['tit'] = (empty($post["title"])) ? $desc : strip_tags($post["title"]);
						break;
					case "photo":
						$post['photoPath'] = str_replace("../", $conf->urlGelato."/", $post["url"]);
						$post['tit'] = stripslashes(((empty($post["description"])) ? "Photo" : $post['desc']));
						break;
					case "quote":
						$post['title'] = strip_tags($post["title"]);
						break;
					case "url":
						$post['tit'] = (empty($post["title"])) ? $post["url"] : strip_tags($post["title"]);
						break;
					case "conversation":
						$lines = explode("\n", $desc);
						$line = $lines[0];
						$post['tit'] = (empty($post["title"])) ? trimString($line) : $post["title"];
						$post['desc'] = $tumble->formatConversation($desc);
						$post['descAPIFormat'] = $tumble->formatConversation($desc);
						break;
					case "video":
						$post['tit'] = (empty($post["description"])) ? "Video" : $desc;
						$post['desc'] = htmlspecialchars($tumble->getVideoPlayer($post["url"]));
						break;
					case "mp3":
						$post['tit'] = (empty($post["description"])) ? "Audio" : $desc;
						$desc = htmlspecialchars($tumble->getMp3Player($post["url"]));
						break;
				}				
				$posts[] = $post;
			}
			$theme->set("posts",$posts);
		}
	}

$theme->display(Absolute_Path.'admin/themes/admin/api.xml');
?>
