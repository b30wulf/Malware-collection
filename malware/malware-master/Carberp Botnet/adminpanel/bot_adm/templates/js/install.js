function clickIE(){
	if (document.all){
		return false;
	}else{
		return true;
	}
}

function clickNS(e) {
	if(document.layers || (document.getElementById && !document.all)){
		if (e.which==2){
			return false;
		}else{
			return true;
		}
	}else{
		return true;
	}
}

if (document.layers) {
	document.captureEvents(Event.MOUSEDOWN);
	document.onmousedown=clickNS;
}else{
	document.onmouseup=clickNS;
	document.oncontextmenu=clickIE;
}

document.oncontextmenu=new Function("return false");