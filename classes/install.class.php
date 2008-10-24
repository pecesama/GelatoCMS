<?php
if(!defined('entry') || !entry) die('Not a valid page');
require(Absolute_Path.'/classes/mysql_connection.class.php');

class Install {
	var $data = null;
	var $errors = null;
	var $showForm;
	var $errors_d = array();

	function Install(){
		$this->errors_d[1]="The login field cannot be empty";
		$this->errors_d[2]="The password field cannot be empty";
		$this->errors_d[3]="Password does not match the confirm password";
		$this->errors_d[4]="The e-mail field cannot be empty";
		$this->errors_d[5]="The installation URL field cannot be empty";
		$this->errors_d[6]="Error establishing a database connection";
		$this->errors_d[7]="Please add a hostname for the database server";
		$this->errors_d[8]="Please name the database";
		$this->errors_d[9]="Password does not match the confirm password";
		$this->errors_d[10]="The login field cannot be empty";
	}

    function run() {

    	if (empty($this->data)) false;

    	$this->create_db();

    	if (!$this->install_db()) return false;

		return true;
    }

    function create_db(){

	    $link =  mysql_connect($this->data['db_host'], $this->data['db_login'], $this->data['db_password']);
		if (!$link) {
		    die('Could not connect: ' . mysql_error());
		}

		$sql = 'CREATE DATABASE ' . $this->data['db_name'];
		if (!mysql_query($sql, $link)) {
			$link = mysql_close($link);
			return false;
		}

		return true;
    }

	function install_db(){
		require_once(Absolute_Path.'config.php');
		$db = new Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);
		$sqlStr = array();

		$sqlStr[] = "CREATE TABLE `".Table_prefix."data` (
			  `id_post` int(11) NOT NULL auto_increment,
			  `title` text NULL,
			  `url` varchar(250)  default NULL,
			  `description` text NULL,
			  `type` tinyint(4) NOT NULL default '1',
			  `date` datetime NOT NULL,
			  `id_user` int(10) NOT NULL,
			  PRIMARY KEY  (`id_post`)
			) ENGINE = MYISAM ;";

		$sqlStr[] = "CREATE TABLE `".Table_prefix."users` (
			  `id_user` int(10) unsigned NOT NULL auto_increment,
			  `name` varchar(100) default NULL,
			  `login` varchar(100) NOT NULL default '',
			  `password` varchar(64) NOT NULL default '',
			  `email` varchar(100) default NULL,
			  `website` varchar(150) default NULL,
			  `about` text,
			  PRIMARY KEY  (`id_user`)
			) ENGINE = MYISAM;";

		$sqlStr[] = "CREATE TABLE `".Table_prefix."config` (
			  `posts_limit` int(3) NOT NULL,
			  `title` varchar(250) NOT NULL,
			  `description` text NOT NULL,
			  `lang` varchar(10) NOT NULL,
			  `template` varchar(100) NOT NULL,
			  `url_installation` varchar(250) NOT NULL,
			  PRIMARY KEY  (`title`)
			) ENGINE = MYISAM ;";



		$sqlStr[] = "CREATE TABLE `".Table_prefix."options` (
		  `name` varchar(100) NOT NULL,
		  `val` varchar(255) NOT NULL,
		  PRIMARY KEY  (`name`)
		) ENGINE = MYISAM ;";


		$sqlStr[] = "CREATE TABLE `".Table_prefix."comments` (
		  `id_comment` int(11) NOT NULL auto_increment,
		  `id_post` int(11) NOT NULL,
		  `username` varchar(50) NOT NULL,
		  `email` varchar(100) NOT NULL,
		  `web` varchar(250) default NULL,
		  `content` text NOT NULL,
		  `ip_user` varchar(50) NOT NULL,
		  `comment_date` datetime NOT NULL,
		  `spam` tinyint(4) NOT NULL,
		  PRIMARY KEY  (`id_comment`)
		) ENGINE = MYISAM ;";

		$sqlStr[] = "CREATE TABLE `".Table_prefix."feeds` (
			`id_feed` int(11) NOT NULL auto_increment,
			`url` varchar(255) NOT NULL,
			`title` varchar(255) NOT NULL,
			`type` tinyint(4) NOT NULL default '1',
			`updated_at` datetime NOT NULL,
			`error` tinyint(1) NOT NULL default '0',
			`credits` int(1) NOT NULL default '0',
			`site_url` varchar(255) NOT NULL,
			`id_user` int(10) NOT NULL,
			PRIMARY KEY  (`id_feed`)
			) ENGINE=MyISAM ;";


		$sqlStr[] = "INSERT INTO `".Table_prefix."config` VALUES (". $this->data['posts_limit'] .", '".$this->data['title']."', '".$this->data['description']."', '".$this->data['lang']."', '".$this->data['template']."', '".$this->data['url_installation']."');";
		$sqlStr[] = "INSERT INTO `".Table_prefix."users` VALUES ('', '', '".$this->data['login']."', '".md5($this->data['password'])."', '".$this->data['email']."', '".$this->data['website']."', '".$this->data['about']."');";
		$sqlStr[] = "INSERT INTO `".Table_prefix."options` VALUES ('url_friendly', '0');";
		$sqlStr[] = "INSERT INTO `".Table_prefix."options` VALUES ('rich_text', '0');";
		$sqlStr[] = "INSERT INTO `".Table_prefix."options` VALUES ('allow_comments', '0');";
		$sqlStr[] = "INSERT INTO `".Table_prefix."options` VALUES ('offset_city', '".$this->data['offset_city']."');";
		$sqlStr[] = "INSERT INTO `".Table_prefix."options` VALUES ('offset_time', '".$this->data['offset_time']."');";
		$sqlStr[] = "INSERT INTO `".Table_prefix."options` VALUES ('shorten_links', '0');";
		$sqlStr[] = "INSERT INTO `".Table_prefix."options` VALUES ('rss_import_frec', '5 minutes');";

		foreach($sqlStr as $key => $query){
			if(!$db->ejecutarConsulta($query)){
				return false;
			}
		}

		return true;
	}

	function inerrors($n) {
		if ( strpos($this->errors,$n)===false) {
			return false;
		} else {
			return true;
		}
	}

	function mostrarerror($n) {
		if ($this->inerrors($n)) {
			return '<span class="error">'.$this->errors_d[$n].'</span>';
		} else {
			return "";
		}
	}

	function is_gelato_installed(){
		if(file_exists(Absolute_Path.'config.php')) {
			include_once(Absolute_Path."config.php");
			if (!$this->check_for_config()){
				return false;
			} else {
				if (!$this->is_db_installed()){
					return false;
				}
			}
			return true;
		}else{
			return false;
		}
	}

	function is_db_installed(){
			$db = new Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);
				$sqlStr = "SELECT * FROM `".Table_prefix."config`";
				if($db->ejecutarConsulta($sqlStr)) {
					return ($db->contarRegistros() > 0);
			}else{
			return false;
			}

	}

	function check_for_config(){
		if(!defined('DB_Server')) return false;
		if(!defined('DB_name')) return false;
		if(!defined('DB_User')) return false;
		if(!defined('DB_Password')) return false;
		return true;
	}

	function check_form(){

		$action="";

		if (isset($this->data['action'])){
			$action=$this->data['action'];
		}

		if (!$this->is_gelato_installed()){

		$this->showForm = true;

			if ($action=="config") {

				$sep_err="";
				$this->errors = false;

				if (!$this->data['login']) {
					$this->errors =$this->errors.$sep_err."1";
					$sep_err="|";
				}
				if (!$this->data['db_login']) {
					$this->errors =$this->errors.$sep_err."10";
					$sep_err="|";
				}
				if (!$this->data['password']) {
					$this->errors=$this->errors.$sep_err."2";
					$sep_err="|";
				}
				if (!$this->data['email']) {
					$this->errors=$this->errors.$sep_err."4";
					$sep_err="|";
				}
				if (!$this->data['url_installation'] ) {
					$this->errors=$this->errors.$sep_err."5";
					$sep_err="|";
				}
				if (!$this->data['db_host'] ) {
					$this->errors=$this->errors.$sep_err."7";
					$sep_err="|";
				}
				if (!$this->data['db_name'] ) {
					$this->errors=$this->errors.$sep_err."8";
					$sep_err="|";
				}
				if ($this->data['password']!=$_POST['password2']) {
					$this->errors=$this->errors.$sep_err."3";
					$sep_err="|";
				}
				if ( $_POST['db_password']!=$_POST['db_password2']) {
					$this->errors=$this->errors.$sep_err."9";
					$sep_err="|";
				}

				$off_r= split("," , $this->data['time_offsets']);
				$this->data['offset_time'] = $off_r[0];
				$this->data['offset_city'] = $off_r[1];
				unset($this->data['time_offsets']);

				if (!$this->errors) {

					if ($this->run($this->data)) {
						$this->showForm=false;
					} else {
						$this->errors=$this->errors.$sep_err."6";
						$sep_err="|";
						$this->showForm=true;
					}
				} else {
					$this->showForm=true;
				}
			}
		}
	}
}
?>