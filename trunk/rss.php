<?
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?
	header("Content-type: text/xml; charset=utf-8");
		
	require_once("classes/configuration.class.php");
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
		<language>EN</language>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		<generator>gelato CMS</generator>
		<image>
			<url><?=$conf->urlGelato;?>/images/information.png</url>
			<title><?=htmlspecialchars(replaceAccents($conf->description));?></title>
			<link><?=$conf->urlGelato;?></link>
		</image>
<?
		while($register = mysql_fetch_array($rs)) {
			$tit = ($register["title"]=="") ? htmlspecialchars($register["url"]) : htmlspecialchars($register["title"]);
			$desc = htmlspecialchars($register["description"]);
			$url = htmlspecialchars($conf->urlGelato."/index.php/post/".$register["id_post"]."/");
			$formatedDate = gmdate("D, d M Y H:i:s \G\M\T", strtotime($register["date"]));
			?>

			<item>
				<title><?=$tit;?></title>
				<link><?=$url;?></link>
				<description><?=$desc;?></description>
				<pubDate><?=$formatedDate;?></pubDate>
				<category>system:unfiled</category>
				<guid isPermaLink="true"><?=$conf->urlGelato."/index.php/post/".$register["id_post"]."/";?></guid>
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
?>