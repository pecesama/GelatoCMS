<?php
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?php
	function version() {
		return "0.8";
	}
	
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
		} elseif (isGoEar($remoteFileName)) {
			return true;
		} else {
			return false;
		}
	}
	
	function isGoEar($songUrl) {
		if (beginsWith($songUrl, "http://www.goear.com/listen.php?v="))
			return true;
		else
			return false;
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
		$str = _file_get_contents($remoteFileName);
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
		$dir = str_replace("admin/", "", $dir);
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
	
	function _file_get_contents($path) {
		// Modified function from: 
		//		http://work.dokoku.net/Anieto2k/_file_get_contents.phps
		//		http://www.anieto2k.com/2007/02/09/file_get_contents-y-dreamhost/
		if (!preg_match('/^http/i', $path)) {
			if ($fp = fopen($path, 'r')) {
				return fread($fp, 1024);
			} else {			
				return false;
			}
		} else {
			if (extension_loaded('curl') && version_compare(get_curl_version(), '7.10.5', '>=')) {
				$ch = curl_init();
				$timeout = 5;
				curl_setopt ($ch, CURLOPT_URL, $path);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				$file_contents = curl_exec($ch);
				curl_close($ch);
				if (is_string($file_contents)) {
					return $file_contents;
				} else {
					return false;
				}
			} else {
				$data = parse_url($path);
				if (!$data['host'] || $data['scheme'] != "http") {
					return false;
				}
		
				$f = fsockopen($data['host'], ($data['port']) ? $data['port'] : 80, $e1, $e2, 3);
				if (!$f) {
					return false;
				}
		
				$q = "GET " . $data['path'] . "?" . $data['query'] . " HTTP/1.1\r\n";
				$q .= "Host: " . $data['host'] . "\r\n";
				$q .= "Connection: close\r\n";
				$q .= "Referer: http://www.gelatocms.com/\r\n\r\n";
		
				$recv = "";
				fwrite($f, $q);
				while (!feof($f)) {
					$recv .= fread($f, 1024);
				}
		
				$request = $q;
				$response = substr($recv, 0, strpos($recv, "\r\n\r\n"));
				$body = substr($recv, strpos($recv, "\r\n\r\n") + 4);
		
				if (preg_match('/http\/1\\.[0|1] ([0-9]{3})/i', $response, $res)) {
					if ($res[1][0] != "2") {
						return false;
					}
				} else {
					return false;
				}
		
				if (preg_match('/transfer-encoding:\s*chunked/i', $response)) {
					$tmp_body = $body;
					$new = "";
					$exit = false;
					while (!$exit) {
						if (preg_match('/^([0-9a-f]+).*?\r\n/i', $tmp_body, $res)) {
							$len = hexdec($res[1]);
							if ($len == "0") {
								$exit = true;
								break;
							}
							$new .= substr($tmp_body, strlen($res[0]), $len);
							$tmp_body = substr($tmp_body, strlen($res[0]) + $len + strlen("\r\n"));
						} else {
							$exit = true;
						}
					}
					$body = $new;
				}	
				return $body;
			}
		}
	}
	
	function get_curl_version() {
		$curl = 0;
		if (is_array(curl_version())) {
			$curl = curl_version();
			$curl = $curl['version'];
		} else {
			$curl = curl_version();
			$curl = explode(' ', $curl);
			$curl = explode('/', $curl[0]);
			$curl = $curl[1];
		}
		return $curl;
	} 	
?>