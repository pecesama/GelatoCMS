//<!-- 

function validateFrmAddUser() {
	if ($('login').value == "") {
	   alert("The username field cannot be left blank.");
	   document.frm_add.login.select();	
	   return false;
	}
	if ($('password').value == "") {
	   alert("The password field cannot be left blank.");
	   document.frm_add.password.select();	
	   return false;
	}	
	if ($('password').value != $('repass').value) {
	   alert("The password must match,\nplease verify them.");
	   document.frm_add.password.focus();	
	   return false;
	}		
	return true;
}

function verifyExistingUser() {
	$('div-process').style.display="block";
	el = $('target');
	el.style.display="block";										
	var path = 'ajax.php?action=verify&login='+$('login').value;
	new Ajax(path, {
		onComplete:function(e) {						
			el.setHTML(e);
			$('div-process').style.display="none";
		}
	}).request();
	return false;
}

function selectFeedType(feed_url,username){
		var source = $('source');
		if(source.selectedIndex != 0){
			$('import_as').style.display="none";
			$('url_label').firstChild.nodeValue = source[source.selectedIndex].firstChild.nodeValue + username;
		}else{
			$('import_as').style.display="block";
			$('url_label').firstChild.nodeValue = feed_url;
		}
}

//-->