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
class configuration {
	
	var $urlGelato;
	var $tablePrefix;
	var $idUser;
	var $postLimit;
	var $title;
	var $description;
	var $lang;	
	var $template;
	var $urlFriendly;
	var $richText;
	var $offset_city;
	var $offset_time;
	var $shorten_links;
	var $rssImportFrec;
	var $check_version;
	var $plugins = array();	
		
	function configuration() {		
		global $isFeed;
		global $db;
		
		if ($db->ejecutarConsulta("SELECT * FROM ".Table_prefix."config")) {
			$row=$db->obtenerRegistro();
			$this->tablePrefix = Table_prefix;
			$this->urlGelato = $row['url_installation'];				
			$this->postLimit = $row['posts_limit'];
			$this->title = $row['title'];
			$this->description = $row['description'];
			$this->lang = $row['lang'];				
			$this->template = $row['template'];
			
			initLang($this->lang);

			$this->urlFriendly = $this->get_option("url_friendly");
			$this->richText = $this->get_option("rich_text");			
			$this->offsetCity = $this->get_option("offset_city");
			$this->offsetTime = $this->get_option("offset_time");
			$this->allowComments = $this->get_option("allow_comments");
			$this->shorten_links = $this->get_option("shorten_links");
			$this->rssImportFrec = $this->get_option("rss_import_frec");
			$this->check_version = $this->get_option("check_version");
			
			//TODO: Soporte de los plugins desde BD activar/desactivar
			if ($handle = opendir(Absolute_Path."plugins")) {				
				while (false !== ($file = readdir($handle))) { 
					if (substr($file, strlen($file)-4, 4) == ".php") {
						require_once(Absolute_Path."plugins/{$file}");
						$this->plugins[] = substr($file, 0, strlen($file)-4);						
					} 
				} 
				closedir($handle); 
			}			
		} else {
			if($isFeed) {
				header("HTTP/1.0 503 Service Unavailable"); 
				header("Retry-After: 60"); 
				exit();
			} else {				
				$message = "
				<h3 class=\"important\">Error establishing a database connection</h3>
				<p>The configuration info is not available.</p>";
				die($message);	
			}
		}
	}
	
	function get_option($name){
		global $db;
		$sql = "SELECT * FROM ".Table_prefix."options WHERE name='".$name."' LIMIT 1";
		$db->ejecutarConsulta($sql);
		if ($db->contarRegistros()>0) {
			$row=$db->obtenerRegistro();
			return $row['val'];
		} else {
			return "0";
		}
	}
}
?>