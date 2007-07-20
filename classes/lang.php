<?
$idiomas = array();
$idiomas['es_MX'] = "Espa&ntilde;ol de M&eacute;xico";
$idiomas['en'] = "English";

function esIdioma($test) {
	global $idiomas;
	if (isset($idiomas[$test]))
		return true;
	else
		return false;
}

function initIdioma($lang = "en") {
	global $locale, $l10n;

	if (!esIdioma($locale) && esIdioma($lang)) {
		$locale = $lang;
	}

	$input = new FileReader(dirname(__FILE__)."/../idiomas/". $locale ."/lang.mo");
	$l10n = new gettext_reader($input);
}

// Standard wrappers for xgettext
function __($text) {
	global $l10n;
	return $l10n->translate($text);
}

function T_ngettext($single, $plural, $number) {
	global $l10n;
	return $l10n->ngettext($single, $plural, $number);
}
?>
