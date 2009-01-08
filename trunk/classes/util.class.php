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
class util {
	
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
		if (util::endsWith($fileUrl, ".mp3")) {
			return true;
		} else {
			return false;
		}
	}

	function getMP3File($remoteFileName) {
		if (util::isMP3($remoteFileName)) {
			if (util::getFile($remoteFileName)) {
				return true;
			} else {
				return false;
			}
		} elseif (util::isGoEar($remoteFileName)) {
			return true;
		} elseif (util::isOdeo($remoteFileName)) {
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
		if (util::beginsWith($songUrl, "http://www.goear.com/listen.php?v=") || util::beginsWith($songUrl, "http://goear.com/listen.php?v="))
			return true;
		else
			return false;
	}

	function isOdeo($songUrl){
		if (util::beginsWith($songUrl, "http://odeo.com/audio/") || util::beginsWith($songUrl, "http://www.odeo.com/audio/"))
			return true;
		else
			return false;
	}

	function getOdeoCode($songUrl) {
		$params = explode("audio/", $songUrl);
		$params2 = explode("/",$params[1]);
		return $params2[0];
	}

	function isImageFile($photoUrl) {
		if (util::endsWith($photoUrl, ".jpg")) { return true; }
		elseif (util::endsWith($photoUrl, ".gif")) { return true; }
		elseif (util::endsWith($photoUrl, ".png")) { return true; }
		else { return false; }
	}

	function getPhotoFile($remoteFileName) {
		if (util::isImageFile($remoteFileName)) {
			if (util::getFile($remoteFileName)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function getFile($remoteFileName) {
		$fileName = "../uploads/".util::sanitizeName(util::getFileName($remoteFileName));
		$str = util::_file_get_contents($remoteFileName);
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
		if (util::beginsWith($videoUrl, "http://vimeo.com/") || util::beginsWith($videoUrl, "http://www.vimeo.com/"))
			return true;
		else
			return false;
	}

	function getVimeoVideoUrl($videoUrl) {
		if(substr_count($videoUrl,"clip:")==1)
			return array_pop(explode("clip:",$videoUrl));
		else
			return array_pop(explode("/",$videoUrl));
	}

	function isYoutubeVideo($videoUrl) {
		$url = explode("?", $videoUrl);
		if((util::beginsWith($url[0], "http://") && util::endsWith($url[0], ".youtube.com/watch")) || util::beginsWith($url[0], "http://youtube.com/watch"))
			return true;
		else
			return false;
	}
	
	function getYoutubeVideoUrl($videoUrl) {
		$params = explode("?v=", $videoUrl);
		$params2 = explode("&",$params[1]);
		return $params2[0];
	}
	
	function isYahooVideo($videoUrl){
		if (util::beginsWith($videoUrl, "http://video.yahoo.com/watch/") || util::beginsWith($videoUrl, "http://www.video.yahoo.com/watch/"))
			return true;
		else
			return false;
	}

	function getYahooVideoCode($videoUrl){
		$params = explode("http://video.yahoo.com/watch/", $videoUrl);
		$params2 = explode("/",$params[1]);
		$values[0] = $params2[0];
		$values[1] = $params2[1];
		return $values;
	}

	function isGoogleVideoUrl($videoUrl){
		if (util::beginsWith($videoUrl, "http://video.google.com/videoplay?")){
			return true;
		} else {
			return false;
		}
	}

	function getGoogleVideoCode($videoUrl){
		$params = explode("?docid=", $videoUrl);
		$params2 = explode("&",$params[1]);
		return $params2[0];
	}
	
	function isMTVVideoUrl($videoUrl){
		if (util::beginsWith($videoUrl, "http://www.mtvmusic.com/video/?id=") || util::beginsWith($videoUrl, "http://mtvmusic.com/video/?id=")){
			return true;
		} else {
			return false;
		}
	}

	function getMTVVideoCode($videoUrl){
		$params = explode("?id=", $videoUrl);
		$params2 = explode("&",$params[1]);
		return $params2[0];
	}

	function isDailymotionVideo($videoUrl) {
		if (util::beginsWith($videoUrl, "http://www.dailymotion.com/video/") || util::beginsWith($videoUrl, "http://dailymotion.com/video/"))
			return true;
		else
			return false;
	}

	function getDailymotionVideoUrl($videoUrl) {
		$params = explode("video/", $videoUrl);
		$params2 = explode("_",$params[1]);
		return $params2[0];
	}

	function isSlideSharePresentation($videoUrl) {
		if (util::beginsWith($videoUrl, "[slideshare id="))
			return true;
		else
			return false;
	}

	function getSlideSharePresentationCode($videoUrl) {
		$videoUrl = str_replace("[slideshare id=", "", $videoUrl);
		$videoUrl = str_replace("&doc=", " ", $videoUrl);
		$videoUrl = str_replace("&w=", " ", $videoUrl);
		return explode(" ",$videoUrl);
	}

	function isVideo($url) {
		if (util::isYoutubeVideo($url)) { return true; }
		elseif (util::isVimeoVideo($url)) { return true; }
		elseif (util::isDailymotionVideo($url)) { return true; }
		elseif (util::isYahooVideo($url)) { return true; }
		elseif (util::isSlideSharePresentation($url)) { return true; }
		elseif (util::isGoogleVideoUrl($url)) { return true; }
		elseif (util::isMTVVideoUrl($url)) { return true; }
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
 			if($filename != "." && $filename != ".." && $filename != ".svn") {
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
			if (extension_loaded('curl') && version_compare(util::get_curl_version(), '7.10.5', '>=')) {
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
									$dirs[$directory]=util::displayLanguage(trim($directory));
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

	function removeBadTags($source,$secure=false) {
		if($secure){
			$validTags ='<blockquote><code><em><i><strong><b><a>';
		} else {
			$validTags ='<p><ol><ul><li><a><abbr><acronym><blockquote><code><pre><em><i><strong><b><del><br><span><div><img>';
		}
		$source = strip_tags($source, $validTags);
		return preg_replace('/<(.*?)>/ie', "'<'.util::removeBadAtributes('\\1').'>'", $source);
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

	function trimString($string, $len=50) {
		if($len>strlen(strip_tags($string)) or $len<1 or strlen(strip_tags($string))<1)
			return strip_tags($string);
		$string = strip_tags($string);

		return (strpos($string," ",$len))?substr_replace($string, "...", $len):$string;
	}
	
	function displayLanguage($lang){
		$out = "";
		if(strpos($lang, '-')==2){
			$lang = explode('-',$lang);
		}
		$language = is_array($lang)? $lang[0] : $lang;
		switch($language){
			case 'af': $out = __('Afrikaans'); break;
			case 'sq': $out = __('Albanian'); break;
			case 'ar': $out = __('Arabic'); break;
			case 'eu': $out = __('Basque'); break;
			case 'bg': $out = __('Bulgarian'); break;
			case 'be': $out = __('Belarusian'); break;
			case 'ca': $out = __('Catalan'); break;
			case 'zh': $out = __('Chinese'); break;
			case 'hr': $out = __('Croatian'); break;
			case 'cs': $out = __('Czech'); break;
			case 'da': $out = __('Danish'); break;
			case 'nl': $out = __('Dutch'); break;
			case 'en': $out = __('English'); break;
			case 'et': $out = __('Estonian'); break;
			case 'fo': $out = __('Faeroese'); break;
			case 'fa': $out = __('Farsi'); break;
			case 'fi': $out = __('Finnish'); break;
			case 'fr': $out = __('French'); break;
			case 'gd': $out = __('Gaelic'); break;
			case 'de': $out = __('German'); break;
			case 'el': $out = __('Greek'); break;
			case 'he': $out = __('Hebrew'); break;
			case 'hi': $out = __('Hindi'); break;
			case 'hu': $out = __('Hungarian'); break;
			case 'is': $out = __('Icelandic'); break;
			case 'id': $out = __('Indonesian'); break;
			case 'it': $out = __('Italian'); break;
			case 'ja': $out = __('Japanese'); break;
			case 'ko': $out = __('Korean'); break;
			case 'lv': $out = __('Latvian'); break;
			case 'lt': $out = __('Lithuanian'); break;
			case 'mk': $out = __('Macedonian'); break;
			case 'ms': $out = __('Malaysian'); break;
			case 'mt': $out = __('Maltese'); break;
			case 'no': $out = __('Norwegian'); break;
			case 'pl': $out = __('Polish'); break;
			case 'pt': $out = __('Portuguese'); break;
			case 'rm': $out = __('Rhaeto-Romanic'); break;
			case 'ro': $out = __('Romanian'); break;
			case 'ru': $out = __('Russian'); break;
			case 'sz': $out = __('Sami'); break;
			case 'sr': $out = __('Serbian'); break;
			case 'sk': $out = __('Slovak'); break;
			case 'sl': $out = __('Slovenian'); break;
			case 'sb': $out = __('Sorbian'); break;
			case 'es': $out = __('Spanish'); break;
			case 'sx': $out = __('Sutu'); break;
			case 'sv': $out = __('Swedish'); break;
			case 'th': $out = __('Thai'); break;
			case 'ts': $out = __('Tsonga'); break;
			case 'tn': $out = __('Tswana'); break;
			case 'tr': $out = __('Turkish'); break;
			case 'uk': $out = __('Ukrainian'); break;
			case 'ur': $out = __('Urdu'); break;
			case 've': $out = __('Venda'); break;
			case 'vi': $out = __('Vietnamese'); break;
			case 'xh': $out = __('Xhosa'); break;
			case 'ji': $out = __('Yiddish'); break;
			case 'zu': $out = __('Zulu'); break;
			default: $out = $language;
		}
		if(is_array($lang)){
			$country = strtolower($lang[1]);
			switch($country){
				//Aca una lista con los paises. No se como tendría que hacer para hacer insensible a mayusculas o minusculas: es-MX o es-mx ¿?
				case 'sa': $out .= " (". __('Saudi Arabia'). ")"; break;
				case 'iq': $out .= " (". __('Iraq'). ")"; break;
				case 'eg': $out .= " (". __('Egypt'). ")"; break;
				case 'ly': $out .= " (". __('Libya'). ")"; break;
				case 'dz': $out .= " (". __('Algeria'). ")"; break;
				case 'ma': $out .= " (". __('Morocco'). ")"; break;
				case 'tn': $out .= " (". __('Tunisia'). ")"; break;
				case 'om': $out .= " (". __('Oman'). ")"; break;
				case 'ye': $out .= " (". __('Yemen'). ")"; break;
				case 'sy': $out .= " (". __('Syria'). ")"; break;
				case 'jo': $out .= " (". __('Jordan'). ")"; break;
				case 'lb': $out .= " (". __('Lebanon'). ")"; break;
				case 'kw': $out .= " (". __('Kuwait'). ")"; break;
				case 'ae': $out .= " (". __('U.A.E.'). ")"; break;
				case 'bh': $out .= " (". __('Bahrain'). ")"; break;
				case 'qa': $out .= " (". __('Qatar'). ")"; break;
				case 'tw': $out .= " (". __('Taiwan'). ")"; break;
				case 'cn': $out .= " (". __('PRC'). ")"; break;
				case 'hk': $out .= " (". __('Hong Kong SAR'). ")"; break;
				case 'sg': $out .= " (". __('Singapore'). ")"; break;
				case 'be': $out .= " (". __('Belgium'). ")"; break;
				case 'us': $out .= " (". __('United States'). ")"; break;
				case 'gb': $out .= " (". __('United Kingdom'). ")"; break;
				case 'au': $out .= " (". __('Australia'). ")"; break;
				case 'ca': $out .= " (". __('Canada'). ")"; break;
				case 'nz': $out .= " (". __('New Zealand'). ")"; break;
				case 'ie': $out .= " (". __('Ireland'). ")"; break;
				case 'za': $out .= " (". __('South Africa'). ")"; break;
				case 'jm': $out .= " (". __('Jamaica'). ")"; break;
				case 'bz': $out .= " (". __('Belize'). ")"; break;
				case 'tt': $out .= " (". __('Trinidad'). ")"; break;
				case 'ch': $out .= " (". __('Switzerland'). ")"; break;
				case 'lu': $out .= " (". __('Luxembourg'). ")"; break;
				case 'at': $out .= " (". __('Austria'). ")"; break;
				case 'li': $out .= " (". __('Liechtenstein'). ")"; break;
				case 'br': $out .= " (". __('Brazil'). ")"; break;
				case 'pt': $out .= " (". __('Portugal'). ")"; break;
				case 'mo': $out .= " (". __('Republic of Moldova'). ")"; break;
				case 'sz': $out .= " (". __('Lappish'). ")"; break;
				case 'mx': $out .= " (". __('Mexico'). ")"; break;
				case 'gt': $out .= " (". __('Guatemala'). ")"; break;
				case 'cr': $out .= " (". __('Costa Rica'). ")"; break;
				case 'pa': $out .= " (". __('Panama'). ")"; break;
				case 'do': $out .= " (". __('Dominican Republic'). ")"; break;
				case 've': $out .= " (". __('Venezuela'). ")"; break;
				case 'co': $out .= " (". __('Colombia'). ")"; break;
				case 'pe': $out .= " (". __('Peru'). ")"; break;
				case 'ar': $out .= " (". __('Argentina'). ")"; break;
				case 'ec': $out .= " (". __('Ecuador'). ")"; break;
				case 'cl': $out .= " (". __('Chile'). ")"; break;
				case 'uy': $out .= " (". __('Uruguay'). ")"; break;
				case 'py': $out .= " (". __('Paraguay'). ")"; break;
				case 'bo': $out .= " (". __('Bolivia'). ")"; break;
				case 'sv': $out .= " (". __('El Salvador'). ")"; break;
				case 'hn': $out .= " (". __('Honduras'). ")"; break;
				case 'ni': $out .= " (". __('Nicaragua'). ")"; break;
				case 'pr': $out .= " (". __('Puerto Rico'). ")"; break;
				case 'fi': $out .= " (". __('Finland'). ")"; break;
				default: $out .= "(".$country.")";
			}
		}
		return $out;
	}

	function init_plugins() {        
		global $conf;
        
		$actives = json_decode($conf->active_plugins,1);
		$actives = $actives[1];
		
        foreach ($actives as $index => $plugin) {
            if (!file_exists(Absolute_Path."plugins/".$plugin)) {
                unset($actives[$index]);
                continue;
            } else {
				require_once(Absolute_Path.'plugins/'.$plugin);
			}            
			if (!class_exists($index)) {
                continue;
			}
			$GLOBALS['plugins::$instances'][$index] = new $index;
        }
	}
}
?>