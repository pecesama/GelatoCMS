<?php
/*
Plugin Name: kodrs
Plugin URI: http://plugins.gelatocms.com/kodrs/
Description: Geshify your source codes
Author: Pedro Santana
Author URI: http://www.pecesama.net/
Version: 0.1
*/ 
class kodrs extends plugins {	
	
	function kodrs() {
		if (!defined('GESHI_VERSION')) {
        	require_once("geshi/geshi.php");
		}
		$this->addAction('post_content', 'source_code_beautifier');
	}

	function source_code_beautifier() {
		global $rows;
		foreach($rows as $key=>$post){
			// Si no es tipo 'post' entonces no tiene 'Body' :)
			if($post["postType"]=="post"){
				$text = $rows[$key]['Body'];		
				$result = preg_replace_callback("/<code\s+.*lang\s*=\"(.*)\">(.*)<\/code>/siU", 
					array('kodrs', 'replace_with_geshi'), 
					$text
				);
				$rows[$key]['Body'] = $result;
			}
		}
	}
	
	static function replace_with_geshi($matches) {		
		$lang = strtolower($matches[1])	;		
		$code = trim($matches[2]);
		$geshi = new geshi($code, (isset($lang)) ? $lang : "");    
		$geshi->enable_classes(false);
		$geshi->set_overall_id('geshi_code');
		return @$geshi->parse_code();
	}	
	
}
?>