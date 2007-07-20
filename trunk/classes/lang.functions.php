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
function initLang($lang = "en") {
	global $locale, $l10n;

	$input = new FileReader(dirname(__FILE__)."/../languages/". $locale ."/lang.mo");
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
