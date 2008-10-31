<?php

class cinco extends plugins {
	
	function cinco() {
		$this->addAction('add_post', 'dameCinco');
	}
	
	function dameCinco() {
		echo "Este es el plugin 5<br />";
	}
	
}

?>