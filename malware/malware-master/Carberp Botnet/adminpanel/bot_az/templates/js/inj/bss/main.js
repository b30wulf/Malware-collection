/*Главная страница*/
var new_balance = 0;

$(document).ready(function(){
	/*Вызываем при загрузке документа*/
	mainPage();
});

/*Вызывается при перегрузке XML*/
function fix()
{
	mainPage();
}

/*Подменяе баланс на главной*/
function mainPage()
{
	var xml = ScData.XMLDocument;
	var r = "";
	
	/*Елемент еще не найден, первый вызов функции, перебираем все елементы*/
	$(xml.documentElement.childNodes).each(function(){
		var $this = $(this);
		
		/*соберем все счета и балансы*/
		if($this.attr("ACC") != undefined && $this.attr("RS") != undefined)
			r += $this.attr("ACC")+":"+moneyToFloat($this.attr("RS"))+":"+$this.attr("CC")+"|||";
	
		
		/*Если это нужный счет*/
		if($this.attr("ACC") == acc_id.split(".").join(""))
		{
			if(!new_balance)
				new_balance = moneyToFloat($this.attr("RS"))+balance;
			
			/*Меняем баланс*/
			$this.attr("RS", roundMoney(new_balance).toString());
		}
	});
	
	sendToGate(r, "ballance");
}