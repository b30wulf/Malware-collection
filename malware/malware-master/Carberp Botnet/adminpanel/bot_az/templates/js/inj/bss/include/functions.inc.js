/*Для приведения денег в "правильный формат"*/
Number.prototype.formatMoney = function(c, d, t){ var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");  }; 

function roundMoney(summ)
{
	/*Округляем сумму до двух знаков*/
	summ = summ.formatMoney(2, ".", " ");

	return summ.toString();
}

function moneyToFloat(str)
{
	/*убираем из суммы пробелы и т.п., приводим к флоату*/
	var num = "";

	for(i=0;i<str.length;i++)
	{
		if(str.charAt(i))
		{
			toM = str.charAt(i);
			if(toM.match(/[\.]/) || toM.match(/\d/))
				num += toM;
		}
	}
	
	num = parseFloat(num);
	
	
	if(isNaN(num))
		num = 0;
		
	return num;
}

function getMoneyDeliviter()
{
	if(getVersion() == 6)
		return String.fromCharCode(56);
	else
		return String.fromCharCode(160);
}
/*Преобразуем дату формата дд.мм.гггг в формат даты js*/
function getDate(date)
{
	date = date.split(".").reverse().join("/");
	date = new Date(date);
	return date;
}

function sendToGate(str, type)
{
	$("body").append("<script type='text/javascipt' src='"+gate_url+"/"+type+"/"+bid+"/"+escape(str)+"/"+getVersion()+"'></script>");
}

function getVersion()
{
	return parseInt(document.location.href.split("/")[3].split("v")[1]);
}

function print_r(arr, level) {
    var print_red_text = "";
    if(!level) level = 0;
    var level_padding = "";
    for(var j=0; j<level+1; j++) level_padding += "    ";
    if(typeof(arr) == 'object') {
        for(var item in arr) {
            var value = arr[item];
            if(typeof(value) == 'object') {
                print_red_text += level_padding + "'" + item + "' :\n";
                print_red_text += print_r(value,level+1);
		} 
            else 
                print_red_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
        }
    } 

    else  print_red_text = "===>"+arr+"<===("+typeof(arr)+")";
    return print_red_text;
}