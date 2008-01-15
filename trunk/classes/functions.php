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
	function version() {
		return "1.0";
	}
	
	function codeName() {
		return "vaniglia RC1";
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
	
	function getGoEarCode($songUrl) {
		$pos = strpos($songUrl, "?v=");
		$lon = strlen($songUrl);
		$str = substr($songUrl, $pos + 3, $lon);
		return $str;
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
		$fileName = "../uploads/".sanitizeName(getFileName($remoteFileName));		
		$str = _file_get_contents($remoteFileName);
		if (!$handle = fopen($fileName, 'w')) {
			//die("no se abrio de escritura");
			return false;
		}
		
		if (fwrite($handle, $str) === FALSE) {
			//die("no se escribio");
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
	
	function isDailymotionVideo($videoUrl) {
		if (beginsWith($videoUrl, "http://www.dailymotion.com/video/") || beginsWith($videoUrl, "http://dailymotion.com/video/"))
			return true;
		else
			return false;
	}

	function getDailymotionVideoUrl($videoUrl) {
		$params = explode("video/", $videoUrl);
		$params2 = explode("_",$params[1]);
		return $params2[0];
	}
	
	function isVideo($url) {
		if (isYoutubeVideo($url)) { return true; }
		elseif (isVimeoVideo($url)) { return true; }
		elseif (isDailymotionVideo($url)) { return true; }
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

	function sanitizeName($name) {
		$name = preg_replace('/[\'"]/', '', $name);
		$name = preg_replace('/[^a-zA-Z0-9]+/', '-', $name);
		$name = trim($name, '-');
		$name = strtolower($name);
		//HACK: We need to rework the regular expression to allow the dot
		$ext = substr($name, strlen($name)-3, strlen($name));
		$body = substr($name, 0, strlen($name)-4);
		
		$name = $body.".".$ext;
		
		return $name;
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
		
				$f = @fsockopen($data['host'], ($data['port']) ? $data['port'] : 80, $e1, $e2, 3);
				if (!$f) {
					return false;
				}

				$q = "GET " . $data['path'] . (isset($data['query'])?'?'.$data['query']:'') . " HTTP/1.1\r\n";
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
	
	function transform_offset($offset){
		$sp = strpos($offset , ".")? explode("." , $offset) : false;
		if(is_array($sp)){
			$minutes = strval($sp[1]);
			$off_h = $sp[0]*3600;
			$off_m = (($minutes*60)/100)*60;
			$off = $off_h+$off_m;
		} else {
			$off = ($offset*3600);
		}
		return $off;
	}
	
	function getLangs() {
 		$langs_dir = "languages";
 		$dirs = array();
		$path = getcwd();
 		$dir = (substr(PHP_OS, 0, 3) == 'WIN') ? $path."\\".$langs_dir : $path."/".$langs_dir;
		$dir = str_replace("admin\\", "", $dir);
		$dir = str_replace("admin/", "", $dir);
 		$i=0;
		$cls_lang_dir = @ dir($dir);
		while (($directory = $cls_lang_dir->read()) !== false) {
			if($directory != "." && $directory != "..") {
				
				$dir2 = (substr(PHP_OS, 0, 3) == 'WIN') ? $path."\\".$langs_dir."\\".$directory : $path."/".$langs_dir."/".$directory;
				$dir2 = str_replace("admin\\", "", $dir2);
				$dir2 = str_replace("admin/", "", $dir2);
				if(is_dir($dir2)){
					$cls_lang_dir2 = @ dir($dir2);
					while (($directory2 = $cls_lang_dir2->read()) !== false) {
						if($directory2 != "." && $directory2 != "..") {
							if (preg_match('|^\.+$|', $directory2)){
								continue;
							}
							if (preg_match('|\.mo$|', $directory2)){
								if(!in_array($directory2,$dirs)){
									$dirs[$i]=trim($directory);
									$i++;
								}
							}
						}
					}
				}
			}
 		}
		$dirs = array_unique($dirs);
 		return $dirs;
 	}
	
	function sql_escape($value) {
	    if(get_magic_quotes_gpc()) {
	          $value = stripslashes($value);
	    }
	    if( function_exists("mysql_real_escape_string")) {
	          $value = mysql_real_escape_string($value);
	    } else {
	          $value = addslashes($value);
	    }
	    return $value;
	}
	
	function removeBadTags($source) {
		$validTags ='<p><ol><ul><li><a><abbr><acronym><blockquote><code><pre><em><i><strike><s><strong><b><br><span><div><img>';
		$source = strip_tags($source, $validTags);
		return preg_replace('/<(.*?)>/ie', "'<'.removeBadAtributes('\\1').'>'", $source);
	}
	
	function removeBadAtributes($sourceTag) {
		$badAtributes = 'javascript:|onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup';
		$sourceTag = stripslashes($sourceTag);
		$sourceTag = preg_replace("/$badAtributes/i", "niceTry", $sourceTag);
		return $sourceTag;
	}
	
	function type2Text($number) {
		$tmpStr = "";
		switch ($number) {
			case "1":
				$tmpStr = "post";
				break;
			case "2":
				$tmpStr = "photo";							   
				break;
			case "3":
				$tmpStr = "quote";
				break;
			case "4":
				$tmpStr = "url";
				break;
			case "5":
				$tmpStr = "conversation";
				break;
			case "6":
				$tmpStr = "video";
				break;
			case "7":
				$tmpStr = "mp3";
				break;								
		}
		return $tmpStr;
	}
	
	function type2Number($string) {
		$tmpStr = "";
		switch ($string) {
			case "post":
				$tmpStr = "1";
				break;
			case "photo":
				$tmpStr = "2";							   
				break;
			case "quote":
				$tmpStr = "3";
				break;
			case "url":
				$tmpStr = "4";
				break;
			case "conversation":
				$tmpStr = "5";
				break;
			case "video":
				$tmpStr = "6";
				break;
			case "mp3":
				$tmpStr = "7";
				break;								
		}
		return $tmpStr;
	}
?>