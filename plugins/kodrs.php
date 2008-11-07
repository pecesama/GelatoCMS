<?php

class kodrs extends plugins {	
	
	function kodrs() {
		if (!defined('GESHI_VERSION')) {
        	require_once("geshi/geshi.php");
		}
		$this->addAction('post_content', 'source_code_beautifier');
	}
	
	function info()
	{
		return array(
			'name' => 'kodrs',
			'version' => '0.1',
			'url' => 'http://plugins.gelatocms.com/kodrs/',
			'author' => 'Pedro Santana',
			'authorurl' => 'http://www.pecesama.net/',
			'license' => 'MIT License',
			'description' => 'Geshify your source codes',
		);
	}

	function source_code_beautifier() {
		global $rows;
		if(count($rows)>0){
			foreach($rows as $key=>$post){
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
	}
	
	function replace_with_geshi($matches) {		
		$lang = strtolower($matches[1])	;		
		$code = trim($matches[2]);
		$geshi = new geshi($code, (isset($lang)) ? $lang : "");    
		$geshi->enable_classes(false);
		$geshi->set_overall_id('geshi_code');
		return @$geshi->parse_code();
	}	
	
}
?>