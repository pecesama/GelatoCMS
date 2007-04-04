<?
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?
	function beginsWith($str, $sub) {
		return (strpos($str, $sub) === 0);
	}
	
	function endsWith($str, $sub) {
		return (substr($str, strlen($str) - strlen($sub)) == $sub);
	}
	
	function getFileName($fileUrl) {
		$path = explode('/', $fileUrl);
		return $path[count($path)-1];
	}
	
	function isMP3($fileUrl) {
		if (endsWith($fileUrl, ".mp3")) {
			return true;
		} else {
			return false;
		}
	}
	
	function getMP3File($remoteFileName) {
		if (isMP3($remoteFileName)) {
			if (getFile($remoteFileName)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function isImageFile($photoUrl) {
		if (endsWith($photoUrl, ".jpg")) { return true; }
		elseif (endsWith($photoUrl, ".gif")) { return true; }
		elseif (endsWith($photoUrl, ".png")) { return true; }
		else { return false; }
	}
	
	function getPhotoFile($remoteFileName) {
		if (isImageFile($remoteFileName)) {		
			if (getFile($remoteFileName)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getFile($remoteFileName) {		
		$fileName = "../uploads/".getFileName($remoteFileName);
		$str = file_get_contents($remoteFileName);
		if (!$handle = fopen($fileName, 'w')) {
			return false;
		}
		if (fwrite($handle, $str) === FALSE) {
			return false;
		}
		fclose($handle);
		return true;		
	}
	
	function isVimeoVideo($videoUrl) {
		if (beginsWith($videoUrl, "http://vimeo.com/clip:") || beginsWith($videoUrl, "http://www.vimeo.com/clip:"))
			return true;
		else
			return false;
	}
	
	function getVimeoVideoUrl($videoUrl) {
		return array_pop(explode("clip:",$videoUrl));
	}
	
	function isYoutubeVideo($videoUrl) {
		if (beginsWith($videoUrl, "http://youtube.com/watch?v=") || beginsWith($videoUrl, "http://www.youtube.com/watch?v="))
			return true;
		else
			return false;
	}
	
	function getYoutubeVideoUrl($videoUrl) {
		$params = explode("?v=", $videoUrl);
		$params2 = explode("&",$params[1]);
		return $params2[0];
	}
	
	function isVideo($url) {
		if (isYoutubeVideo($url)) { return true; }
		elseif (isVimeoVideo($url)) { return true; }
		else { return false; }
	}
	
	function sendMail($to, $title, $body, $from) {					
		$rp     = trim($from);
		$org    = "gelato CMS";
		$mailer = "gelato CMS Mailer";
			
		$head  = '';
		$head  .= "Content-Type: text/html \r\n";
		$head  .= "Date: ". date('r'). " \r\n";
		$head  .= "Return-Path: $rp \r\n";
		$head  .= "From: $from \r\n";
		$head  .= "Sender: $from \r\n";
		$head  .= "Reply-To: $from \r\n";
		$head  .= "Organization: $org \r\n";
		$head  .= "X-Sender: $from \r\n";
		$head  .= "X-Priority: 3 \r\n";
		$head  .= "X-Mailer: $mailer \r\n";
			
		$body  = str_replace("\r\n", "\n", $body);
		$body  = str_replace("\n", "\r\n", $body);
			
		return @mail($to, $title, $body, $head);			
	}
	
	function getThemes() {
 		$themes_dir = "themes";
 		$dirs = array();
		$path = getcwd();
 		$dir = (substr(PHP_OS, 0, 3) == 'WIN') ? $path."\\".$themes_dir : $path."/".$themes_dir;
		$dir = str_replace("admin\\", "", $dir);
 		$handle = opendir($dir);
 		$i=0;
 		while($filename = readdir($handle)) {
 			if($filename != "." && $filename != "..") {
 				$dirs[$i]=trim($filename);
 				$i++;
 			}
 		}
 		closedir($handle);
 		return $dirs;
 	}	
?>