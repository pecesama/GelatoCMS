<?php
function initIdioma($lang = "en") {
	global $locale, $l10n;

	$input = new FileReader(dirname(__FILE__)."/../idiomas/". $locale ."/lang.mo");
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
