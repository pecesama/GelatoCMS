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
	class plugin {	
		
		var $actions = array();
		var $exists = array();
	
		function call($name) {            

			if (!$this->exists($name)) {
				return false;
			}            

 			/*echo "<br />==========<br />";
			echo $name;
			echo "<br />";*/
			
			$index = 0;
			//foreach (plugins::$instances as $plugin) {
			foreach ($GLOBALS['plugins::$instances'] as $plugin) {
				if(array_key_exists($index,$this->actions[$name])){
					$action = $this->actions[$name][$index][1]; 
					if (is_callable(array($plugin, $action))) {
						//echo "ejecutar: ".$action."()<br/>";					
						$plugin->$action();
						$index++;
					}
				}
			}
        }	
        
		function exists($name) {
            if (isset($this->exists[$name])) {
                return $this->exists[$name];
			}

            //foreach (plugins::$instances as $plugin) {
			foreach ($GLOBALS['plugins::$instances'] as $plugin) {
				/*print_r(plugins::$instances);
				echo "<br />";
				print_r($plugin);
				echo "<br />";
				print_r($this->actions[$name]);
				echo "<br />";
				echo $this->actions[$name][0][1];
				echo "<br />";*/
				if(array_key_exists($name,$this->actions)){
					if (is_callable(array($plugin, $this->actions[$name][0][1]))) {
						return $this->exists[$name] = true;
					}
				}
			}

            return $this->exists[$name] = false;
        }		
        
		function & instance(){
			static $instance;
			if( !isset($instance) ) {
				$instance = new plugin();
			}
			return $instance;
        }
		
	}
?>
