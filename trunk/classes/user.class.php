<?
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?
require_once("configuration.class.php");

class user extends Conexion_Mysql {
	var $conf;

	function user() {
		parent::Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);
		$this->conf = new configuration();
	}

	function isAdmin() {
		if(isset($_COOKIE["gelato_cookie"]) && $_COOKIE["gelato_cookie"] && $_COOKIE["gelato_cookie"]!="") {
			$galleta = explode(",",$_COOKIE["gelato_cookie"]);
			if ($this->validateUser($galleta[1],$galleta[2])) {
				$_SESSION["user_id"]=$galleta[0];
				$_SESSION["user_login"]=$galleta[1];
			} else {
				$_SESSION["user_id"]="";
				$_SESSION["user_login"]="";
				unset($_SESSION["user_id"]);
				unset($_SESSION["user_login"]);
			}
		}
		if (isset($_SESSION["user_id"]) && isset($_SESSION["user_login"])) {
			return true;
		}
		return false;
	}

	function validateUser($user="", $password="") {
		if ($this->ejecutarConsulta("SELECT id_user, login, password  FROM ".$this->conf->tablePrefix."users WHERE login='".$user."' AND password='".$password."'")) {
			if ($this->contarRegistros()>0) {
				$register=$this->obtenerRegistro();
				$_SESSION['user_id']=$register["id_user"];
				$_SESSION['user_login']=$register["login"];
				if (isset($_POST["save_pass"])) {
					$cookie_life = 60*24*3600;
					setcookie("gelato_cookie",$register["id_user"].",".$register["login"].",".$register["password"],time()+$cookie_life);
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
		$_SESSION = array();
		$_COOKIE["gelato_cookie"]="";
		setcookie("gelato_cookie","",time()-3600,'/','',0);
		setcookie("gelato_cookie","",0);
		unset($_COOKIE["gelato_cookie"]);
		unset($_COOKIE[session_name()]);
		if (session_destroy()) {
			return true;
		} else {
			return false;
		}
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

	function addUser($fieldsArray) {
		if ($this->ejecutarConsulta("SELECT id_user FROM ".$this->conf->tablePrefix."users WHERE login='".$fieldsArray['login']."'")) {
			if ($this->contarRegistros()==0) {
				if ($this->insertarDeFormulario($this->conf->tablePrefix."users", $fieldsArray)) {
					$this->confirmationEmail($fieldsArray['email'], $fieldsArray['login'], $fieldsArray['password']);
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
		$msg .=	"<font size=1>Username: <strong>".$user."</strong><br/><br/>";
		$msg .=	"Password: <strong>".$password."</strong><br/><br/>";
		$msg .=	"<em>Don't tell your password to anybody!!</em><br/><br/></font>";

		sendMail($email, "Register conformation on gelato CMS", $msg, "no-reply@gelatocms.com");
	}
}
?>