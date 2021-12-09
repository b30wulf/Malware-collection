<script type="text/javascript" src="/js/md5.js"></script>
<form action="" enctype="application/x-www-form-urlencoded" method="post" onsubmit="this.elements['password_autorize'].value = hex_md5 (this.elements['password_autorize'].value); this.elements['info'].value = save_info();">
<input id="info" name="info" type="hidden" />
<h2 align="center">Вход на сайт</h2>
<hr />
<br />
<table border="0" cellspacing="0" cellpadding="10" class="reg" style="width:90%; margin-left: 30px;">
<tr>
  <td style="width: 100px;">Логин:</td>
  <td><input id="login_autorize" name="login" type="text" class="reg_input_obligatory" style="width:100%" /></td>
</tr>
<tr>
  <td>Пароль:</td>
  <td><input id="password_autorize" name="password" type="password" class="reg_input_obligatory" style="width:100%" /></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td><input id="autorize_submit" name="autorize_submit" type="submit" value="Авторизоваться" style="width:100%" /></td>
</tr>
</table>
</form>
<hr />