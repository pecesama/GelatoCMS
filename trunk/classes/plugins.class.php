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
	$GLOBALS['plugins::$instances'] = array(); /* class static property */
	class plugins {		
		
		function addAction($name, $function) {
	    	$plugEngine = plugin::instance();
	    	$plugEngine->actions[$name][] = array($this, $function);	    	
	    }
		
	}
?>
