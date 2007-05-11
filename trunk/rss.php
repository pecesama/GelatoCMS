<?
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?
	header("Content-type: text/xml; charset=utf-8");
		
	require(dirname(__FILE__)."/config.php");
	include("classes/configuration.class.php");
	$conf = new configuration();
	
	echo "<?xml version=\"1.0\""." encoding=\"UTF-8\"?>\n";
	
	include("classes/gelato.class.php");
	$tumble = new gelato();
	$rs = $tumble->getPosts("20");
	if ($tumble->contarRegistros()>0) {		
?>

<rss version="2.0">
	<channel>
		<title><?=htmlspecialchars(replaceAccents($conf->title));?></title>
		<link><?=$conf->urlGelato;?></link>
		<description><?=htmlspecialchars(replaceAccents($conf->description));?></description>
		<generator>gelato CMS</generator>
		<image>
			<url><?=$conf->urlGelato;?>/images/information.png</url>
			<title><?=htmlspecialchars(replaceAccents($conf->description));?></title>
			<link><?=$conf->urlGelato;?></link>
		</image>
<?
		while($register = mysql_fetch_array($rs)) {
			switch ($register["type"]) {
				case "1":
					$tit = ($register["title"]=="") ? htmlspecialchars(strip_tags($register["description"])) : htmlspecialchars($register["title"]);
					$desc = htmlspecialchars($register["description"]);
					break;
				case "2":
					$tit = ($register["description"]=="") ? "Photo" : htmlspecialchars(strip_tags($register["description"]));
					$desc = "<img src=\"".$register["url"]."\"/>";
					break;
				case "3":
					$tit = "\"".htmlspecialchars(strip_tags($register["description"]))."\"";
					$tmpStr = ($register["title"]!="") ? "<br /><br /> - <em>".htmlspecialchars($register["title"])."</em>" : "";
					$desc = "\"".htmlspecialchars($register["description"])."\"".$tmpStr;
					break;
				case "4":
					$tit = ($register["title"]=="") ? htmlspecialchars($register["url"]) : htmlspecialchars($register["title"]);
					$tmpStr = ($register["description"]!="") ? "<br /><br /> - <em>".htmlspecialchars($register["description"])."</em>" : "";
					$desc = "<a href=\"".htmlspecialchars($register["url"])."\">".$tit."</a>".$tmpStr;
					break;
				case "5":
					$lines = explode("\n", $register["description"]);
					$line = htmlspecialchars($lines[0]);
					$tit = ($register["title"]=="") ? $line : htmlspecialchars($register["title"]);
					$desc = $tumble->formatConversation(htmlspecialchars($register["description"]));
					break;
				case "6":
					$tit = ($register["description"]=="") ? "Video" : htmlspecialchars(strip_tags($register["description"]));
					$desc = $tumble->getVideoPlayer(htmlspecialchars($register["url"]));
					break;
				case "7":
					$tit = ($register["description"]=="") ? "MP3" : htmlspecialchars(strip_tags($register["description"]));
					$desc = $tumble->getMp3Player(htmlspecialchars($register["url"]));
					break;
			}
			$url = htmlspecialchars($conf->urlGelato."/index.php/post/".$register["id_post"]."/");
			$formatedDate = gmdate("D, d M Y H:i:s \G\M\T", strtotime($register["date"]));
			?>

			<item>
				<title><?=replaceAccentsWithAmp($tit);?></title>
				<description><![CDATA[<?=replaceAccentsWithAmp($desc);?>]]></description>
				<link><?=$url;?></link>
				<guid isPermaLink="true"><?=$conf->urlGelato."/index.php/post/".$register["id_post"]."/";?></guid>				
				<pubDate><?=$formatedDate;?></pubDate>				
			</item>

<?		
		}
	}
?>
	</channel>
</rss>

<?
	function replaceAccents($texto="") {
		$texto = str_replace("&Aacute;","A", $texto);
		$texto = str_replace("&Eacute;","E", $texto);
		$texto = str_replace("&Iacute;","I", $texto);
		$texto = str_replace("&Oacute;","O", $texto);
		$texto = str_replace("&Uacute;","U", $texto);
		$texto = str_replace("&aacute;","a", $texto);
		$texto = str_replace("&eacute;","e", $texto);
		$texto = str_replace("&iacute;","i", $texto);
		$texto = str_replace("&oacute;","o", $texto);
		$texto = str_replace("&uacute;","u", $texto);
		return $texto;
	}
	
	function replaceAccentsWithAmp($texto="") {
		$texto = str_replace("&amp;Aacute;","A", $texto);
		$texto = str_replace("&amp;Eacute;","E", $texto);
		$texto = str_replace("&amp;Iacute;","I", $texto);
		$texto = str_replace("&amp;Oacute;","O", $texto);
		$texto = str_replace("&amp;Uacute;","U", $texto);
		$texto = str_replace("&amp;aacute;","a", $texto);
		$texto = str_replace("&amp;eacute;","e", $texto);
		$texto = str_replace("&amp;iacute;","i", $texto);
		$texto = str_replace("&amp;oacute;","o", $texto);
		$texto = str_replace("&amp;uacute;","u", $texto);
		$texto = str_replace("&amp;#39;"," ", $texto);
		return $texto;
	}
?>