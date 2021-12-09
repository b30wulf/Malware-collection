{if $parent->host eq ''}
<center ><h2><span style="color: #F00">Внимание!</span><br />Вы хотите удалить раздел!</h2></center>
<br />
<div style="font-size: 16px; margin-left: 50px;">
<div style="margin-left: 100px;">Информация о фильтрах, в фильтрах, о разделах, в разделах будет полностью утрачена!</div><br />
<div style="margin-left: 100px;">При удаление раздела удаляться все внутренние разделы и фильтры!</div><br />
<div style="margin-left: 100px;">Если в разделе и во всех подразделах есть рабочие фильтры,<br />то они удаляться и все данные фильтров этих тоже удаляться.</div><br />
<div style="margin-left: 100px;">После удаления раздела, невозможно будет восстановить данные фильтров!</div><br />
</div>

<center>

<h2>Точно удалить раздел?</h2>
(<span style="font-size:14px">{$parent->name}</span>)
<br /><br />
<form action="" method="post">
<input type="submit" name="yes" value="Да, удалить!" />
<input type="button" name="no" value="Нет, не удалять!" onclick="location.href = '/manager/';" />
</form>
</center>

{else}
<center ><h2><span style="color: #F00">Внимание!</span><br />Вы хотите удалить фильтр!</h2></center>
<br />
<div style="font-size: 16px; margin-left: 50px;">
<div style="margin-left: 100px;">Сам фильтр и вся информация будет удалена без возможности востановления!</div><br />
</div>

<center>

<h2>Точно удалить фильтр?</h2>
(<span style="font-size:14px">{$parent->name}</span>)
<br /><br />
<form action="" method="post">
<input type="submit" name="yes" value="Да, удалить!" />
<input type="button" name="no" value="Нет, не удалять!" onclick="location.href = '/manager/';" />
</form>
</center>
{/if}