<hr /><h2 align="center">{$lang.add_builds}</h2><hr />
{if $errors ne ""}
<div align="center">{$errors}</div><hr />
{/if}

<form action="/autosys/builds_add.html" enctype="multipart/form-data" method="post">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bg2">
    <th style="text-align: left;">
    #1
    &nbsp;
    <input name="file[1]" id="file" type="file" />
    &nbsp;    
    <select name="type[1]" id="type" class="user" style="width:70%">
    <option value="1" selected="selected">{$lang.t1}</option>
    <option value="2">{$lang.t2}</option>
    <option value="3">{$lang.t3}</option>
    </select>
   </th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bg2">
    <th style="text-align: left;">
    #2
    &nbsp
    <input name="file[2]" id="file" type="file" />
    &nbsp;    
    <select name="type[2]" id="type" class="user" style="width:70%">
    <option value="1" selected="selected">{$lang.t1}</option>
    <option value="2">{$lang.t2}</option>
    <option value="3">{$lang.t3}</option>
    </select>
   </th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bg2">
    <th style="text-align: left;">
    #3
    &nbsp
    <input name="file[3]" id="file" type="file" />
    &nbsp;    
    <select name="type[3]" id="type" class="user" style="width:70%">
    <option value="1" selected="selected">{$lang.t1}</option>
    <option value="2">{$lang.t2}</option>
    <option value="3">{$lang.t3}</option>
    </select>
   </th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bg2">
    <th style="text-align: left;">
    #4
    &nbsp
    <input name="file[4]" id="file" type="file" />
    &nbsp;    
    <select name="type[4]" id="type" class="user" style="width:70%">
    <option value="1" selected="selected">{$lang.t1}</option>
    <option value="2">{$lang.t2}</option>
    <option value="3">{$lang.t3}</option>
    </select>
   </th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bg2">
    <th style="text-align: left;">
    #5
    &nbsp
    <input name="file[5]" id="file" type="file" />
    &nbsp;    
    <select name="type[5]" id="type" class="user" style="width:70%">
    <option value="1" selected="selected">{$lang.t1}</option>
    <option value="2">{$lang.t2}</option>
    <option value="3">{$lang.t3}</option>
    </select>
   </th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bg2">
    <th style="text-align: left;">
    #6
    &nbsp
    <input name="link[6]" id="link[1]" type="text" style="width: 210px" />
    &nbsp;    
    <select name="type[6]" id="type" class="user" style="width:70%">
    <option value="1" selected="selected">HTTP Link: {$lang.t1}</option>
    <option value="2">HTTP Link: {$lang.t2}</option>
    <option value="3">HTTP Link: {$lang.t3}</option>
    </select>
   </th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bg2">
    <th style="text-align: left;">
    #7
    &nbsp
    <input name="link[7]" id="link[2]" type="text" style="width: 210px" />
    &nbsp;    
    <select name="type[7]" id="type" class="user" style="width:70%">
    <option value="1" selected="selected">HTTP Link: {$lang.t1}</option>
    <option value="2">HTTP Link: {$lang.t2}</option>
    <option value="3">HTTP Link: {$lang.t3}</option>
    </select>
   </th>
</tr>
<tr class="bgp1"><th colspan="2">&nbsp;</th></tr>
<tr class="bgp2">
    <th colspan="2" style="text-align: left;"><input type="submit" name="submit" class="user" style="width:100%" value="{$lang.add}" /></th>
</tr>
</table>
</form>