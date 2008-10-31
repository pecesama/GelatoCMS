<?php

class hola extends plugins {
	
	function hola() {
		$this->addAction('add_post', 'saluda');		
	}
	
	function saluda() {
		echo "Hola mundo desde plugin en gelato<br />";
	}
	
}

?>
