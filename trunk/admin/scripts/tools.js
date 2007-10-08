function validateFrmAddUser() {
	if ($("#login").value == "") {
	   alert("The username field cannot be left blank.");
	   document.frm_add.login.select();	
	   return false;
	}
	if ($("#password").val() == "") {
	   alert("The password field cannot be left blank.");
	   document.frm_add.password.select();	
	   return false;
	}	
	if ($("#password").val() != $("#repass").val()) {
	   alert("The password must match,\nplease verify them.");
	   document.frm_add.password.focus();	
	   return false;
	}		
	return true;
}

function verifyExistingUser() {	
	$("#div-process").css({display:"block"});	
	$("#target").css({display:"block"});		
	$.ajax({
		url: "ajax.php?action=verify&login="+$("#login").val(),
		cache: false,
		success: function(html){
			$("#target").html(html);
			$("#div-process").css({display:"none"});	
			/*$("#login").css({display:"none"});*/	
		}
	});
	return false;
}

function exit(el, path) {					
	el=document.getElementById(el);		
	$(el).css({display:"block"});
	$(el).html("Processing request...");
	$.ajax({
		url: path,
		cache: false,
		success: function(html){
			$(el).html(html);
			window.location='../login.php';			
		}
	});
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

