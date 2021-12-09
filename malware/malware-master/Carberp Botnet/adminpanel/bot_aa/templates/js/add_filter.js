function create_data_filter(z, add_html){
	var content = document.getElementById(add_html);
	if (z <= 0){
		content.innerHTML = '<center>Параметров фильтра не может быть ноль! Сделайте выбор колличества полей больше нуля!</center>';
	}else{
		var html = '';

		html += '<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">';
		html += '<tr>';
		html += '<td style="text-align: center; width: 250px;">Название полей</td>';
		html += '<td>';
		for (i=1; i <= z; i+=1){
			html += 'Название поля №'+i+': <input name="p[name]['+i+']" type="text" style="width: 300px;" />';
			if (i < z) html += '<hr />';
		}
		html += '</td>';
		html += '</tr></table>';
		html += '<br /><hr /><br />';
		html += '<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">';
		html += '<tr>';
		html += '<td style="text-align: center; width: 250px;">Параметры полей для Граббера</td>';
		html += '<td>';
		for (i=1; i <= z; i+=1){
			html += 'Номер поля в логе граббера для поля №'+i+': <input name="p[grabber]['+i+']" type="text" style="width: 33px;" maxlength="2" onkeypress="return numbersonly(event)" />';
			if (i < z) html += '<hr />';
		}
		html += '</td>';
		html += '</tr></table>';
		html += '<br /><hr /><br />';
		html += '<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">';
		html += '<tr>';
		html += '<td style="text-align: center; width: 250px;">Параметры полей для Форм-Граббера</td>';
		html += '<td>';
		value1 = '';
		value2 = '';
		for (i=1; i <= z; i+=1){
			html += 'Название переменной для поля №'+i+': <input name="p[formgrabber]['+i+']" type="text" style="width: 222px;" />';
			if (i < z) html += '<hr />';
		}
		html += '</td>';
		html += '</tr></table>';

		content.innerHTML = html;
	}
}

function set_data_filter(p, namedata, formname){
	if(namedata == null) namedata = 'p';
	if(p){
		if(p['name']){
			for (i=1; i < p['name'].length; i+=1){
				if(p['name'][i]) document.forms[formname].elements[namedata + '[name]['+i+']'].value = p['name'][i];
			}
		}

		if(p['grabber']){
			for (i=1; i < p['grabber'].length; i+=1){
				if(p['grabber'][i]) document.forms[formname].elements[namedata + '[grabber]['+i+']'].value = p['grabber'][i];
			}
		}

		if(p['formgrabber']){
			for (i=1; i < p['formgrabber'].length; i+=1){
				if(p['formgrabber'][i]) document.forms[formname].elements[namedata + '[formgrabber]['+i+']'].value = p['formgrabber'][i];
			}
		}
	}
}