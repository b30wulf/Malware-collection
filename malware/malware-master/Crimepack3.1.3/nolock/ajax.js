function getXmlHttp() {
	var xmlhttp;
	if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest( );
	} else if (window.ActiveXObject) {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		if ( !xmlhttp ){
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return xmlhttp;
}
function autoJS(src){
	var scripts = Array.from(src.getElementsByTagName('script'));
	for (var i = 0; i < scripts.length; i++) {
		var script = scripts[i];
		var jsObj = document.createElement('script');
		if (script.src)
			jsObj.src = script.src;
		if (script.type)
			jsObj.type = script.type;
		jsObj.text = script.text;
		script.parentNode.removeChild(script);
		src.appendChild(jsObj);
	}
}
function ajax_load(url, tag_id, loadgif,extras) {
	var elem = document.getElementById(tag_id);
	if (elem) {
		var bckHTML = elem.innerHTML;
		elem.innerHTML = '<table><tr><td valign="center"><img src="' + loadgif + '" alt="checking...">';
		if (extras) { 
			elem.innerHTML += extras;
		}
		elem.innerHTML += '</td></tr></table>';

	}
	
	if (url.indexOf('?') != -1)
		url += '&';
	else
		url += '?';
	url += 'rnd=' + Math.random();
	
	var xmlhttp = getXmlHttp();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			if (this.status == 200) {
				var resp = this.responseText;
				if (elem) {
					elem.innerHTML = resp;
					autoJS(elem);
				}
			}
			else {
				if (elem) elem.innerHTML = '<table><tr><td>error while checking....</td></tr></table>';
			}
		}
	};
	xmlhttp.open('GET', url, true);
	xmlhttp.send('');
}
