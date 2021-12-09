function get_window (http_link, options) {
	if (!options) options = {};
	if(options.name == null) options.name = 'r' + Math.floor( Math.random( ) * (999999 - 1 + 1) ) + 1;
	if(document.getElementById(options.name + '_wid') == null){
		window_open(options.name, options.title, options.widht, options.height);
		return get_hax({
			url:http_link,
			id:options.name + '_content',
			method: options.method,
			form: options.form,
			onload: function (obj){toggler_false = false; window_resize(obj.id.replace('_content','_wid'));},
		});
	}else{
		//document.getElementById(options.name + '_wid').zIndex = 1;
		//document.getElementById(options.name + '_wid').focus();
	}
	return false;
}

function window_open (window_topic, title, widht, height) {
	if(!document.getElementById(window_topic + '_wid')){
		toggler_false = true;

		var window_id = window_topic + '_wid';
		var window_open = document.createElement("DIV");
		window_open.style.display = 'none';
		var window_top = document.createElement("DIV");
		var window_top_left = document.createElement("DIV");
		var window_top_right = document.createElement("DIV");
		var window_title = document.createElement("DIV");
		var window_button_close = document.createElement("DIV");
		var window_content = document.createElement("DIV");

		window_title.className = 'window_title';
		window_title.id = window_topic + '_title';
		if(title != null) window_title.innerHTML = title;

		window_button_close.className = 'window_button_close';
		window_button_close.onclick = function(){ window_close_opacity(this.parentNode.parentNode.id,1); };
		window_button_close.onmousemove = function(){ this.className = 'window_button_close_active'; };
		window_button_close.onmouseout = function(){ this.className = 'window_button_close'; };

		window_top.className = 'window_top';
		window_top_left.className = 'window_top_left';
		window_top.appendChild(window_top_left);

		window_top_right.className = 'window_top_right';
		window_top.appendChild(window_top_right);

		window_top.appendChild(window_title);
		window_top.appendChild(window_button_close);

		window_content.id = window_topic + '_content';
		window_content.className = 'window_content';
		window_content.innerHTML = '<br /><br /><div align="center"><img src="/images/indicator.gif" alt="'+lang['load']+'"/></div><br /><br />';

		window_open.className = 'window_open';
		window_open.id = window_id;

		if(widht != null){
			window_open.style.width = widht + 'px';
		}else{
			window_open.style.width = 250 + 'px';
		}

		if(height != null){
			window_open.style.height = height + 'px';
			window_content.style.height = (height-25) + 'px';
		}else{
			//window_open.style.height = 50 + 'px';
			//window_content.style.height = 25 + 'px';
		}
		
		window_open.appendChild(window_top);
		window_open.appendChild(window_content);
		window_open.style.opacity = '0';
		document.body.appendChild(window_open);
		window_resize(window_open.id);
		window_open_opacity(window_id, 0);
		window_open.focus();
	}else{
		document.getElementById(window_topic + '_wid').focus();
	}
}

function window_resize (id) {
	var window_open = document.getElementById(id);
	var window_content = document.getElementById(id.replace('_wid', '_content'));
	window_open.style.display = 'block';

	if(!window_content.style.height){
		window_open.style.height = (window_content.scrollHeight + 29) + 'px';
	}else{
		/*
		if(window_content.clientHeight < window_content.scrollHeight){
			if(window_open.style.maxHeight < window_open.scrollHeight){
				//window_open.style.height = window_open.style.maxHeight + 'px';
				//window_content.style.height = window_open.style.maxHeight + 'px';
			}else{
				window_open.style.height = (window_content.scrollHeight + 29) + 'px';
				window_content.style.height = (window_open.scrollHeight - 27) + 'px';
			}
		}else{
			window_open.style.height = (window_content.clientHeight + 29) + 'px';
			window_content.style.height = (window_open.clientHeight - 27) + 'px';
		}
		*/
	}
	
	window_open.style.width = (window_content.scrollWidth + 25) + 'px';

	window_open.style.left = Math.ceil(((document.documentElement.clientWidth / 2) - (window_open.clientWidth / 2)) + (Math.random() * 10)) + 'px';
	window_open.style.top = Math.ceil(((document.documentElement.clientHeight / 2) - (window_open.clientHeight / 2)) + (Math.random() * 100)) + 'px';
	if(window_open.style.top.replace('px', '') < '15') window_open.style.top = 15 + 'px';
	window_open.style.top = (Math.ceil(document.documentElement.scrollTop) + Math.ceil(window_open.style.top.replace('px', ''))) + 'px';
}

function window_open_opacity(id, opacity) {
	if(document.getElementById(id)){
		var window_open = document.getElementById(id);
		if(window_open.style.opacity < 1) {
			opacity += 0.2;
			if(('\v'=='v') != false) window_open.style.filter = 'alpha(opacity='+opacity*100+')';
			window_open.style.opacity = opacity.toFixed(1);
			t = setTimeout('window_open_opacity(\''+id+'\','+opacity+')', 50);
		}
	}
}

function window_close_opacity(id, opacity) {
	if(document.getElementById(id)){
		var window_open = document.getElementById(id);
		if(window_open.style.opacity > 0) {
			opacity -= 0.2;
			if(('\v'=='v') != false) window_open.style.filter = 'alpha(opacity='+opacity*100+')';
			window_open.style.opacity = opacity.toFixed(1);
			t = setTimeout('window_close_opacity(\''+id+'\','+opacity+')', 20);
		}else{
			document.body.removeChild(window_open);
		}
	}
}

function window_close(id) {
	if(document.getElementById(id)){
		var window_open = document.getElementById(id);
		document.body.removeChild(window_open);
	}
}

var dragresize = new DragResize('window', { minLeft: 10, minTop: 10, maxLeft: document.documentElement.clientWidth-30 });
dragresize.isElement = function(elm){ if (elm.className && elm.className.indexOf('window_open') > -1) return true};
dragresize.isHandle = function(elm){ if (elm.className && elm.className.indexOf('window_top') > -1) return true};
dragresize.ondragfocus = function() {};
dragresize.ondragstart = function(isResize) {};
dragresize.ondragmove = function(isResize) {};
dragresize.ondragend = function (isResize) {};
dragresize.ondragblur = function() {};
dragresize.apply(document);


