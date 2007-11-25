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
	// Code modified from the one at http://paste.ubuntu-nl.org/44548/
	header("Content-type: text/xml; charset=utf-8");	

	require(dirname(__FILE__)."/config.php");
	include("classes/configuration.class.php");
	include("classes/gelato.class.php");
	$tumble = new gelato();
	$conf = new configuration();	
	$isFeed = true;
	
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	
	$rs = $tumble->getPosts(1);	
?>
<!-- generator="gelato CMS" -->

<urlset 
		xmlns="http://www.google.com/schemas/sitemap/0.84"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84
        	http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">
	
	<url>
		<loc><?php echo $conf->urlGelato;?></loc>
        <priority>0.9</priority>		
<?php
		if ($tumble->contarRegistros()>0) {
			$register = mysql_fetch_array($rs);
			$formatedDate = gmdate("Y-m-d", strtotime($register["date"]));
			echo "<lastmod>".$formatedDate."</lastmod>";
		}
?>
		<changefreq>hourly</changefreq>        
	</url>


<?php	
	$rs = $tumble->getPosts($tumble->getPostsNumber());
	if ($tumble->contarRegistros()>0) {
		while($register = mysql_fetch_array($rs)) {
			$url = htmlspecialchars($conf->urlGelato."/index.php?post=".$register["id_post"]);
			$formatedDate = gmdate("Y-m-d", strtotime($register["date"]));
?>
	<url>
		<loc><?php echo $url;?></loc>
        <priority>0.7</priority>
		<lastmod><?php echo $formatedDate;?></lastmod>
        <changefreq>daily</changefreq>        
	</url>
<?php
		}
	}
?>
</urlset>