<?php
if(!defined('entry') || !entry) die('Not a valid page'); 
/* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */

class plantillas {

	var $plantilla;
	var $plantilla_cargada;
	var $texto_plantilla;
	
	var $antesBloque;
	var $bloque;
	var $despuesBloque;
	var $bloqueFinal="";

	function plantillas($plantilla) {		
		$this->plantilla = ($plantilla == "") ? "tumblr" : $plantilla;
	}	

	function cargarPlantilla($entrada, $salida, $plantilla_usar) {		
		$plantilla_usar = "themes/".$this->plantilla."/".$plantilla_usar.".htm";
		
		if (!file_exists($plantilla_usar)) {
			die("No se encuentra la plantilla :".$plantilla_usar);
		} else {		
			if(!$fd = fopen($plantilla_usar, "r")) {
				die("Error en la plantilla");
			} else {
				$salida_xhtml = fread($fd, filesize ($plantilla_usar));
				fclose ($fd);
				$salida_xhtml = stripslashes($salida_xhtml);
				$this->texto_plantilla = $salida_xhtml;
				for ($i = 0; $i < count($entrada); $i++) {
					$salida_xhtml = str_replace($entrada[$i], $salida[$i], $salida_xhtml);
				}
				$this->plantilla_cargada = $salida_xhtml;
			} 
		}
	}	
		
	function precargarPlantillaConBloque($entrada, $salida, $plantilla_usar, $nombreBloque) {		
		$plantilla_usar = "themes/".$this->plantilla."/".$plantilla_usar.".htm";
		
		if (!file_exists($plantilla_usar)) {
			die("No se encuentra la plantilla :".$plantilla_usar);
		} else {		
			if(!$fd = fopen($plantilla_usar, "r")) {
				die("Error en la plantilla");
			} else {
				$salida_xhtml = fread($fd, filesize ($plantilla_usar));
				fclose ($fd);
				$salida_xhtml = stripslashes($salida_xhtml);
				$this->texto_plantilla = $salida_xhtml;
				
				$this->cargaAntesBloque($nombreBloque);
				$this->cargaDespuesBloque($nombreBloque);
				
				$this->antesBloque = $this->procesaBloque($entrada, $salida, $this->antesBloque);
				$this->despuesBloque = $this->procesaBloque($entrada, $salida, $this->despuesBloque);
			} 
		}
	}	
	
	function cargarPlantillaConBloque($entrada, $salida, $plantilla_usar, $nombreBloque) {		
		$plantilla_usar = "themes/".$this->plantilla."/".$plantilla_usar.".htm";
		
		if (!file_exists($plantilla_usar)) {
			die("No se encuentra la plantilla :".$plantilla_usar);
		} else {		
			if(!$fd = fopen($plantilla_usar, "r")) {
				die("Error en la plantilla");
			} else {
				$salida_xhtml = fread($fd, filesize ($plantilla_usar));
				fclose ($fd);
				$salida_xhtml = stripslashes($salida_xhtml);
				$this->texto_plantilla = $salida_xhtml;
				
				$this->cargaBloque($nombreBloque);
				
				$this->bloqueFinal .= $this->procesaBloque($entrada, $salida, $this->bloque);
			} 
		}
	}	
	
	function procesaBloque($entrada, $salida, $bloque) {
		for ($i = 0; $i < count($entrada); $i++) {
			$bloque = str_replace($entrada[$i], $salida[$i], $bloque);
		}
		return $bloque;
	}
	
	function cargaBloque($nombreBloque) {
		$inicioBloque = "[bloque: ".$nombreBloque."]";
		$finBloque = "[/bloque: ".$nombreBloque."]";
		$ini = strpos($this->texto_plantilla,$inicioBloque)+strlen($inicioBloque);
		$fin = strpos($this->texto_plantilla,$finBloque);
		$this->bloque = substr($this->texto_plantilla,$ini,($fin-$ini));
	}
	
	function cargaAntesBloque($nombreBloque) {
		$inicioBloque = "[bloque: ".$nombreBloque."]";
		$ini = strpos($this->texto_plantilla,$inicioBloque);
		$this->antesBloque = substr($this->texto_plantilla,0,$ini);
	}
	
	function cargaDespuesBloque($nombreBloque) {
		$finBloque = "[/bloque: ".$nombreBloque."]";
		$fin = strpos($this->texto_plantilla,$finBloque);
		$this->despuesBloque = substr($this->texto_plantilla,($fin+strlen($finBloque)),strlen($this->texto_plantilla));
	}
	
	function mostrarPlantillaConBloque() {
		echo $this->antesBloque.$this->bloqueFinal.$this->despuesBloque;
	}
	
	function mostrarPlantilla() {
		echo $this->plantilla_cargada;
	}
	
	function renderizaEtiqueta($texto="", $etiquetaHtml="p", $claseCss="") {
		echo "<".$etiquetaHtml." class=\"".$claseCss."\">".$texto."</".$etiquetaHtml.">";
	}
	
	function renderizaTexto($texto="") {
		echo $texto;
	}
}
?>