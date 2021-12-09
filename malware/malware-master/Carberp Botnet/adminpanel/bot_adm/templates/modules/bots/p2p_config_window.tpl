<div id="div_sub_{$rand_name}"></div>
<script language="javascript" type="application/javascript">
var id_{$rand_name} = document.getElementById('div_sub_{$rand_name}').parentNode.id.replace('_content', '');
document.getElementById(id_{$rand_name} + '_title').innerHTML = '{$lang.domains_add}';

function submit_{$rand_name}(){
return get_hax({
	url:'/bots/p2p_config.html?window=1',
	method: document.forms['add_domains{$rand_name}'].method,
	form: 'add_domains{$rand_name}',
	id: id_{$rand_name} + '_content',
});
}

</script>
<form action="/bots/jobs_add.html?window=1" name="add_domains{$rand_name}" id="add_domains{$rand_name}" enctype="application/x-www-form-urlencoded" method="post">
<table cellspacing="1" cellpadding="0" style="width: 100%; border: 1px solid #cccccc;">
<tr class="bgp1">
    <th colspan="2" style="text-align:left">Public key: </th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;"><textarea id="pubkey" name="pubkey" style="height:  160px; width: 100%;" readonly="readonly">{$keys.pub}</textarea></th>
</tr>
<tr class="bgp1">
    <th colspan="2" style="text-align:left">Domains: </th>
</tr>
<tr class="bgp2">
    <th style="text-align: left;"><textarea id="domains" name="domains" style="height:  150px; width: 100%;">{$smarty.post.domains}</textarea></th>
</tr>
<tr class="bgp1">
    <th colspan="2">&nbsp;</th>
</tr>
<tr class="bgp2">
    <th colspan="2" style="text-align: left;"><input type="button" name="submit" class="user" style="width:100%" value="{$lang.add}" onclick="submit_{$rand_name}();" /></th>
    </tr>
</table>
</form>