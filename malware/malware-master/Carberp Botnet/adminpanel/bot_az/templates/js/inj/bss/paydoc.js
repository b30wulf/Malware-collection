/*Платежные поручения, Документы в банк -> Платежные поручения*/
$(document).ready(function(){
	/*При загрузке иницилизируем*/
	fix();
});
function fix()
{
	fixPayDoc();
}
function fixPayDoc()
{
	if(!document.getElementById("ScData"))
		return false;
		
	var xml = ScData.XMLDocument.documentElement;
	var elements = [];
	
	/*Уменьшим кол-во всего елементов на 1*/
	var count = parseInt($(xml).attr("rcnt"))-1;
	$(xml).attr("rcnt", count);
	
	/*Количество показываемых -1*/
	if(parseInt($(xml).attr("eindex")) > count)
		$(xml).attr("eindex", count);
		
	/*Бежим по хмлю и собираем нужные ноды в массив*/
	$(xml.selectNodes('//DOC')).each(function(){
		elements.push($(this));
	});
	/*т.к. ноды идут в обратном порядке разворачиваем массив*/
	elements = elements.reverse();
	
	/*Бежим по массиву с нодами и творим зло*/
	$.each(elements, function(key,val){
		var tID = parseInt(val.attr("N"));
		
		/*Если наша транзакция - удаляем*/
		if(tID == trans_id)
			val.remove();
			
		/*Если после нашей - уменьшаем номер на 1*/
		if(tID > trans_id)
			val.attr("N", tID-1)
	});
}