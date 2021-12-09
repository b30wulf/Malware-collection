<?php
if($_GET['go'] != 'index') exit;
?>
<form action="/install/index.html" metod="GET">
<input type="hidden" name="step" value="0" />
<br /><br />
Select language:
<br /><br />
<select name="lang">
<option value="en">English</option>
<option value="ru">Russian</option>
</select>
<br /><br />
<input type="submit" value="OK" />
</form>