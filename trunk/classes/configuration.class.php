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
class configuration extends Conexion_Mysql {
	
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
	
	
	function configuration() {
		parent::Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);
		
		global $isFeed;
		
		if ($this->ejecutarConsulta("SELECT * FROM ".Table_prefix."config")) {
			$row=$this->obtenerRegistro();
			$this->tablePrefix = Table_prefix;
			$this->urlGelato = $row['url_installation'];				
			$this->postLimit = $row['posts_limit'];
			$this->title = $row['title'];
			$this->description = $row['description'];
			$this->lang = $row['lang'];				
			$this->template = $row['template'];

			$this->urlFriendly = $this->get_option("url_friendly");
			$this->richText = $this->get_option("rich_text");
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
		$sql = "SELECT * FROM ".Table_prefix."options WHERE name='".$name."' LIMIT 1";
		$this->ejecutarConsulta($sql);
		if ($this->contarRegistros()>0) {
			$row=$this->obtenerRegistro();
			return $row['val'];
		} else {
			return "0";
		}
	}
}
?>