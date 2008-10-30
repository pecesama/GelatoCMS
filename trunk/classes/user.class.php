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

class user extends Conexion_Mysql {
	var $conf;
	var $cookieString;
	var $cookieTime;
	var $persist = false;

	function user() {
		parent::Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);
		$this->cookie_life = 60*24*3600;
		$this->cookieTime = time();
		$this->conf = new configuration();
	}

	function isAdmin() {		
		
		if ((!empty($_SESSION["user_id"]) && !empty($_SESSION["user_login"]))  && (isset($_SESSION['authenticated'])  && $_SESSION['authenticated']==true)) {
			return true;
		}

		if(isset($_COOKIE["PHPSESSID"]) && $_COOKIE["PHPSESSID"]!="") {		
		
			if ((!empty($_SESSION["user_id"]) && !empty($_SESSION["user_login"]))  && (isset($_SESSION['authenticated'])  && $_SESSION['authenticated']==true)) {
				return true;
			}
		
		}

		return false;

	}

	function validateUser($username="", $password="") {

		if ($this->ejecutarConsulta("SELECT id_user, login, password  FROM ".$this->conf->tablePrefix."users WHERE login='".$this->sql_escape($username)."' AND password='".$password."'")) {
			if ($this->contarRegistros()>0) {
				$register=$this->obtenerRegistro();
				$_SESSION['user_id']=$register["id_user"];
				$_SESSION['user_login']=$register["login"];
				$_SESSION['authenticated'] = true;
				if (isset($_POST["save_pass"])) {
					$this->persist = true;
					setcookie("PHPSESSID",session_id(),$this->cookieTime+$this->cookie_life);
				}
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function closeSession() {
		if (!$this->persist) session_destroy();
		return true;
	}

	function userExist($user="") {
		if ($this->ejecutarConsulta("SELECT * FROM ".$this->conf->tablePrefix."users WHERE login='".$user."'")) {
			if ($this->contarRegistros()>0) {
				return true;
			} else {
				return false;
			}
		}
	}

	function isAuthenticated() {
		return $this->isAdmin();
	}

	function addUser($fieldsArray) {
		if ($this->ejecutarConsulta("SELECT id_user FROM ".$this->conf->tablePrefix."users WHERE login='".$fieldsArray['login']."'")) {
			if ($this->contarRegistros()==0) {
				$realPassword = ($fieldsArray["password"]);
				$fieldsArray["password"] = md5($fieldsArray["password"]);
				if ($this->insertarDeFormulario($this->conf->tablePrefix."users", $fieldsArray)) {
					$this->confirmationEmail($fieldsArray['email'], $fieldsArray['login'], $realPassword);
					header("Location: ".$this->conf->urlGelato."/admin/admin.php?added=true");
					die();
				} else {
					header("Location: ".$this->conf->urlGelato."/admin/admin.php?error=2&des=".$this->merror);
					die();
				}
			} else {
				header("Location: ".$this->conf->urlGelato."/admin/admin.php?error=1");
				die();
			}
		}
	}

	function modifyUser($fieldsArray, $id_user) {
		$fieldsArray["password"] = md5($fieldsArray["password"]);
		if ($this->modificarDeFormulario($this->conf->tablePrefix."users", $fieldsArray, "id_user=$id_user")) {
			header("Location: ".$this->conf->urlGelato."/admin/admin.php?modified=true");
			die();
		} else {
			header("Location: ".$this->conf->urlGelato."/admin/admin.php?error=2&des=".$this->merror);
			die();
		}
	}

	function deleteUser($idUser) {
		$this->ejecutarConsulta("DELETE FROM ".$this->conf->tablePrefix."users WHERE id_user=".$idUser);
	}

	function getUsers($show="10", $from="0") {
		$sqlStr = "select * from ".$this->conf->tablePrefix."users ORDER BY id_user DESC LIMIT $from,$show";
		$this->ejecutarConsulta($sqlStr);
		return $this->mid_consulta;
	}

	function getUserByID($idUser) {
		if ($this->ejecutarConsulta("select * from ".$this->conf->tablePrefix."users where id_user=".$idUser)) {
			if ($this->contarRegistros()>0) {
				return $registro=$this->obtenerRegistro();
			} else {
				return false;
			}
		}
	}

	function confirmationEmail($email="", $user="", $password="") {
		$msg =  "<font face=verdana><em><font size=2>Account information on <strong>gelato CMS</strong></font></em><br/><br/>";
		$msg .=	"Visit the <a href=\"".$this->conf->urlGelato."/admin/\">tumblelog panel</a> <br/><br/>";
		$msg .=	"<font size=1>Username: <strong>".$user."</strong><br/><br/>";
		$msg .=	"Password: <strong>".$password."</strong><br/><br/>";		
		$msg .=	"<em>Don't tell your password to anybody!!</em><br/><br/></font>";

		sendMail($email, "Register confirmation on gelato CMS", $msg, "no-reply@gelatocms.com");
	}
}
?>