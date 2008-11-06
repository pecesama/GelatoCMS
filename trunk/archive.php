<?php
if(!defined('entry'))define('entry', true);
 /* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */
?>
<?php
require('entry.php');
global $user, $tumble, $conf;

$quote = array();
$conversation = array();
$link = array();
$photo = array();
$regular = array();
$video = array();

$rs = $tumble->getPosts($tumble->getPostsNumber());
if ($db->contarRegistros()>0) {
    while($register = mysql_fetch_assoc($rs)) {
    	$output = handleNode($register);
    	
		$theType = util::type2Text($register["type"]);
		
    	switch($theType) {
			case 'quote':
				$quote[] = $output;
				continue;
			case 'conversation':
				$conversation[] = $output;
				continue;
			case 'url':
				$link[] = $output;
				continue;
			case 'photo':
				$photo[] = $output;
				continue;
			case 'post':
				$regular[] = $output;
				continue;
			case 'video':
				$video[] = $output;
				continue;
		}
    }
}

function handleNode($node) {
		global $user, $tumble, $conf;
		$dateTmp = null;
		$formatedDate = gmdate("M d", strtotime($node["date"]) + util::transform_offset($conf->offsetTime));
        
		$output = array();		
		$output['url'] = $tumble->getPermalink($node["id_post"]);
 		$output['date'] = $formatedDate;
 		
 		$theType = util::type2Text($node["type"]);
 		
		switch($theType) {
			case 'quote':
				$output['quote'] = $node["description"];
				$output['source'] = $node["title"];                                        		
				break;
			case 'conversation':				
				$output['lines'] = $tumble->formatConversation($node["description"]);
				break;
			case 'url':								
				$node["title"] = (empty($node["title"]))? $node["url"] : $node["title"];				
				$output['text'] = $node["title"];
				$output['link'] = $node["url"];
				break;
			case 'photo':				
				$fileName = "uploads/".util::getFileName($node["url"]);
				$x = @getimagesize($fileName);
                if ($x[0] > 500) {
					$photoPath = $conf->urlGelato."/classes/imgsize.php?w=500&img=".$node["url"];
                } else {
					$photoPath = str_replace("../", $conf->urlGelato."/", $node["url"]);
               	}				
				$output['caption'] = $node["description"];
				$output['photo'] = $photoPath;
				break;
			case 'post':				
				$output['title'] = $node["title"];
				$output['body'] = $node["description"];
				break;
			case 'video':				
				$output['caption'] = $node["description"];
				$temp = $tumble->getVideoPlayer($node["url"]);
				$patterns[0] = "/width='[0-9]+'/";
				$patterns[1] = "/height='[0-9]+'/";
				$replace[0] = "width='100'"; 
				$replace[1] = "height='75'";
				$embed = preg_replace($patterns, $replace, $temp);
				$output['embed'] = $embed;
		}
		return $output;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="generator" content="gelato <?php echo util::codeName()." (".util::version().")"; ?>" />
        <link rel="shortcut icon" href="<?php echo $conf->urlGelato;?>/images/favicon.ico" />
        <link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo $conf->urlGelato.($conf->urlFriendly ? "/rss/" : "/rss.php"); ?>"/>
		<title><?php echo $conf->title." &raquo; ".__(" archive"); ?></title>
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/jquery.js"></script>
		<link href="<?php echo $conf->urlGelato;?>/admin/css/archive.css" type="text/css" rel="stylesheet">
		<script type='text/javascript'>
			function select(object) {
				var sel_id = object.id;
				$("a.option").removeClass('selected');
				$(object).addClass('selected');
				$("#conversation_content, #quote_content, #link_content, #regular_content, #photo_content, #video_content").hide("slow");
				$("#"+sel_id+"_content").show("slow");
			};
		</script>
	</head>
	
	<body>
		
		<div id='dash'>
					
			<div id='options'>
				<ul id='option-list'>
					<li><a href='#' id='conversation' class='option selected' onclick="select(this);">Conversations</a></li>
					<li><a href='#' id='photo' class='option' onclick="select(this);">Photos</a></li>
					<li><a href='#' id='link' class='option' onclick="select(this);">Links</a></li>
					<li><a href='#' id='regular' class='option' onclick="select(this);">Posts</a></li>
					<li><a href='#' id='quote' class='option' onclick="select(this);">Quotes</a></li>
					<li><a href='#' id='video' class='option' onclick="select(this);">Videos</a></li>
				</ul>
			</div>
			
			<div id='content'>
				<div id='conversation_content'>
					<?php foreach($conversation as $item) { ?>
					<ul class='item_list'>
						<li>
							<div class='item'>
								<ul>									
                                    <li><?php echo $item['lines']; ?></li>
								</ul>
							</div>
							<div class='user_hover' onclick="location.href='<?php echo $item['url'] ?>';">
								<h3><?php echo strftime("%b %d, %G", strtotime($item['date'])); ?></h3>
							</div>
						</li>
					</ul>
					<?php } ?>
				</div>
				
				<div id='quote_content' style='display:none;'>
					<?php foreach($quote as $item) { ?>
					<ul class='item_list'>
						<li>
							<div class='item'>
								<ul>
									<li><em>"<?php echo substr($item['quote'], 0, 50), "..."; ?>"</em></li>
									<li>--<?php echo $item['source']; ?></li>
								</ul>
							</div>
							<div class='user_hover' onclick="location.href='<?php echo $item['url'] ?>';">
								<h3><?php echo strftime("%b %d, %G", strtotime($item['date'])); ?></h3>
							</div>
						</li>
					</ul>
					<?php } ?>
				</div>
				
				<div id='link_content' style='display:none;'>
					<?php foreach($link as $item) { ?>
					<ul class='item_list'>
						<li>
							<div class='item link'>
								<ul>
									<li><a href="<?php echo $item['link']; ?>"><?php echo $item['text']; ?></a></li>
								</ul>
							</div>
							<div class='user_hover' onclick="location.href='<?php echo $item['url'] ?>';">
								<h3><?php echo strftime("%b %d, %G", strtotime($item['date'])); ?></h3>
							</div>
						</li>
					</ul>
					<?php } ?>
				</div>
				
				<div id='photo_content' style='display:none;'>
					<?php foreach($photo as $item) { ?>
					<ul class='item_list'>
						<li>
							<div class='item' style="background-image:url('<?php echo $item['photo']; ?>')"></div>
							<div class='user_hover' onclick="location.href='<?php echo $item['url'] ?>';">
								<h3><?php echo strftime("%b %d, %G", strtotime($item['date'])); ?></h3>
							</div>
						</li>
					</ul>
					<?php } ?>
				</div>
				
				<div id='regular_content' style='display:none;'>	
					<?php foreach($regular as $item) { ?>
					<ul class='item_list'>
						<li>
							<div class='item'>
								<h4><?php echo $item['title']; ?></h4>
								<p><?php echo $item['body']; ?></p>
							</div>
							<div class='user_hover' onclick="location.href='<?php echo $item['url'] ?>';">
								<h3><?php echo strftime("%b %d, %G", strtotime($item['date'])); ?></h3>
							</div>
						</li>
					</ul>
					<?php } ?>
				</div>
				
				<div id='video_content' style='display:none;'>
					<?php foreach($video as $item) { ?>
					<ul class='item_list'>
						<li>
							<div class='item' style='text-align:center;'>
								<?php echo  $item['embed']; //$item['caption'] ?>								
							</div>
							<div class='user_hover' onclick="location.href='<?php echo $item['url'] ?>';">
								<h3><?php echo strftime("%b %d, %G", strtotime($item['date'])); ?></h3>
							</div>
						</li>
					</ul>
					<?php } ?>
				</div>

			</div>
			
			<br clear='both' />
			
		</div>
		
	</body>

</html>