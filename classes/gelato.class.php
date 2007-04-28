<?
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?
require_once("configuration.class.php");
require_once("functions.php");

class gelato extends Conexion_Mysql {
	var $conf;
	
	function gelato() {
		parent::Conexion_Mysql(DB_name, DB_Server, DB_User, DB_Password);
		$this->conf = new configuration();		
	}
	
	function addPost($fieldsArray) {		
		if ($this->insertarDeFormulario($this->conf->tablePrefix."data", $fieldsArray)) {
			return true;
		} else {
			return false;
		}		
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
		} elseif (isGoogleVideo($url)) {
			$html = trim(_file_get_contents($url));
			$start_code = strpos($html, "var flashObj =\n    \"");
			$end_code = strpos($html, "\";\n  flashObj = flashObj.replace");
			$start_code = $start_code + strlen("var flashObj =\n    \"");
			$video_code = substr($html, $start_code, $end_code - $start_code);	
			$video_code = str_replace("\u003c", "<", $video_code);
			$video_code = str_replace("\u003d", "=", $video_code);
			$video_code = str_replace("\\\"", "\"", $video_code);
			$video_code = str_replace("width:100%;height:100%;", "width:500px;height:393px", $video_code);
			$video_code = str_replace("/googleplayer.swf", "http://video.google.com/googleplayer.swf", $video_code);
			$video_code = str_replace("embed", "object", $video_code);
			$video_code = str_replace("src=", "data=", $video_code);
			$video_code = str_replace('align="middle"', "", $video_code);
			$video_code = str_replace('allowScriptAccess="always"', "", $video_code);
			$video_code = str_replace('quality="best"', "", $video_code);
			$video_code = str_replace('bgcolor="#ffffff"', "", $video_code);
			$video_code = str_replace('scale="noScale"', "", $video_code);
			$video_code = str_replace('salign="TL"', "", $video_code);	
			$video_code = str_replace('FlashVars="playerMode=normal&autoPlay=true&docid=-2519555829449987448&clickUrl="', "", $video_code);
			$video_code = str_replace('          \\', " /", $video_code);
			$video_code = str_replace('\></object\>', ' ></object>', $video_code);
			$video_code = str_replace("id=\"VideoPlayback\"", "", $video_code);
			$video_code = str_replace("&", "&amp;", $video_code);
			return $video_code;
		} else {
			return "This URL is not a supported video (YouTube, Vimeo or Google Video)";
		}		
	}
	
	function getMp3Player($url) {
		if (isMP3($url)) {
			$playerUrl = $this->conf->urlGelato."/admin/scripts/player.swf?soundFile=".$url;
			return "\t\t\t<object type=\"application/x-shockwave-flash\" data=\"" . $playerUrl . "\" width=\"290\" height=\"24\"><param name=\"movie\" value=\"" . $playerUrl . "\" /><param name=\"quality\" value=\"high\" /><param name=\"menu\" value=\"false\" /><param name=\"wmode\" value=\"transparent\" /></object>\n";
		} else {
			return "This URL is not a supported video (YouTube or Vimeo)";
		}		
	}	
} 
?>