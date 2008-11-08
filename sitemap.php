<?php
if(!defined('entry')) define('entry',true);
/* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */
?>
<?php
	require('entry.php');	
	global $conf, $tumble, $db;
	
	// Code modified from the one at http://paste.ubuntu-nl.org/44548/
	header("Content-type: text/xml; charset=utf-8");
	$isFeed = true;
	
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"".$conf->urlGelato."/sitemap.xsl\"?>\n";
	
	$rs = $tumble->getPosts(1);	
?>
<!-- generator="gelato CMS" -->
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">	
	<url>
		<loc><?php echo $conf->urlGelato;?></loc>        		
<?php
		if ($db->contarRegistros()>0) {
			$register = mysql_fetch_array($rs);
			$formatedDate = gmdate("Y-m-d", strtotime($register["date"]));
			echo "\n\t<lastmod>".$formatedDate."</lastmod>";
		}
?>
		<changefreq>hourly</changefreq>
        <priority>0.9</priority>
	</url>


<?php	
	$rs = $tumble->getPosts($tumble->getPostsNumber());
	if ($db->contarRegistros()>0) {
		while($register = mysql_fetch_array($rs)) {			
			$url = $tumble->getPermalink($register["id_post"]);
			$formatedDate = gmdate("Y-m-d", strtotime($register["date"]));
?>
	<url>
		<loc><?php echo $url;?></loc>        
		<lastmod><?php echo $formatedDate;?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
	</url>
<?php
		}
	}
?>
</urlset>