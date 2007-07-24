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

class gelato extends Conexion_Mysql {
	var $conf;
	
	function gelato() {
		parent::Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);
		$this->conf = new configuration();
	}
	
	function saveSettings($fieldsArray) {
		if ($this->modificarDeFormulario($this->conf->tablePrefix."config", $fieldsArray)) {
			header("Location: ".$this->conf->urlGelato."/admin/settings.php?modified=true");
			die();
		} else {
			header("Location: ".$this->conf->urlGelato."/admin/settings.php?error=1&des=".$this->merror);
			die();
		}
	}
	
	function saveOption($value, $name) {
		$sqlStr = "UPDATE ".$this->conf->tablePrefix."options SET val='".$value."' WHERE name='".$name."' LIMIT 1";		
		if ($this->ejecutarConsulta($sqlStr)) {
			return true;
		} else {
			return true;
		}
	}
	
	function addPost($fieldsArray) {		
		if ($this->insertarDeFormulario($this->conf->tablePrefix."data", $fieldsArray)) {
			return true;
		} else {
			return false;
		}		
	}
	
	function modifyPost($fieldsArray, $id_post) {
		if ($this->modificarDeFormulario($this->conf->tablePrefix."data", $fieldsArray, "id_post=$id_post")) {
			header("Location: ".$this->conf->urlGelato."/admin/index.php?modified=true");
			die();
		} else {
			header("Location: ".$this->conf->urlGelato."/admin/index.php?error=2&des=".$this->merror);
			die();
		}
	}
	
	function deletePost($idPost) {
		$this->ejecutarConsulta("DELETE FROM ".$this->conf->tablePrefix."data WHERE id_post=".$idPost);
	}	
	
	function getPosts($limit="10", $from="0") {
		$sqlStr = "select * from ".$this->conf->tablePrefix."data ORDER BY date DESC LIMIT $from,$limit";
		$this->ejecutarConsulta($sqlStr);
		return $this->mid_consulta;
	}
	
	function getPost($id="") {
		$this->ejecutarConsulta("select * from ".$this->conf->tablePrefix."data WHERE id_post=".$id);
		return mysql_fetch_array($this->mid_consulta);
	}
	
	function getPostsNumber() {
		$this->ejecutarConsulta("select count(*) as total from ".$this->conf->tablePrefix."data");
		$row = mysql_fetch_assoc($this->mid_consulta);
		return $row['total'];
	}
	
	function getType($id) {
		if ($this->ejecutarConsulta("select type from ".$this->conf->tablePrefix."data WHERE id_post=".$id)) {	
			if ($this->contarRegistros()>0) {	
				while($registro = mysql_fetch_array($this->mid_consulta)) {
					return $registro[0];
				}
			}
		} else {
			return "0";
		}
	}
	
	function formatConversation($text) {
		$formatedText = "";
		$odd=true;
		
		$lines = explode("\n", $text);
		
		$formatedText .= "<ul>\n";
		foreach ($lines as $line) {
			$pos = strpos($line, ":") + 1;
			
			$label = substr($line, 0, $pos);
			$desc = substr($line, $pos, strlen($line));
			
			if ($odd) { 
				$cssClass = "odd"; 
			} else {
				$cssClass = "even"; 
			}
			$odd=!$odd;
			
			
			$formatedText .= "	<li class=\"".$cssClass."\">\n";
			$formatedText .= "		<span class=\"label\">".$label."</span>\n";
			$formatedText .= "		".$desc."\n";
			$formatedText .= "	</li>\n";			
		}
		$formatedText .= "</ul>\n";
		return $formatedText;
	}
	
	function saveMP3($remoteFileName) {
		if (getMP3File($remoteFileName)) {
			return true;
		} elseif (isGoEar($remoteFileName)) {
			return true;
		} else {
			return false;
		}
	}
	
	function savePhoto($remoteFileName) {		
		if (getPhotoFile($remoteFileName)) {
			return true;
		} else {
			return false;
		}
	}
	
	function getVideoPlayer($url) {
		if (isYoutubeVideo($url)) {
			$id_video = getYoutubeVideoUrl($url);
			return "\t\t\t<object type=\"application/x-shockwave-flash\" style=\"width:500px;height:393px\" data=\"http://www.youtube.com/v/".$id_video."\"><param name=\"movie\" value=\"http://www.youtube.com/v/".$id_video."\" /></object>\n";
		} elseif (isVimeoVideo($url)) {
			$id_video = getVimeoVideoUrl($url);
			return "\t\t\t<object type=\"application/x-shockwave-flash\" style=\"width:500px;height:393px\" data=\"http://www.vimeo.com/moogaloop.swf?clip_id=".$id_video."\"><param name=\"movie\" value=\"http://www.vimeo.com/moogaloop.swf?clip_id=".$id_video."\" /></object>\n";
		} else {
			return "This URL is not a supported video (YouTube or Vimeo)";
		}		
	}
	
	function getMp3Player($url) {
		if (isMP3($url)) {
			$playerUrl = $this->conf->urlGelato."/admin/scripts/player.swf?soundFile=".$url;
			return "\t\t\t<object type=\"application/x-shockwave-flash\" data=\"" . $playerUrl . "\" width=\"290\" height=\"24\"><param name=\"movie\" value=\"" . $playerUrl . "\" /><param name=\"quality\" value=\"high\" /><param name=\"menu\" value=\"false\" /><param name=\"wmode\" value=\"transparent\" /></object>\n";
		} elseif (isGoEar($url)) {
			return "\t\t\t<object type=\"application/x-shockwave-flash\" data=\"http://www.goear.com/files/localplayer.swf\" width=\"366\" height=\"75\"><param name=\"movie\" value=\"http://www.goear.com/files/localautoplayer.swf\" /><param name=\"quality\" value=\"high\" /><param name=\"FlashVars\" value=\"file=".getGoEarCode($url)."\" /></object>\n";
		} else {
			return "This URL is not an MP3 file.";
		}		
	}	
} 
?>