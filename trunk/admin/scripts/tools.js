//<!--
function init_tynimce(el1, el2) {
	tinyMCE.init({
		width : "100%",
		mode : "textareas",
		theme : "simple"
	});
	
	edCanvas = document.getElementById(el1);
		// This code is meant to allow tabbing from Title to Description (TinyMCE).
	if ( tinyMCE.isMSIE )
		document.getElementById(el2).onkeydown = function (e)
			{
				e = e ? e : window.event;
				if (e.keyCode == 9 && !e.shiftKey && !e.controlKey && !e.altKey) {
					var i = tinyMCE.selectedInstance;
					if(typeof i ==  'undefined')
						return true;
									tinyMCE.execCommand("mceStartTyping");
					this.blur();
					i.contentWindow.focus();
					e.returnValue = false;
					return false;
				}
			}
	else
		document.getElementById(el2).onkeypress = function (e)
			{
				e = e ? e : window.event;
				if (e.keyCode == 9 && !e.shiftKey && !e.controlKey && !e.altKey) {
					var i = tinyMCE.selectedInstance;
					if(typeof i ==  'undefined')
						return true;
									tinyMCE.execCommand("mceStartTyping");
					this.blur();
					i.contentWindow.focus();
					e.returnValue = false;
					return false;
				}
			}
	}
//-->