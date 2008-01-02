/* NicEdit - Micro Inline WYSIWYG
 * Copyright 2007 Brian Kirchoff
 *
 * NicEdit is distributed under the terms of the MIT license
 * For more information visit http://nicedit.com/
 * Do not remove this copyright message
 */
 
 var nicEditorConfig = {
	buttons : {
		'save' : {name : 'Click to Save', type : 'nicEditorSaveButton', tile : 1},
		'undo' : {name : 'Undo', command : 'undo', noActive : true, tile : 23},
		'redo' : {name : 'Redo', command : 'redo', noActive : true, tile : 24},
		'bold' : {name : 'Click to Bold', command : 'Bold', tags : ['B','STRONG'], css : {'font-weight' : 'bold'}, tile : 2},
		'italic' : {name : 'Click to Italic', command : 'Italic', tags : ['EM','I'], css : {'font-style' : 'italic'}, tile : 3},
		'underline' : {name : 'Click to Underline', command : 'Underline', tags : ['U'], css : {'text-decoration' : 'underline'}, tile : 4},
		'left' : {name : 'Left Align', command : 'justifyleft', noActive : true, tile : 8},
		'center' : {name : 'Center Align', command : 'justifycenter', noActive : true, tile : 9},
		'right' : {name : 'Right Align', command : 'justifyright', noActive : true, tile : 10},
		'ol' : {name : 'Insert Ordered List', command : 'insertorderedlist', tags : ['OL'], tile : 12},
		'ul' : 	{name : 'Insert Unordered List', command : 'insertunorderedlist', tags : ['UL'], tile : 13},
		'fontSize' : {name : 'Select Font Size', type : 'nicEditorFontSizeSelect', command : 'fontsize'},
		'fontFamily' : {name : 'Select Font Family', type : 'nicEditorFontFamilySelect', command : 'fontname'},
		'fontFormat' : {name : 'Select Font Format', type : 'nicEditorFontFormatSelect', command : 'formatBlock'},
		'subscript' : {name : 'Click to Subscript', command : 'subscript', tags : ['SUB'], tile : 6, disabled : true},
		'superscript' : {name : 'Click to Superscript', command : 'superscript', tags : ['SUP'], tile : 5, disabled : true},
		'strikeThrough' : {name : 'Click to Strike Through', command : 'strikeThrough', css : {'text-decoration' : 'line-through'}, tile : 7, disabled : true},
		'indent' : {name : 'Indent Text', command : 'indent', noActive : true, tile : 20},
		'unindent' : {name : 'Remove Indent', command : 'outdent', noActive : true, tile : 21},
		'hr' : {name : 'Horizontal Rule', command : 'insertHorizontalRule', noActive : true, tile : 22},
		'color' : {name : 'Change Color', type : 'nicEditorColorButton', tile : 25},
		'image' : {name : 'Add Image', type : 'nicEditorImageButton', tile : 14},
		'html' : {name : 'Edit HTML', type : 'nicEditorHTMLButton', tile : 16},
		'link' : {name : 'Add Link', type : 'nicEditorLinkButton', tile : 17}
	},
	iconsPath : 'scripts/nicEditorIcons.gif',
	fullPanel : false,
	onSubmit : null,
	buttonList : ['bold','italic','underline','strikeThrough','ol','ul','indent','unindent','link', 'html'],
	toolTipOn : false,
	toolTipText : 'Click to Edit'
};

function bkClass(){}bkClass.prototype.construct=function(){};bkClass.extend=function(E){var A=function(){if(arguments[0]!==bkClass){this.construct.apply(this,arguments)}};var D=new this(bkClass);var B=this.prototype;for(var F in E){var C=E[F];if(C instanceof Function){C.$=B}D[F]=C}A.prototype=D;A.extend=this.extend;return A};Function.prototype.closure=function(){var A=this,B=bkLib.toArray(arguments),C=B.shift();return function(){return A.apply(C,B.concat(bkLib.toArray(arguments)))}};Function.prototype.closureListener=function(){var A=this,C=bkLib.toArray(arguments),B=C.shift();return function(E){E=E||window.event;if(E.target){var D=E.target}else{var D=E.srcElement}return A.apply(B,[E,D].concat(C))}};function $N(A){return document.getElementById(A)}var bkLib={getStyle:function(B,A,D){var C=(!D)?document.defaultView:D;return(C&&C.getComputedStyle)?C.getComputedStyle(B,"").getPropertyValue(A):B.currentStyle[A]},setStyle:function(B,A){var C=B.style;for(itm in A){switch(itm){case"float":C["cssFloat"]=C["styleFloat"]=A[itm];break;case"opacity":C.opacity=A[itm];C.filter="alpha(opacity="+Math.round(A[itm]*100)+")";break;case"className":B.className=A[itm];break;default:if(document.compatMode||itm!="cursor"){C[itm]=A[itm]}}}},cancelEvent:function(A){A=A||window.event;if(A.preventDefault&&A.stopPropagation){A.preventDefault();A.stopPropagation()}return false},domLoad:[],domLoaded:function(){if(arguments.callee.done){return }arguments.callee.done=true;for(i=0;i<bkLib.domLoad.length;i++){bkLib.domLoad[i]()}},onDomLoaded:function(fireThis){this.domLoad.push(fireThis);if(document.addEventListener){document.addEventListener("DOMContentLoaded",bkLib.domLoaded,null);/*@cc_on @*//*@if (@_win32)
			var proto = "src='javascript:void(0)'";
			if (location.protocol == "https:") proto = "src=//0";
			document.write("<scr"+"ipt id=__ie_onload defer " + proto + "><\/scr"+"ipt>");
			var script = document.getElementById("__ie_onload");
			script.onreadystatechange = function() {
			    if (this.readyState == "complete") {
			        bkLib.domLoaded();
			    }
			};
		/*@end @*/}window.onload=bkLib.domLoaded},addEvent:function(C,B,A){(C.addEventListener)?C.addEventListener(B,A,false):C.attachEvent("on"+B,A)},elmPos:function(B){var C=curtop=0;var A=B.offsetHeight;if(B.offsetParent){C=B.offsetLeft;curtop=B.offsetTop;while(B=B.offsetParent){C+=B.offsetLeft;curtop+=B.offsetTop}}return[C,curtop+A]},mousePos:function(A){return[A.pageX||A.clientX+document.body.scrollLeft+document.documentElement.scrollLeft,A.pageY||A.clientY+document.body.scrollTop+document.documentElement.scrollTop]},getElementsByClassName:function(F){if(document.getElementsByClassName){return document.getElementsByClassName(F)}var A=[];var E=new RegExp("\\b"+F+"\\b");var D=document.getElementsByTagName("*");for(var C=0,B=D.length;C<B;C++){if(E.test(D[C].className)){A.push(D[C])}}return A},inArray:function(A,B){for(i=0;i<A.length;i++){if(A[i]===B){return true}}return false},toArray:function(C){var B=C.length,A=new Array(B);while(B--){A[B]=C[B]}return A},unselectAble:function(B){if(B.setAttribute&&B.contentEditable!=true&&B.nodeName!="input"&&B.nodeName!="textarea"){B.setAttribute("unselectable","on")}for(var A=0;A<B.childNodes.length;A++){bkLib.unselectAble(B.childNodes[A])}},ajaxRequest:function(D,C,A){var B=(window.XMLHttpRequest)?new window.XMLHttpRequest():new ActiveXObject("Microsoft.XMLHTTP");B.open((!D)?"GET":D,C,true);if(D=="POST"){B.setRequestHeader("Content-Type","application/x-www-form-urlencoded")}B.send(A)}};var bkEvent={addEvent:function(A,B){if(B){this.eventList=this.eventList||{};this.eventList[A]=this.eventList[A]||[];this.eventList[A].push(B)}},fireEvent:function(C,B){if(this.eventList&&this.eventList[C]){for(var A=0;A<this.eventList[C].length;A++){this.eventList[C][A](B)}}}}
var nicEditors={allTextAreas:function(C){var A=document.getElementsByTagName("textarea");var D=new Array();for(var B=0;B<A.length;B++){D.push(new nicEditor(C).panelInstance(A[B]))}return D}};var nicEditor=bkClass.extend({nicInstances:[],nicPanel:null,selectedInstance:null,construct:function(B){var A=bkClass.extend(nicEditorConfig);A=(B)?A.extend(B):A;this.options=new A();bkLib.addEvent(document.body,"mousedown",this.selectCheck.closureListener(this))},selectCheck:function(C,A){var B=false;do{if(A.className&&A.className.indexOf("nicEdit")!=-1){return false}}while(A=A.parentNode);this.fireEvent("noInstanceSelect",A);this.selectedInstance=null;return false},panelInstance:function(B){if(typeof (B)=="string"){B=$N(B)}var A=document.createElement("DIV");B.parentNode.insertBefore(A,B);panelWidth=B.width||B.clientWidth;A.style.width=panelWidth+"px";this.setPanel(A);return this.addInstance(B)},findInstance:function(A){if(typeof (A)=="string"){A=$N(A)}for(i=0;i<this.nicInstances.length;i++){if(A==this.nicInstances[i].elm){return this.nicInstances[i]}}},addInstance:function(A,B){if(typeof (A)=="string"){A=$N(A)}if(A.contentEditable||!!window.opera){this.nicInstances.push(new nicEditorInstance(A,B,this))}else{this.nicInstances.push(new nicEditorIFrameInstance(A,B,this))}return this},instancesByClassName:function(B,E){var D=bkLib.getElementsByClassName(B);var C=new Array();for(var A=0;A<D.length;A++){this.addInstance(D[A],E)}},nicCommand:function(B,A){if(this.selectedInstance){this.selectedInstance.nicCommand(B,A)}},setPanel:function(A){if(typeof (A)=="string"){A=$N(A)}this.nicPanel=new nicEditorPanel(A,this.options,this);return this}});nicEditor=nicEditor.extend(bkEvent);var nicEditorInstance=bkClass.extend({nicEditor:null,elm:null,initalContent:null,isSelected:false,construct:function(G,D,C){this.nicEditor=C;this.elm=G;this.options=D||{};newX=G.width||G.clientWidth;newY=G.height||G.clientHeight;if(G.nodeName=="TEXTAREA"){G.style.display="none";var B=document.createElement("DIV");var A=document.createElement("DIV");A.innerHTML=G.value;bkLib.setStyle(B,{width:(newX)+"px",border:"1px solid #ccc",borderTop:0,overflow:"hidden"});bkLib.setStyle(A,{width:(newX-8)+"px",margin:"4px",minHeight:(newY-8)+"px"});var H=navigator.appVersion;if(H.indexOf("MSIE")!=-1&&!((typeof document.body.style.maxHeight!="undefined")&&document.compatMode=="CSS1Compat")){A.style.height=newY+"px"}B.appendChild(A);G.parentNode.insertBefore(B,G);this.elm=A;this.copyElm=G;var F=document.getElementsByTagName("FORM");for(var E=0;E<F.length;E++){bkLib.addEvent(F[E],"submit",this.saveContent.closure(this))}}this.initialHeight=newY-8;this.nicEditor.addEvent("noInstanceSelect",this.unselected.closure(this));this.init();this.unselected()},init:function(){this.elm.setAttribute("contentEditable","true");this.initialContent=this.getContent();if(this.initialContent==""){this.setContent("<br />")}this.elm.className="nicEdit";bkLib.addEvent(this.elm,"mousedown",this.mouseDown.closureListener(this))},getSel:function(){return(window.getSelection)?window.getSelection():document.selection},getRng:function(){var A=this.getSel();if(!A){return null}return(A.rangeCount>0)?A.getRangeAt(0):A.createRange()},selRng:function(A,B){if(window.getSelection){B.removeAllRanges();B.addRange(A)}else{A.select()}},saveRng:function(){this.savedRange=this.getRng();this.savedSel=this.getSel()},restoreRng:function(){if(this.savedRange){this.selRng(this.savedRange,this.savedSel)}},mouseDown:function(B,A){if(this.nicEditor.selectedInstance!=this){this.nicEditor.fireEvent("noInstanceSelect",A)}this.nicEditor.selectedInstance=this;this.nicEditor.fireEvent("instanceSelect",A);this.selected()},selected:function(){this.isSelected=true;bkLib.setStyle(this.elm,{className:"nicEdit nicEdit-instanceSelect"});if(this.toolTip){this.toolTip.remove();this.toolTip=null}},unselected:function(){this.isSelected=false;bkLib.setStyle(this.elm,{className:"nicEdit nicEdit-noInstanceSelect"});if(!this.toolTip&&(this.nicEditor.options.toolTipOn||this.options.toolTipOn)){this.addTooltip();this.toolTip.setContent((this.options.toolTipText)?this.options.toolTipText:this.nicEditor.options.toolTipText)}},addTooltip:function(){this.toolTip=new nicEditorTooltip(this.elm,this.nicEditor,this.getTipStyle())},saveContent:function(){if(this.copyElm){this.copyElm.value=this.getContent()}},getContent:function(){return this.elm.innerHTML},setContent:function(A){this.elm.innerHTML=A},getTipStyle:function(){return{padding:"3px",backgroundColor:"#ffffc9",fontSize:"12px",border:"1px solid #ccc",className:"nicEdit-instanceTip"}},getDoc:function(){return document.defaultView},nicCommand:function(B,A){document.execCommand(B,false,A)}});var nicEditorIFrameInstance=nicEditorInstance.extend({elm:null,init:function(){this.elmFrame=document.createElement("iframe");this.elmFrame.setAttribute("frameBorder","0");this.elmFrame.setAttribute("allowTransparency","true");this.elmFrame.setAttribute("scrolling","no");bkLib.setStyle(this.elmFrame,{width:"100%",overflow:"hidden",className:"nicEdit-frame"});if(this.copyElm){this.elmFrame.style.width=(this.elm.offsetWidth-4)+"px"}this.initialFontSize=bkLib.getStyle(this.elm,"font-size");this.initialFontFamily=bkLib.getStyle(this.elm,"font-family");this.initialFontWeight=bkLib.getStyle(this.elm,"font-weight");this.initialFontColor=bkLib.getStyle(this.elm,"color");this.initialContent=this.elm.innerHTML;if(this.initialContent==""){this.initialContent="<br />"}if(!this.copyElm){this.initialHeight=0}this.elm.innerHTML="";this.elm.appendChild(this.elmFrame);this.initFrame();this.heightUpdate();bkLib.addEvent(this.elmFrame.contentWindow.document,"mousedown",this.mouseDown.closureListener(this));bkLib.addEvent(this.elmFrame.contentWindow.document,"keyup",this.heightUpdate.closureListener(this))},initFrame:function(){this.frameDoc=this.elmFrame.contentWindow.document;this.frameDoc.open();this.frameDoc.write('<html><head></head><body id="nicEditContent" style="margin: 0 !important; background-color: transparent !important;">');this.frameDoc.write(this.initialContent);this.frameDoc.write("</body></html>");this.frameDoc.close();this.frameDoc.designMode="on";this.frameContent=this.elmFrame.contentWindow.document.body;bkLib.setStyle(this.frameContent,{fontSize:this.initialFontSize,fontFamily:this.initialFontFamily,fontWeight:this.initialFontWeight,color:this.initialFontColor})},getContent:function(){return this.frameContent.innerHTML},addTooltip:function(A){this.toolTip=new nicEditorTooltip(this.elmFrame,this.nicEditor,this.getTipStyle())},setContent:function(A){this.frameContent.innerHTML=A},getDoc:function(){return this.elmFrame.contentWindow.document.defaultView},getSel:function(){return(this.elmFrame.contentWindow)?this.elmFrame.contentWindow.getSelection():this.frameDoc.selection},heightUpdate:function(){this.elmFrame.style.height=(this.frameContent.offsetHeight<this.initialHeight)?this.initialHeight+"px":this.frameContent.offsetHeight+"px"},nicCommand:function(B,A){this.frameDoc.execCommand(B,false,A);setTimeout(this.heightUpdate.closure(this),100)}});var nicEditorPanel=bkClass.extend({panelId:null,panelButtons:[],panelElm:null,elm:null,construct:function(D,B,A){this.elm=D;this.options=B;this.nicEditor=A;this.panelElm=document.createElement("div");this.panelContain=document.createElement("div");bkLib.setStyle(this.panelContain,{width:"100%",border:"1px solid #cccccc",backgroundColor:"#efefef",className:"nicEdit-panelContain"});bkLib.setStyle(this.panelElm,{margin:"2px",overflow:"hidden",className:"nicEdit-panel"});this.panelButtons=new Array();if(this.options.fullPanel){for(b in this.options.buttons){this.addButton(this.options.buttons[b])}}else{for(var C=0;C<this.options.buttonList.length;C++){this.addButton(this.options.buttons[this.options.buttonList[C]])}}this.panelContain.appendChild(this.panelElm);this.elm.appendChild(this.panelContain);bkLib.unselectAble(this.elm)},addButton:function(button){var type=(button["type"])?eval(button["type"]):nicEditorButton;this.panelButtons.push(new type(this.panelElm,button,this.nicEditor))}});var nicEditorButton=bkClass.extend({isDisabled:false,isHover:false,isActive:false,construct:function(C,B,A){this.elm=C;this.options=B;this.nicEditor=A;this.buttonContain=document.createElement("div");this.buttonBorder=document.createElement("div");this.buttonElm=document.createElement("div");bkLib.setStyle(this.buttonElm,{backgroundImage:"url('"+this.nicEditor.options.iconsPath+"')",width:"18px",height:"18px",backgroundPosition:((this.options.tile-1)*-18)+"px 0px",cursor:"pointer",className:"nicEdit-button"});bkLib.setStyle(this.buttonBorder,{border:"1px solid #efefef",width:"18px",height:"18px",backgroundColor:"#efefef"});bkLib.setStyle(this.buttonContain,{"float":"left",overflow:"hidden",width:"20px",height:"20px",className:"nicEdit-buttonContain"});bkLib.addEvent(this.buttonElm,"mouseover",this.hoverOn.closure(this));bkLib.addEvent(this.buttonElm,"mouseout",this.hoverOff.closure(this));bkLib.addEvent(this.buttonElm,"click",this.mouseClick.closure(this));if(!window.opera){this.buttonElm.onmousedown=bkLib.cancelEvent;this.buttonElm.onclick=bkLib.cancelEvent}this.nicEditor.addEvent("instanceSelect",this.enable.closure(this));this.nicEditor.addEvent("noInstanceSelect",this.disable.closure(this));this.disable();this.buttonBorder.appendChild(this.buttonElm);this.buttonContain.appendChild(this.buttonBorder);this.elm.appendChild(this.buttonContain);this.init()},init:function(){},hideButton:function(){this.buttonContain.style.display="none"},updateState:function(){if(this.isDisabled){this.setBg()}else{if(this.isHover){this.setBg("hover")}else{if(this.isActive){this.setBg("active")}else{this.setBg()}}}},setBg:function(A){if(A=="hover"){bkLib.setStyle(this.buttonBorder,{border:"1px solid #666",backgroundColor:"#ddd",className:"nicEdit-buttonContain-hover"})}else{if(A=="active"){bkLib.setStyle(this.buttonBorder,{border:"1px solid #666",backgroundColor:"#ccc",className:"nicEdit-buttonContain-active"})}else{bkLib.setStyle(this.buttonBorder,{border:"1px solid #efefef",backgroundColor:"#efefef",className:"nicEdit-buttonContain-normal"})}}},checkNodes:function(A){var B=A;do{if(this.options.tags&&bkLib.inArray(this.options.tags,B.nodeName)){this.activate();return true}}while(B=B.parentNode);var B=A;if(this.options.css){for(itm in this.options.css){if(bkLib.getStyle(B,itm,this.nicEditor.selectedInstance.getDoc())==this.options.css[itm]){this.activate();return true}}}this.deactivate();return false},activate:function(){if(!this.isDisabled){this.isActive=true;this.updateState()}},deactivate:function(){this.isActive=false;this.updateState()},enable:function(A){this.isDisabled=false;bkLib.setStyle(this.buttonContain,{"opacity":1,className:"nicEdit-buttonContain-enabled"});this.updateState();this.checkNodes(A)},disable:function(A){this.isDisabled=true;bkLib.setStyle(this.buttonContain,{"opacity":0.6,className:"nicEdit-buttonContain-disabled"});this.updateState();this.removePane()},toggleActive:function(){(this.isActive)?this.deactivate():this.activate()},hoverOn:function(){if(!this.isDisabled){this.isHover=true;this.updateState();this.toolTimer=setTimeout(this.addTooltip.closure(this),500)}},addTooltip:function(){if(this.isHover&&!this.buttonPane){this.toolTip=new nicEditorPane(this.buttonContain,this.nicEditor,{margin:"4px",padding:"3px",backgroundColor:"#ffffc9",fontSize:"12px",border:"1px solid #ccc",className:"nicEdit-tooltip"});this.toolTip.setContent(this.options.name)}},removeTooltip:function(){if(this.toolTimer){clearTimeout(this.toolTimer)}if(this.toolTip){this.toolTip.remove();this.toolTip=null}},getPaneStyle:function(){return{width:"275px",fontSize:"12px",padding:"4px",textAlign:"left",border:"1px solid #ccc",backgroundColor:"#fff",className:"nicEdit-buttonPane"}},removePane:function(){if(this.buttonPane){this.buttonPane.remove();this.buttonPane=null;return true}},hoverOff:function(){this.isHover=false;this.updateState();this.removeTooltip()},mouseClick:function(){if(this.options.command){this.nicEditor.nicCommand(this.options.command,this.options.commandArgs);if(!this.options.noActive){this.toggleActive()}}else{(this.buttonPane)?this.removePane():this.addPane()}}});var nicEditorSelect=bkClass.extend({isDisabled:false,construct:function(C,B,A){this.elm=C;this.options=B;this.nicEditor=A;this.selectContain=document.createElement("DIV");this.selectItems=document.createElement("DIV");this.selectControl=document.createElement("DIV");this.selectTxt=document.createElement("DIV");this.selectOptions=new Array();bkLib.setStyle(this.selectContain,{overflow:"hidden",width:"90px",height:"20px","float":"left",cursor:"pointer",margin:"0 2px",className:"nicEdit-selectContain"});bkLib.setStyle(this.selectItems,{overflow:"hidden",border:"1px solid #ccc",paddingLeft:"3px",height:"18px",backgroundColor:"#fff"});bkLib.setStyle(this.selectControl,{backgroundImage:"url('"+this.nicEditor.options.iconsPath+"')",backgroundPosition:(17*-18)+"px 0px","float":"right",height:"16px",width:"16px",className:"nicEdit-selectControl"});bkLib.setStyle(this.selectTxt,{"float":"left",width:"66px",overflow:"hidden",height:"16px",marginTop:"1px",fontFamily:"arial",fontSize:"12px",className:"nicEdit-selectTxt"});if(!window.opera){this.selectContain.onmousedown=this.selectControl.onmousedown=this.selectTxt.onmousedown=bkLib.cancelEvent}this.selectItems.appendChild(this.selectTxt);this.selectItems.appendChild(this.selectControl);this.selectContain.appendChild(this.selectItems);this.elm.appendChild(this.selectContain);bkLib.addEvent(this.selectContain,"click",this.togglePane.closure(this));this.nicEditor.addEvent("instanceSelect",this.enable.closure(this));this.nicEditor.addEvent("noInstanceSelect",this.disable.closure(this));this.disable();this.init();bkLib.unselectAble(this.elm)},disable:function(){this.isDisabled=true;this.removePane();bkLib.setStyle(this.selectContain,{opacity:0.6})},enable:function(A){this.isDisabled=false;bkLib.setStyle(this.selectContain,{opacity:1})},setDisplay:function(A){this.selectTxt.innerHTML=A},togglePane:function(){if(!this.isDisabled){(this.selectPane)?this.removePane():this.showPane()}},showPane:function(){this.selectPane=new nicEditorPane(this.selectContain,this.nicEditor,this.getPaneStyle());for(var B=0;B<this.selectOptions.length;B++){var A=this.selectOptions[B][0];var D=this.selectOptions[B][1];var C=document.createElement("div");bkLib.setStyle(C,{overflow:"hidden",width:"88px",textAlign:"left",padding:"0 4px",cursor:"pointer",borderBottom:"1px solid #ccc"});C.id=A;C.innerHTML=D;C.onclick=this.onSelect.closure(this,A);C.onmouseover=this.optionOver.closure(this,C);C.onmouseout=this.optionOut.closure(this,C);if(!window.opera){C.onmousedown=bkLib.cancelEvent}this.selectPane.append(C);bkLib.unselectAble(C)}},removePane:function(){if(this.selectPane){this.selectPane.remove();this.selectPane=null}},getPaneStyle:function(){return{width:"88px",overflow:"hidden",fontSize:"12px",borderLeft:"1px solid #ccc",borderRight:"1px solid #ccc",backgroundColor:"#fff",className:"nicEdit-selectPane"}},optionOver:function(A){bkLib.setStyle(A,{backgroundColor:"#ccc"})},optionOut:function(A){bkLib.setStyle(A,{backgroundColor:"#fff"})},add:function(B,A){this.selectOptions.push(new Array(B,A))},onSelect:function(A){this.nicEditor.nicCommand(this.options.command,A);this.removePane()}});var nicEditorFontSizeSelect=nicEditorSelect.extend({selConfig:{1:"1&nbsp;(8pt)",2:"2&nbsp;(10pt)",3:"3&nbsp;(12pt)",4:"4&nbsp;(14pt)",5:"5&nbsp;(18pt)",6:"6&nbsp;(24pt)"},init:function(){this.setDisplay("Font&nbsp;Size..");for(itm in this.selConfig){this.add(itm,'<font size="'+itm+'">'+this.selConfig[itm]+"</font>")}}});var nicEditorFontFamilySelect=nicEditorSelect.extend({selConfig:{"arial":"Arial","comic sans ms":"Comic Sans","courier new":"Courier New","georgia":"Georgia","helvetica":"Helvetica","impact":"Impact","times new roman":"Times","trebuchet ms":"Trebuchet","verdana":"Verdana"},init:function(){this.setDisplay("Font&nbsp;Family..");for(itm in this.selConfig){this.add(itm,'<font face="'+itm+'">'+this.selConfig[itm]+"</font>")}}});var nicEditorFontFormatSelect=nicEditorSelect.extend({selConfig:{"p":"Paragraph","pre":"Pre","h6":"Heading&nbsp;6","h5":"Heading&nbsp;5","h4":"Heading&nbsp;4","h3":"Heading&nbsp;3","h2":"Heading&nbsp;2","h1":"Heading&nbsp;1"},init:function(){this.setDisplay("Font&nbsp;Format..");for(itm in this.selConfig){var A=itm.toUpperCase();this.add("<"+A+">","<"+itm+' style="padding: 0px; margin: 0px;">'+this.selConfig[itm]+"</"+A+">")}}});var nicEditorSaveButton=nicEditorButton.extend({onSave:null,init:function(){this.onSave=this.nicEditor.options.onSave;if(!this.onSave){this.hideButton()}},mouseClick:function(){if(this.nicEditor.options.onSave){this.nicEditor.options.onSave(this.nicEditor.nicInstances)}}});var nicEditorPane=bkClass.extend({nicPane:null,options:null,isVisible:true,isOver:true,construct:function(E,B,A){this.options=A;this.nicEditor=B;this.elm=E;var C=this.nicEditor.nicPanel.panelElm;this.nicPane=document.createElement("DIV");this.pos=bkLib.elmPos(E);bkLib.setStyle(this.nicPane,{position:"absolute",left:this.pos[0]+"px",top:this.pos[1]+"px",className:"nicEdit-pane"});bkLib.setStyle(this.nicPane,A);document.body.appendChild(this.nicPane);panelPos=bkLib.elmPos(C);var D=panelPos[0]+C.offsetWidth;xPos=this.pos[0]+this.nicPane.offsetWidth;if(xPos>D){bkLib.setStyle(this.nicPane,{left:(this.pos[0]-(xPos-D+1))+"px"})}this.init();bkLib.unselectAble(this.nicPane)},init:function(){},hide:function(){this.nicPane.style.display="none";this.isVisible=false},show:function(){if(this.isOver){this.nicPane.style.display="block";this.isVisible=true}},remove:function(){if(this.nicPane){document.body.removeChild(this.nicPane)}},append:function(A){this.nicPane.appendChild(A)},setContent:function(A){this.nicPane.innerHTML=A}});var nicEditorTooltip=nicEditorPane.extend({init:function(){bkLib.addEvent(this.elm,"mouseover",this.mouseOver.closureListener(this));bkLib.addEvent(this.elm,"mouseout",this.mouseOut.closureListener(this));bkLib.addEvent((this.elm.nodeName=="IFRAME")?this.elm.contentWindow.document:this.elm,"mousemove",this.mouseMove.closureListener(this));this.hide()},mouseMove:function(C){var E=bkLib.mousePos(C);var A=(this.elm.nodeName=="IFRAME");var B=A?E[0]+this.pos[0]:E[0];var D=A?E[1]+this.pos[1]-this.elm.offsetHeight:E[1];bkLib.setStyle(this.nicPane,{left:(B+10)+"px",top:(D+10)+"px"})},mouseOver:function(C,B){var A=(C.relatedTarget)?C.relatedTarget:C.toElement;while(A&&A!=this.elm){A=A.parentNode}if(A==this.elm){return }if(!this.isVisible){this.toolTimer=setTimeout(this.show.closure(this),400)}this.isOver=true},mouseOut:function(C,B){var A=(C.relatedTarget)?C.relatedTarget:C.toElement;while(A&&A!=this.elm){A=A.parentNode}if(A==this.elm){return }if(this.toolTimer){clearTimeout(this.toolTimer)}this.hide();this.isOver=false}});var nicEditorImageButton=nicEditorButton.extend({addPane:function(){this.removeTooltip();this.nicEditor.selectedInstance.saveRng();this.buttonPane=new nicEditorPane(this.buttonContain,this.nicEditor,this.getPaneStyle());var A='<form id="nicImageForm" onSubmit="return false;"><h3 style="padding: 0px; margin: 0px;">Insert Image</h3>			<label style="display: block; float: left; width: 100px" for="url">Image URL</label>			<input style="border: 1px solid #ccc; width: 150px;" type="text" name="url" id="nicImageURL" value="http://" /><br />			<input type="submit" style="border: 1px solid #ccc" id="nicImageSubmit" /></div></form>';this.buttonPane.setContent(A);this.imgURL=$N("nicImageURL");this.imgURL.focus();bkLib.addEvent($N("nicImageForm"),"submit",this.paneSubmit.closure(this))},paneSubmit:function(){var B=this.nicEditor.selectedInstance;if(B){B.restoreRng();var A=this.imgURL.value;if(A=="http://"||A==""){alert("You must enter a URL to Insert an Image");return false}this.nicEditor.nicCommand("insertImage",A);this.removePane()}}});var nicEditorLinkButton=nicEditorButton.extend({addPane:function(){this.removeTooltip();this.nicEditor.selectedInstance.saveRng();this.buttonPane=new nicEditorPane(this.buttonContain,this.nicEditor,this.getPaneStyle());var A='<form id="nicLinkForm" onSubmit="return false;"><h3 style="padding: 0px; margin: 0px;">Insert Link</h3>			<label style="display: block; float: left; width: 100px" for="url">Link URL</label>			<input type="text" style="border: 1px solid #ccc; width: 150px;" name="url" id="nicLinkURL" value="http://" /><br />			<input type="submit" style="border: 1px solid #ccc" value="Submit" /></form>';this.buttonPane.setContent(A);this.linkURL=$N("nicLinkURL");this.linkURL.focus();bkLib.addEvent($N("nicLinkForm"),"submit",this.paneSubmit.closure(this))},paneSubmit:function(){var B=this.nicEditor.selectedInstance;if(B){this.nicEditor.selectedInstance.restoreRng();var A=this.linkURL.value;if(A=="http://"||A==""){alert("You must enter a URL to Create a Link");return false}this.nicEditor.nicCommand("createlink",A)}this.removePane()}});var nicEditorHTMLButton=nicEditorButton.extend({addPane:function(){this.removeTooltip();this.buttonPane=new nicEditorPane(this.buttonContain,this.nicEditor,{width:(this.nicEditor.nicPanel.panelElm.offsetWidth-12)+"px",textAlign:"left",border:"1px solid #ccc",zIndex:"9999",backgroundColor:"#fff"});paneContent='<form id="nicHTMLForm" onSubmit="return false;"><textarea id="nicHTMLArea" style="width: 100%; text-align: left; font-size: 13px; height: 200px; border: 0; border-bottom: 1px solid #ccc;">			</textarea><input type="submit" style="margin-left: 20px; border: 1px solid #ccc" value="Update HTML" /></form>';this.buttonPane.setContent(paneContent);bkLib.addEvent($N("nicHTMLForm"),"submit",this.paneSubmit.closure(this));$N("nicHTMLArea").value=this.nicEditor.selectedInstance.getContent()},paneSubmit:function(){var A=this.nicEditor.selectedInstance;if(A){A.restoreRng();A.setContent($N("nicHTMLArea").value)}this.removePane()}});var nicEditorColorButton=nicEditorButton.extend({addPane:function(){this.removeTooltip();this.buttonPane=new nicEditorPane(this.buttonContain,this.nicEditor,this.getPaneStyle());var D={0:"00",1:"33",2:"66",3:"99",4:"CC",5:"FF"};var H=document.createElement("DIV");bkLib.setStyle(H,{width:"270px"});for(var A in D){for(var F in D){for(var E in D){var C=document.createElement("DIV");var G=document.createElement("DIV");var B=document.createElement("DIV");var I="#"+D[A]+D[E]+D[F];bkLib.setStyle(C,{"cursor":"pointer","height":"15px","float":"left"});bkLib.setStyle(G,{border:"2px solid "+I});bkLib.setStyle(B,{backgroundColor:I,overflow:"hidden",width:"11px",height:"11px"});bkLib.addEvent(C,"click",this.colorSelect.closure(this,I));bkLib.addEvent(C,"mouseover",this.borderOn.closure(this,G));bkLib.addEvent(C,"mouseout",this.borderOff.closure(this,G,I));if(!window.opera){C.onmousedown=bkLib.cancelEvent;B.onmousedown=bkLib.cancelEvent}C.appendChild(G);G.appendChild(B);H.appendChild(C)}}}bkLib.unselectAble(H);this.buttonPane.append(H)},colorSelect:function(A){this.nicEditor.nicCommand("foreColor",A);this.removePane()},borderOn:function(A){bkLib.setStyle(A,{border:"2px solid #000"})},borderOff:function(A,B){bkLib.setStyle(A,{border:"2px solid "+B})}})