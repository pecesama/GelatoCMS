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
require_once("configuration.class.php");
require_once("functions.php");

class comments extends Conexion_Mysql {
	var $conf;
	
	function comments() {
		parent::Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);
		$this->conf = new configuration();
	}
	
	function addComment($fieldsArray) {		
		if ($this->insertarDeFormulario($this->conf->tablePrefix."comments", $fieldsArray)) {
			return true;
		} else {
			return false;
		}
	}
	
	function generateCookie($fieldsArray) {
		setcookie("cookie_gel_user", $fieldsArray["username"], time() + 30000000);
		setcookie("cookie_gel_email", $fieldsArray["email"], time() + 30000000);
		setcookie("cookie_gel_web", $fieldsArray["web"], time() + 30000000);
	}
	
	function isSpam($fieldsArray) {
		if (preg_match( "/^\d+$/", $fieldsArray["username"])) { return true; } 
		elseif (trim($fieldsArray["content"]) == "") { return true; } 
		elseif (preg_match( "/^\d+$/", $fieldsArray["content"])) { return true; } 
		elseif (strtolower($fieldsArray["content"]) == strtolower($fieldsArray["username"])) { return true; } 
		elseif (preg_match("#^<strong>[^.]+\.\.\.</strong>#", $fieldsArray["content"])) { return true; } 
		elseif (3 <= preg_match_all("/a href=/", strtolower($fieldsArray["content"]), $matches)) { return true; } 
		elseif ($this->isBadWord($fieldsArray["content"])) { return true; } 
		else { return false; }
	}
	
	function isBadWord($str="") {
		$bads = array ("puto", "viagra", "ringtones", "casino", "buy", "cheap", "order", "poker", "discount", "fuck", "cool", "site", "online", "very", "cholesterol", "milf", "sex", "sexo", "arredamento", "reddit", "sesso", "lesbico", "vzge", "angelcities", "porno", "holdem", "blackjack", "black-jack", "mortgage", "pharmacy", "loan", "refinance", "credit", "alberghi", "scarica", "hotel", "cellulare", "giochi", "gratis", "gif", "animata", "fantasy", "albergo", "blowjob", "delicio", "cosco", "dealerships");
		for($i=0;$i<sizeof($bads);$i++) {
			if(eregi($bads[$i],$str)) return true;
		}		
		return false;	
	}
	
	function getComments($idPost=null, $limit=null, $from=null, $spam=null) {
		if (isset($idPost)) {
			$this->ejecutarConsulta("select * from ".$this->conf->tablePrefix."comments WHERE id_post=".$idPost." AND spam=0 order by comment_date ASC");
		} else {			
			if (isset($limit) && isset($from)) {
				$limit = " LIMIT $from, $limit";
			} else { ""; }
			if (isset($spam)) { $sp = "1"; } else { $sp = "0"; } 
			$this->ejecutarConsulta("select * from ".$this->conf->tablePrefix."comments WHERE spam=".$sp." order by comment_date ASC".$limit);
		}
		return $this->mid_consulta;
	}
	
	function getComment($id="") {
		$this->ejecutarConsulta("select * from ".$this->conf->tablePrefix."comments WHERE id_comment=".$id);
		return mysql_fetch_array($this->mid_consulta);
	}
	
	function countComments($idPost=null) {
		if (isset($idPost)) {
			$this->ejecutarConsulta("select * from ".$this->conf->tablePrefix."comments WHERE id_post=".$idPost." AND spam=0");
		} else {
			$this->ejecutarConsulta("select * from ".$this->conf->tablePrefix."comments WHERE spam=0");
		}		
		return $this->contarRegistros();
	}
	
	function deleteComment($idComment) {
		if ($this->ejecutarConsulta("DELETE FROM ".$this->conf->tablePrefix."comments WHERE id_comment=".$idComment)) {
			return true;
		} else {
			return false;
		}
	}
	
	function modifyComment($fieldsArray, $id_comment) {
		if ($this->modificarDeFormulario($this->conf->tablePrefix."comments", $fieldsArray, "id_comment=$id_comment")) {
			return true;
		} else {
			return false;
		}
	}
} 
?>