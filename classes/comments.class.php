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
		if ($this->insertarDeFormulario($this->conf->tablePrefix."data", $fieldsArray)) {
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
	
	function confirmacionEmail($email_autor_post, $tit_blog, $desc_blog, $url_blog, $id_post, $titulo_post, $usuario, $email, $pagina_web, $comentario, $ip_usuario) {
		$msg =  "<br/><br/><font face=verdana><em><font size=2>Hay un nuevo comentario en el post #".$id_post." \"".$titulo_post."\"</font></em><br/><br/>";
		$msg .=	"Autor : ".$usuario." (IP: ".$ip_usuario.")<br /><br />";
		$msg .=	"E-mail : <a href=\"mailto:".$email."\">".$email."</a><br /><br />";
		$msg .=	"URL &nbsp; &nbsp;: <a href=\"".$pagina_web."\" target=\"_blank\">".$pagina_web."</a><br /><br />";
		$msg .=	"Whois &nbsp;: <a href=\"http://ws.arin.net/cgi-bin/whois.pl?queryinput=".$ip_usuario."\" target=\"_blank\">http://ws.arin.net/cgi-bin/whois.pl?queryinput=".$ip_usuario."</a><br /><br />";
		$msg .=	"Comentario:<br /><br />".$comentario;
		$msg .=	"<br /><br /><a href=\"".$url_blog."/index.php?id=".$id_post."\">".$url_blog."/index.php?id=".$id_post."</a><br /><br />";
		
		enviaMail($email_autor_post, "[".$desc_blog."] Nuevo mensaje en: ".$titulo_post."", $msg, EMAIL_ADMIN);
	}	
	
	function obtenerComentarios($idArticulo="") {
		$this->ejecutarConsulta("select * from ".tabla_prefijo."comentarios WHERE id_post=".$idArticulo." AND spam=0 order by fecha ASC");
		return $this->mid_consulta;
	}
	
	function contarComentarios($idArticulo="") {
		$this->ejecutarConsulta("select * from ".tabla_prefijo."comentarios WHERE id_post=".$idArticulo." AND spam=0");
		return $this->contarRegistros();
	}
	
	function mostrarGravatar($email="") {
		$emailg = $email;
		$default = URL_CODICE."/images/noGravatar.jpg";
		$size = 30;
		$grav_url = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($emailg)."&amp;default=".urlencode($default)."&amp;size=".$size;
		return "<img src=\"".$grav_url."\" alt=\"Gravatar\" title=\"Gravatar\" />";
	}
	
} 
?>