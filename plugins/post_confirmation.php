<?php
/*
Plugin Name: PostConfirmation
Plugin URI: http://www.gelatocms.com/plugins/postconfirmation/
Description: Add a confirmation dialog when submit the Create-post button.
Author: Victor Bracco
Author URI: http://www.vbracco.com.ar/
Version: 1.0
*/ 

class post_confirmation extends plugins {

	function post_confirmation(){
		$this->addAction('admin_includes', 'post_confirmation_script');
	}
	
	function post_confirmation_script() {
		global $admin_includes,$isEdition;
		if(!$isEdition){
			$admin_includes .= "
			<script type=\"text/javascript\">	
			$(document).ready(function(){
				$('#publish').click( function(){
					return (confirm('".__("Do you realy want to publish this?")."'));
				});
			});
			</script>
			";
		}
	}
}
?>