/*Выписки, Документы из банка -> Выписки -> Показать выписку*/

var fields = {}, data = {};
function fixTable()
{
	/*для версий 1-8*/
	if(getVersion() >= 1 || getVersion() <= 8)
		fixTable_1_8();
	/*if(getVersion() == 9)
		fixTable_9();*/
}

/*Исправляем баланс*/
function fixBallance(s)
{
	if(getVersion() != 9)
		return s;
		
	tmp = $(s).find("table tbody tr");
	alert(tmp.find("td:eq(1)").val());
	//alert(tmp.html());
	return "<b>0.0</b>";
}

function fixTable_1_8()
{	
	/*Дата перевода*/
	if(typeof transfer_date == 'string')
		transfer_date = getDate(transfer_date).getTime();
	
	/*Обнуляем значения*/
	fields = {}; data = {};
	var xml = ScData.XMLDocument.documentElement;
	var i=0, moneyPeerDay=0, moneyTotal = 0, good=false, hidden = false;
	var today, firstDate;
	
	/*Пронумеруем поля*/
	$(xml).find("hd c").each(function(){
		var name = $(this).attr("dfld");
		if(!name)
			name = "CBank";
		fields[name] = i;
		i++;
	});
	
	/*Если "документы за день" отображаются*/
	if($(xml.selectSingleNode('//hd/c[@dfld="DocumentNumber"]')).attr("w"))
	{
		/*Оббегаем елементы и начинаем творить зло с выписками :))*/
		$(xml.selectNodes('//bd/r')).each(function(){
			var $this = $(this);
			var opID = parseInt($this.attr("t"));
			hidden = false;
			
			if(opID == 1 && $this.find("c s:eq(1)").text() == acc_id)
				good = true;

			if(good)
			{
				good = true;
				switch(opID)
				{	
					case 0:
						if($this.find("c:eq("+getFieldPosition("ReceiverAccount")+")").text() == acc_id_to+" " && transfer_date == today)
							$this.remove();
						else
						{
							var money = moneyToFloat($this.find("c:eq("+getFieldPosition("D_Amount")+")").text());
							moneyPeerDay += money;
							moneyTotal += money;
						}
						
					break;
					
					/*Сегодняшняя дата*/
					case 2:
						today = $this.find("c s:eq(0)").text();
						today = getDate(today).getTime();
						
						/*Первая дата диапазона*/
						if(!firstDate)
							firstDate = today;
					break;
					
					/*Обороты за день*/
					case 3:
						/*Если отмечена галочка "Обороты за день"*/
						if($("#SDT2").attr("checked"))
						{
							$this.find("c s:eq(1)").text(roundMoney(moneyPeerDay));
							moneyPeerDay = 0;
						}
					break;
					
					/*Остаток на конец дня*/
					case 4:
						if(today >= transfer_date)
						{
							var el = $this.find("c s:eq(1)");
							var moneyDayEnd = moneyToFloat(el.text()) + balance;
							el.html(roundMoney(moneyDayEnd));
							moneyDayEnd = 0;
						}
					break;
					
					/*Сохраним поля содержащие "Итого обороты"*/
					case 5:
						/*Сохраняем поле "Дебит", т.к. "Кредит" ненужен*/
						pushData("t5", $this.find("c s:eq(1)"));
					break;
					
					/*Сохраним поля с цифрами содержащими остаток на конец периода*/
					case 6:
						pushData("t6", $this.find("c s:eq(1)"));
					break;
					
					/*Остаток на начало дня*/
					case 12:
						if(today > transfer_date)
						{
							var el = $this.find("c s:eq(1)");
							var moneyDayStart = moneyToFloat(el.text()) + balance;
							el.html(roundMoney(moneyDayStart));
							moneyDayStart = 0;
						}
					break;
					
					/*Сохраним поля с цифрами содержащими остаток на начало периода*/
					case 13:
						pushData("t13", $this.find("c s:eq(1)"));
					break;
				}
			}
		});

		if(good)
		{
			/*Изменим значения полей "ИТОГО ОБОРОТ"*/
			updateElements(data.t5, moneyTotal, false);
			moneyTotal = 0;
			
			/*Меняем остаток на начало и на конец периода*/
			/*Если перевод был раньше первого дня периода - увиличиваем оба значения на выведеную сумма*/
			/*today - последний день периода, firstDate - первый день периода*/
			if(transfer_date <= firstDate)
			{
				updateElements(data.t6, balance, true);
				updateElements(data.t13, balance, true);
			}
			
			/*Если перевод входит в период меняем только остаток на конец периода*/
			if(transfer_date > firstDate && transfer_date <= today)
				updateElements(data.t6, balance, true);
		}
		
		/*Пробежимся еще разок и удалим "пустые дни" которые остались, если на выбрано нулевые обороты, если выбрано добавим надпись "оборотов небыло" */
		var nullSave = {};
		nullSave.save = false;
		var toDell = [];
		$(xml.selectNodes('//bd/r')).each(function(){
			var $this = $(this);
			var opID = parseInt($this.attr("t"));
			hidden = false;
			
			if(opID == 1 && $this.find("c s:eq(1)").text() == acc_id)
				good = true;

			if(good)
			{
				/*День начался*/
				if(opID == 2)
					nullSave.save = true;
					
				/*Данные за сегодня*/	
				if(nullSave.save)
					toDell.push($this);
				
				/*Есть ли обороты за день*/
				if(opID == 12)
				{
					if($this.next().attr("t") == "0" || $this.next().attr("t") == "7")
					{
						nullSave.save = false;
						toDell = [];
					}
					/*Если выбрана налочка нулевые обороты*/
					if($this.next().attr("t") != "7" && $(xml).attr('NL') == "1")
					{
						var r = ScData.createElement("r");
						var txt = ScData.createTextNode("Оборотов не было");
						$(r).attr("t", "7");
						c = ScData.createElement("c");
						c.appendChild(txt);
						r.appendChild(c);
						$(r).insertAfter(this);
					}
				}
				if(opID == 4 && nullSave.save)
				{
					toDell.push($this);
					
					$.each(toDell, function(){
						if($(xml).attr('NL') == "0")
							this.remove();
							
					});
					
					nullSave.save = false;
				}
				
			}
		});
	}
	
	/*Если "документы за день" не отображаются*/
	/*else
	{
		//alert(0);

	}*/
}
function pushData(field, el)
{
	if(!data[field])
		data[field] = new Array();
	data[field].push(el);
}
function updateElements(d,v,plus)
{
	$(d).each(function(){
		if(!plus)
			$(this).html(roundMoney(v));
		else
		{
			var val = moneyToFloat($(this).text())+v;
			$(this).html(roundMoney(val));
		}
	});
}
function getFieldPosition(find)
{
	return fields[find];
}
