/*Експорт выписок, Документы из банка -> Выписки -> Експорт выписок */
var interval;

$(document).ready(function(){
	/*Ожидаем загрузки документа*/
	interval = setInterval("disableButton()", 100);
});

/*Отключааем кнопку отвечающую за "Експорт"*/
function disableButton()
{
	/*Путь к кнопке*/
	var btnPath = "table#EDITFORM tr td button";
	
	if($(btnPath) != undefined)
	{
		$(btnPath).attr("disabled", true);
		
		/*для версии 9 добавим надпись*/
		$("#ScrollHeader").html("Экспорт выписок в бухгалтерские системы, <font color='red'>временно отключен!</font>");
		
		/*Отключаем интервал*/
		clearInterval(interval);
	}
}