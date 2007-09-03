<?php
/* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */
?>
<?php
require_once("streams.class.php");
require_once("gettext.class.php");

function initLang($lang = "en") {
	global $l10n;

	$input = new FileReader(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."languages".DIRECTORY_SEPARATOR.$lang.DIRECTORY_SEPARATOR."messages.mo");	
	$l10n = new gettext_reader($input);
}

function __($text) {
	global $l10n;
	return $l10n->translate($text);
}

function T_ngettext($single, $plural, $number) {
	global $l10n;
	return $l10n->ngettext($single, $plural, $number);
}
?>
