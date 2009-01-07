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

$dates = array();
$rs = $tumble->getPosts($tumble->getPostsNumber());
if ($db->contarRegistros()>0) {
    while($register = mysql_fetch_assoc($rs)) {
		$date = strtotime($register['date']);
		$year = date('Y',$date);
		$month = date('M',$date);
		$day = date('d',$date);
		$dates[$year][$month][$day] = true;
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

		$date = strtotime($node['date']);
		$year = date('Y',$date);
		$month = date('M',$date);
		$day = date('d',$date);
		
 		$output['full_date'] = $day.' '.$month.' '.$year;
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
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/jquery.scrollTo-min.js"></script>
		<link href="<?php echo $conf->urlGelato;?>/admin/css/archive.css" type="text/css" rel="stylesheet">		
		<script type='text/javascript'>
			function select(object) {
				var sel_id = object.id;
				$("a.option").removeClass('selected');
				$(object).addClass('selected');
				$("#conversation_content, #quote_content, #link_content, #regular_content, #photo_content, #video_content").slideUp("fast");
				$(".en, .es, .wn, .ws").remove();
			 	$("li.selected").removeClass('selected');
				$("#"+sel_id+"_content").show();
				$('#user_hover').hide();
				
			};
			
			$(function(){
				$("#timeline li").hover(function(){
					$(this).css('cursor','pointer');
					$("#bubble").hide();
					$(this).queue('fx',[]);
					$(this).animate({width:80},300, function(){
						$("#bubble").text($(this).find('span').text()).fadeIn('fast').css({top:this.offsetTop - 2});
						});
				},function(){
					$(this).css('cursor','auto');
					$(this).animate({width:$(this).attr("rel")},300,'linear');
				}).click(function(){
					$(".en, .es, .wn, .ws").remove();
					var date = $(this).text();
					$("#content > div:visible ul").find('li').removeClass('selected');
					$("#content > div:visible ul").find('li div[rel$='+date+']').parent().addClass('selected');
					wrap();
					if($('li.selected').length > 0){
						var elem = $("li.selected").get();
						$(window).scrollTo($("li.selected").eq(0).offset().top-50,900);
					}else{
						$("#bubble").text('Not found');
					}
				});
				
				$('#user_hover').hover(function(){},function(){ $(this).hide(); });
				
				$('.item').hover(function(e){
					var item = $(this);
					$('#user_hover').css({top:$(this).offset().top,left:$(this).offset().left}).show().unbind('click').click(function(){
						$(item).click();
					});
					$('#user_hover h3').text($(this).attr('rel'));
				},function(e){});
			});
			
			function wrap () {
					var elems = $("li.selected");
					var list = $("#content > div:visible ul.item_list > li");
					var cols = 4;
					var l = $(elems).length;
					$(elems).each(function(){
						index = $(list).index(this);
						if( (index+1) % cols == 0){
							$(this).append("<div class='crnr en'>&nbsp;</div>").append("<div class='crnr es'>&nbsp;</div>");
						}else if( (index) % cols == 0){
							$(this).append("<div class='crnr wn'>&nbsp;</div>").append("<div class='crnr ws'>&nbsp;</div>");
						}
					});
					$(elems).eq(0).append("<div class='crnr wn'>&nbsp;</div>").append("<div class='crnr ws'>&nbsp;</div>");
					$(elems).eq(l-1).append("<div class='crnr en'>&nbsp;</div>").append("<div class='crnr es'>&nbsp;</div>");
			 }
			
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
		<div id="timeline">
			<div id="timewrap">
				<div id="bubble"></div>
				<h4>TIMELINE</h4>
				<ul>
					<?php
					
					foreach ($dates as $year => $monthday) {
						echo '<li class="year" rel="60"><span>'.$year.'</span></li>';
						if(is_array($monthday) && count($monthday) > 0){
							
							foreach ($monthday as $month => $days) {
								echo '<li class="month" rel="40"><span>'.$month.' '.$year.'</span></li>';
								if(is_array($days) && count($days) > 0){
									foreach ($days as $day => $val) {
										echo '<li class="day" rel="20"><span>'.$day.' '.$month.' '.$year.'</span></li>';
									}
								}
							}
						}
					}
					?>
				</ul>
			</div>		
		</div>
					

			
			
			<div id='content'>
				<div id='msg' style="display:none;">&nbsp;</div>
				
				<div id='conversation_content'>
					<ul class='item_list'>
					<?php foreach($conversation as $item) { ?>
						<li>
							<div class='item' rel='<?php echo $item['full_date'] ?>' title="<?php echo strftime("%b %d, %G", strtotime($item['date'])); ?>" onclick="location.href='<?php echo $item['url'] ?>';">
								<ul>
									<li><?php echo $item['lines']; ?></li>
								</ul>
							</div>
						</li>
					<?php } ?>
					</ul>
				</div>
				
				<div id='quote_content' style='display:none;'>
					<ul class='item_list'>
					<?php foreach($quote as $item) { ?>
						<li>
							<div class='item' rel='<?php echo $item['full_date'] ?>' title="<?php echo strftime("%b %d, %G", strtotime($item['date'])); ?>" onclick="location.href='<?php echo $item['url'] ?>';">
								<ul>
									<li><em>"<?php echo substr($item['quote'], 0, 50), "..."; ?>"</em></li>
									<li>--<?php echo $item['source']; ?></li>
								</ul>
							</div>
						</li>
					<?php } ?>
					</ul>
				</div>
				
				<div id='link_content' style='display:none;'>
					<ul class='item_list'>
					<?php foreach($link as $item) { ?>
						<li>
							<div class='item link' rel='<?php echo $item['full_date'] ?>' title='<?php echo strftime("%b %d, %G", strtotime($item['date'])); ?>' onclick="location.href='<?php echo $item['url'] ?>';">
								<ul>
									<li><a href="<?php echo $item['link']; ?>" ><?php echo str_replace('.','.<br/>',$item['text']); ?></a></li>
								</ul>
							</div>
						</li>
					<?php } ?>
					</ul>
				</div>
				
				<div id='photo_content' style='display:none;'>
					<ul class='item_list'>
						<?php foreach($photo as $item) { ?>
								<?php echo "<pre>";
								print_r($item);
								echo "</pre>"; ?>
						<li>
							<div class='item' style="background-image:url('<?php echo $item['photo']; ?>')"  rel='<?php echo $item['full_date'] ?>' title="<?php echo strftime("%b %d, %G", strtotime($item['date'])); ?>" onclick="location.href='<?php echo $item['url'] ?>';"></div>
						</li>
					<?php } ?>
					</ul>
				</div>
				
				<div id='regular_content' style='display:none;'>	
					<ul class='item_list' rel='<?php echo $item['full_date'] ?>'>
						<?php foreach($regular as $item) { ?>
						<li>
							<div class="item" title="<?php echo strftime("%b %d, %G", strtotime($item['date'])); ?>" onclick="location.href='<?php echo $item['url'] ?>';">
								<h4><?php echo $item['title']; ?></h4>
								<p><?php echo $item['body']; ?></p>
							</div>
						</li>
					<?php } ?>
					</ul>
				</div>
				
				<div id='video_content' style='display:none;'>
					<ul class='item_list' rel='<?php echo $item['full_date'] ?>'>
					<?php foreach($video as $item) { ?>
						<li>
							<div class='item' style='text-align:center;' title="<?php echo strftime("%b %d, %G", strtotime($item['date'])); ?>" onclick="location.href='<?php echo $item['url'] ?>';">
								<?php echo  $item['embed']; //$item['caption'] ?>
							</div>
						</li>
					<?php } ?>
					</ul>
				</div>
			<p style="clear:both">&nbsp;</p>
			</div>
			
			<br clear='both' />
			<div id='user_hover'><h3>&nbsp;</h3></div>

		</div>
		
	</body>

</html>
