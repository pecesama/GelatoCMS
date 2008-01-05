<?php
if(!defined('entry') || !entry) die('Not a valid page'); 
/* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */
?>
<?php
require_once("configuration.class.php");
require_once("simplepie.class.php");

class feeds extends Conexion_Mysql {
	var $conf;
	
	
	function feeds() {
		parent::Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);
		$this->conf = new configuration();
	}
	
	function addFeed($url, $type, $source, $credits){
		$f = array();
		$f['updated_at'] = '0000-00-00 00:00:00';
		$f['error']	= 0;
		$f['title'] = '';
		$f['url'] = '';
		$f['credits'] = $credits;
		$f['site_url'] = '';
		$f['id_user'] = $_SESSION['user_id'];
		switch($source){
			case 'Rss': $f['url'] = $url;  $f['type'] = $type; break;
			case 'VOX': 	$f['url'] = 'http://{{username}}.vox.com/library/posts/atom.xml'; $f['type'] = 1; break;
			case 'Digg': 	$f['url'] = 'http://digg.com/rss/{{username}}/index2.xml'; $f['type'] = 1; break;
			case 'Tumblr': 	$f['url'] = 'http://{{username}}.tumblr.com/rss'; $f['type'] = 1; break;
			case 'Twitter': $f['url'] = 'http://twitter.com/{{username}}'; $f['type'] = 1; break;
			case 'Last.fm': $f['url'] = 'http://ws.audioscrobbler.com/1.0/user/{{username}}/recenttracks.rss'; $f['type'] = 1; break;
			case 'Blogger': $f['url'] = 'http://{{username}}.blogspot.com/feeds/posts/default'; $f['type'] = 1; break;
			case 'Youtube': $f['url'] = 'http://www.youtube.com/rss/user/{{username}}/videos.rss'; $f['type'] = 1; break;
			case 'Wordpress.com': $f['url'] = 'http://{{username}}.wordpress.com/feed/'; $f['type'] = 1; break;
			default : $f['url'] = ''; break;
		}
		if(!empty($f['url'])){
			$f['url'] = str_replace('{{username}}',$url,$f['url']);
			return ($this->insertarDeFormulario($this->conf->tablePrefix."feeds", $f));
		}
		print_r($_POST);
		print_r($f);
	}
	
	function removeFeed($id){
		return ($this->ejecutarConsulta("DELETE FROM ".$this->conf->tablePrefix."feeds WHERE id_feed=".$id));
	}
	
	function updateFeeds(){
			$timeToUpdate = trim($this->conf->rssImportFrec). ' ago';
			$feeds = $this->getFeedList("WHERE updated_at <  '".date("Y-m-d H:i:s",strtotime($timeToUpdate))."'");
			foreach($feeds as $feed){
				$data = new SimplePie();
				$data->feed_url($feed['url']);
				$data->cache_location(Absolute_Path."/uploads/CACHE");
				$data->init();
				$temp = array();
				$temp['updated_at'] = date("Y-m-d H:i:s");
				if (!empty($data->error)){
					// Error report
					$temp['error'] = 1;
					$this->modificarDeFormulario($this->conf->tablePrefix."feeds", $temp, 'id_feed = '.$feed['id_feed']);
				}else{

					if($data->data){ 
						$timeFilter = strtotime($timeToUpdate);
						foreach($data->get_items() as $post){
							if (strtotime($post->get_date("Y-m-d H:i:s")) > strtotime($feed['updated_at'])) {
								$newPost = array();
								$newPost['id_user'] = $feed['id_user'];
								$newPost['title'] = $post->get_title();
								$newPost['date'] = $post->get_date("Y-m-d H:i:s");
								if($feed['type'] == 1){ //TEXT
									$newPost['type'] = 1;
									if($post->get_title()  != $post->get_description()){
										if(strpos($feed['url'],'twitter.com') <= 0){
											$newPost['description'] = $post->get_description();
										}
									}
								}elseif($feed['type'] == 2){ //IMAGES
									$ma = array();
									$url_image = '';
									@preg_match_all('/\<img ([^\>]+)/',$post->get_description(), $ma);
									@preg_match_all('/src\=\"([^\"]+)\"/',$ma[0][0], $ma);
									$url_image = $ma[1][0];
									
									if(empty($url_image)){
										/* Theres no image, lets make a text post */
										$newPost['type'] = 1;
										if($post->get_title()  != $post->get_description()){
											if(strpos($feed['url'],'twitter.com') <= 0){
												$newPost['description'] = $post->get_description();
											}
										}
									}else{
										$newPost['type'] = 2;
										$newPost['url'] = $url_image;
										$newPost['description'] = $post->get_title();
									}
								}
								if($feed['credits'] == 1 && !empty($feed['title'])){
									$newPost['description'] .= '<p class="rss-credits">(via <a href="'.((empty($feed['site_url']))? $feed['url'] : $feed['site_url']).'" title="'.$feed['title'].'">'.$feed['title'].'</a>)</p>';
								}
								$this->insertarDeFormulario($this->conf->tablePrefix."data", $newPost);
							}	
						}
					$temp['title'] = (!empty($data->data['info']['title']))? $data->data['info']['title'] : '';
					$temp['site_url'] = (!empty($data->data['info']['link']['alternate'][0]))? $data->data['info']['link']['alternate'][0] :  $data->data['info']['link'];
					$temp['error'] = 0;
					$this->modificarDeFormulario($this->conf->tablePrefix."feeds", $temp, 'id_feed = '.$feed['id_feed']);
	
					}
				}
			}

	}
	
	
	function getFeedList($condition = ''){
		$feeds = array();
		$this->ejecutarConsulta('SELECT * FROM '.$this->conf->tablePrefix.'feeds '.$condition.' ORDER BY id_feed DESC');
		while($feed = mysql_fetch_assoc($this->mid_consulta)){
			$feeds[] = $feed;
		}
		return $feeds;
	}
	
	
	/**
	Calculate the seconds until next update
	
	@param $feed can be an ID, or the raw array from the DB
	@raturn int
	*/
	function getNextUpdate($feed){
		if(is_numeric($feed)){
			$id = (int)$feed;
		}elseif(is_array($feed)){
			$id = $feed['id_feed'];
		}else{
			return false;
		}
		$timeToUpdate = trim($this->conf->rssImportFrec). ' ago';
		$delta = time() - strtotime($timeToUpdate);
		$this->ejecutarConsulta('SELECT (UNIX_TIMESTAMP(updated_at) - '.$delta.') - UNIX_TIMESTAMP(NOW())  FROM '.$this->conf->tablePrefix.'feeds WHERE id_feed = '.$id);
		$time = mysql_fetch_array($this->mid_consulta);
		return $time[0];

	}
}
?>