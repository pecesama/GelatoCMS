<?php
if(!defined('entry')) define('entry', true);
 /* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */
?>
<?php
require('../entry.php');
global $user, $conf, $tumble;
$template = new plantillas("admin");


$user= new User();
if ($user->isAdmin()) {
	$message = '';
	$f = new feeds();
	if(isset($_POST['add'], $_POST['url'])){
		 $credits =(isset($_POST['credits']))? 1 : 0;
		if($f->addFeed($_POST['url'],$_POST['type'],$_POST['source'], $credits)){
			$message = __('Feed added');
			$messageStatus = 'exito';
		}
	}
	
	if(isset($_GET['delete']) && is_numeric($_GET['delete'])){
		if($f->removeFeed((int)$_GET['delete'])){
			$message = __('Feed deleted');
			$messageStatus = 'error';
		}
	}
	
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>gelato :: <?php echo __("Feeds")?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="gelato cms <?php echo version();?>" />
		<link rel="shortcut icon" href="<?php echo $conf->urlGelato;?>/images/favicon.ico" />
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/tools.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/mootools.js"></script>
		<style type="text/css" media="screen">	
			@import "<?php echo $conf->urlGelato;?>/admin/css/style.css";
		</style>
	</head>
	
	<body>
		<div id="cont">
			<div id="head">
				<h1><a href="<?php echo $conf->urlGelato;?>/" title="gelato :: <?php echo __("home")?>">gelato cms</a></h1>
				<ul id="nav">
					<li><a href="<?php echo $conf->urlGelato;?>/" title="<?php echo __("Take me to the tumblelog")?>"><?php echo __("Back to the Tumblelog")?></a></li>
			  	</ul>
			</div>
			<div id="main">				
				
				<div class="box">
					<h3><?php echo __("Configure your feeds")?></h3>
					<ul class="menu manage">
					<li><a href="index.php"><?php echo __("Post")?></a></li>
                    <li><a href="options.php"><?php echo __("Options")?></a></li>
					</ul>
					
					<?php echo (!(empty($message))? '<div class="'.$messageStatus.'" id="divMessages"> '.$message.'</div>' : ''); ?>

					<div class="tabla">

						<form action="feeds.php" method="post" class="newpost">
						<fieldset>
						<ul>
							<li>
								<label for="source"><?php echo __("Import data from:")?></label>
								<select name="source" id="source" onchange="selectFeedType('<?php echo __("Feed Url:")?>','<?php echo __(" username:")?>' )">
                                	<option value="Rss" selected="selected">Rss/atom Feed</option>
                                 	<option value="Flickr">Flickr</option>
                                    <option value="Twitter">Twitter</option>
                                    <option value="Youtube">Youtube</option>
                                    <option value="Last.fm">Last.fm</option>
                                    <option value="Tumblr">Tumblr</option>
                                    <option value="Wordpress.com">Wordpress.com</option>
                                    <option value="Blogger">Blogger</option>
                                    <option value="VOX">VOX</option>
                                </select>
							</li>
							<li id="import_as"><label for="type"><?php echo __("Import As:")?></label>
                                    <select name="type" id="type">
                                    	<option value="1" selected="selected"><?php echo __("Text")?></option>
                                    	<option value="2"><?php echo __("Photos")?></option>
                                    </select>
							</li>
							<li>
								<label for="url" id="url_label"><?php echo __("Feed Url (double check):")?></label>
									<input type="text" name="url" id="url" class="txt" />
							</li>
                            <li><label for="credits"><?php echo __('Show credits on each post?')?></label><input type="checkbox" name="credits" checked="checked" class="check" /></li>
							<li>
								<input name="add" type="submit" value="<?php echo __("Add"); ?>" />
							</li>
						</ul>
						</fieldset>
						</form>
						<div id="feedlist">						  
						  <ul>
						    <?php
							$actual_feeds = $f->getFeedList();
							foreach($actual_feeds as $feed){
								if($feed['error']>0){
									echo '<li class="feederror"><a href="feeds.php?delete='.$feed['id_feed'].'" title="'.__('Delete this Feed').'" class="action"><img title="" alt="" src="css/images/delete.png"/></a><span class="status">'.__('Error updating').'</span> '.((!empty($feed['title']))? $feed['title'] : $feed['url']).'</li>';
								}else{
									echo '<li><a href="feeds.php?delete='.$feed['id_feed'].'" title="'.__('Delete this Feed').'" class="action"><img title="" alt="" src="css/images/delete.png"/></a><span class="status" title="'.__('Last update').': '.$feed['updated_at'].'">'.__('Importing').'</span> '.((!empty($feed['title']))? $feed['title'] : $feed['url']).'</li>';
								}
							}
						?>
					      </ul>
					    </div>
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
} else {
	header("Location: ".$conf->urlGelato."/login.php");
}
?>