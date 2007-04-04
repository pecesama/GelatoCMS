<?
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?

include "config.php";

class configuration extends Conexion_Mysql {
	
	var $urlGelato;
	var $tablePrefix;
	var $idUser;
	var $postLimit;
	var $title;
	var $description;
	var $lang;
	var $urlFriendly;
	var $richText;
	var $template;
	
	
	function configuration() {
		parent::Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);
		
		if ($this->ejecutarConsulta("SELECT *  FROM ".Table_prefix."config")) {
			if ($this->contarRegistros()>0) {
				$register=$this->obtenerRegistro();
				$this->tablePrefix = Table_prefix;
				$this->urlGelato = $register['url_installation'];				
				$this->idUser = $register['id_user'];
				$this->postLimit = $register['posts_limit'];
				$this->title = $register['title'];
				$this->description = $register['description'];
				$this->lang = $register['lang'];
				$this->urlFriendly = $register['url_friendly'];
				$this->richText = $register['rich_text'];
				$this->template = $register['template'];
			}
		}
	}
}
?>