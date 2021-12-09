<?php

/* То что приходит от бота */
function avc_replace($str){
    $av['avgcsrvx.exe'] = 'avg';
    $av['avgemcx.exe'] = 'avg';
    $av['avgidsagent.exe'] = 'avg';
    $av['avgnsx.exe'] = 'avg';
    $av['avgrsx.exe'] = 'avg';
    $av['avgtray.exe'] = 'avg';
    $av['avgwdsvc.exe'] = 'avg';
    $av['vprot.exe'] = 'avg';
    $av['toolbarupdater.exe'] = 'aware';
    $av['avgfws.exe'] = 'avg';
    $av['avastsvc.exe'] = 'avast';
    $av['avastui.exe'] = 'avast';
    $av['afwserv.exe'] = 'avast';
    $av['avguard.exe'] = 'avira';
    $av['avshadow.exe'] = 'avira';
    $av['avgnt.exe'] = 'avira';
    $av['sched.exe'] = 'avira';
    $av['avwebgrd.exe'] = 'avira';
    $av['avmailc.exe'] = 'avira';
    $av['avfwsvc.exe'] = 'avira';
    $av['egui.exe'] = 'nod32';
    $av['ekrn.exe'] = 'nod32';
    $av['dwengine.exe'] = 'dr.web';
    $av['dwservice.exe'] = 'dr.web';
    $av['dwnetfilter.exe'] = 'dr.web';
    $av['frwl_svc.exe'] = 'dr.web';
    $av['frwl_notify.exe'] = 'dr.web';
    $av['spideragent.exe'] = 'dr.web';
    $av['avp.exe'] = 'kaspersky';
    $av['op_mon.exe'] = 'outpost';
    $av['acs.exe'] = 'outpost';
    $av['ccsvchst.exe'] = 'norton';
    $av['elogsvc.exe'] = 'norman';
    $av['nhs.exe'] = 'norman';
    $av['nigsvc32.exe'] = 'norman';
    $av['niguser.exe'] = 'norman';
    $av['njeeves.exe'] = 'norman';
    $av['nnf.exe'] = 'norman';
    $av['npfsvc32.exe'] = 'norman';
    $av['npfuser.exe'] = 'norman';
    $av['nprosec.exe'] = 'norman';
    $av['npsvc32.exe'] = 'norman';
    $av['nsesvc.exe'] = 'norman';
    $av['nvcoas.exe'] = 'norman';
    $av['nvoy.exe'] = 'norman';
    $av['zanda.exe'] = 'norman';
    $av['zlh.exe'] = 'norman';
    $av['popwndexe.exe'] = 'rising';
    $av['ravmond.exe'] = 'rising';
    $av['rsmgrsvc.exe'] = 'rising';
    $av['rstray.exe'] = 'rising';
    $av['cfp.exe'] = 'comodo';
    $av['clps.exe'] = 'comodo';
    $av['clpsls.exe'] = 'comodo';
    $av['cmdagent.exe'] = 'comodo';
    $av['unsecapp.exe'] = 'comodo';
    $av['avkproxy.exe'] = 'gdata';
    $av['avkservice.exe'] = 'gdata';
    $av['avktray.exe'] = 'gdata';
    $av['avkwctl.exe'] = 'gdata';
    $av['gdscan.exe'] = 'gdata';
    $av['gdfirewalltray.exe'] = 'gdata';
    $av['gdfwsvc.exe'] = 'gdata';
    $av['akvbackupservice.exe'] = 'gdata';
    $av['tsnxgservice.exe'] = 'gdata';
    $av['bdagent.exe'] = 'bitdefender';
    $av['vsserv.exe'] = 'bitdefender';
    $av['updatesrv.exe'] = 'bitdefender';
    $av['uiwatchdog.exe'] = 'titanium';
    $av['coreserviceshell.exe'] = 'titanium';
    $av['coreframeworkhost.exe'] = 'titanium';
    $av['uiseagnt.exe'] = 'titanium';
    $av['pctssvc.exe'] = 'pctools';
    $av['pctsauxs.exe'] = 'pctools';
    $av['pctsgui.exe'] = 'pctools';
    $av['fpavserver.exe'] = 'f-prot';
    $av['fprottray.exe'] = 'f-prot';
    $av['agent.exe'] = 'immunet';
    $av['iptray.exe'] = 'immunet';
    $av['psimsvc.exe'] = 'panda';
    $av['pshost.exe'] = 'panda';
    $av['pavsrvx86.exe'] = 'panda';
    $av['psctrls.exe'] = 'panda';
    $av['pavjobs.exe'] = 'panda';
    $av['psksvc.exe'] = 'panda';
    $av['pavfnsvr.exe'] = 'panda';
    $av['tpsrv.exe'] = 'panda';
    $av['webproxy.exe'] = 'panda';
    $av['avengine.exe'] = 'panda';
    $av['pavprsrv.exe'] = 'panda';
    $av['srvload.exe'] = 'panda';
    $av['apvxdwin.exe'] = 'panda';
    $av['pavbckpt.exe'] = 'panda';
    $av['fsorsp.exe'] = 'f-secure';
    $av['fsgk32st.exe'] = 'f-secure';
    $av['fshoster32.exe'] = 'fsecure';
    $av['fsgk32.exe'] = 'f-secure';
    $av['fsma32.exe'] = 'f-secure';
    $av['fsdfwd.exe'] = 'f-secure';
    $av['fsm32.exe'] = 'f-secure';
    $av['msseces.exe'] = 'mss';
    $av['mcagent.exe'] = 'mcafee';
    $av['mcshield.exe'] = 'mcafee';
    $av['mcsvhost.exe'] = 'mcafee';
    $av['mfefire.exe'] = 'mcafee';
    $av['mfevtps.exe'] = 'mcafee';
    $av['mcpvtray.exe'] = 'mcafee';
    $av['bullguard.exe'] = 'bullguard';
    $av['bullguardbhvscanner.exe'] = 'bullguard';
    $av['bullguardscanner.exe'] = 'bullguard';
    $av['bullguardupdate.exe'] = 'bullguard';
    $av['emlproxy.exe'] = 'quickheal';
    $av['onlinent.exe'] = 'quickheal';
    $av['opssvc.exe'] = 'quickheal';
    $av['quhlsvc.exe'] = 'quickheal';
    $av['sapissvc.exe'] = 'quickheal';
    $av['scanmsg.exe'] = 'quickheal';
    $av['scanwscs.exe'] = 'quickheal';
    $av['sbamsvc.exe'] = 'aware';
    $av['sbantray.exe'] = 'vipre';
    $av['sbpimsvc.exe'] = 'vipre';
    $av['vbcmserv.exe'] = 'vexira';
    $av['vbsystry.exe'] = 'vexira';
    $av['adaware.exe'] = 'aware';
    $av['adawarebp.exe'] = 'aware';
    $av['adawareservice.exe'] = 'aware';
    $av['wajamupdater.exe'] = 'aware';
    $av['arcaconfsv.exe'] = 'arcavir';
    $av['arcamainsv.exe'] = 'arcavir';
    $av['arcaremotesvc.exe'] = 'ikarus';
    $av['arcataskservice.exe'] = 'ikarus';
    $av['avmenu.exe'] = 'ikarus';
    $av['guardxkickoff.exe'] = 'ikarus';
    $av['guardxservicce.exe'] = 'ikarus';
    $av['confirm.dll'] = 'immunity';
    $av['core.dll'] = 'immunity';
    $av['flash.dll'] = 'immunity';
    $av['imun.dll'] = 'immunity';
    $av['imunsvc.exe'] = 'immunity';
    $av['net.exe'] = 'immunity';
    $av['net1.exe'] = 'immunity';
    $av['share.dll'] = 'immunity';
    $av['cmd.exe'] = 'immunity';
    $av['ping.exe'] = 'immunity';
    $av['panda_url_filtering.exe'] = 'pandacloudantivirus';
    $av['psanhost.exe'] = 'pandacloudantivirus';
    $av['psunmain.exe'] = 'pandacloudantivirus';
    $av['solocfg.exe'] = 'solo';
    $av['solosent.exe'] = 'solo';
    $av['vba32ldr.exe'] = 'vba32';
    $av['vbascheduler.exe'] = 'vba32';
    $av['avgam.exe'] = 'avg';
    $av['avgchsvx.exe'] = 'avg';
    $av['avgemc.exe'] = 'avg';
    $av['avgfws9.exe'] = 'avg';
    $av['avgfrw.exe'] = 'avg';
    $av['avgidsmonitor.exe'] = 'avg';
    $av['defensewall.exe'] = 'defence wall';
    $av['defensewall_serv.exe'] = 'defence wall';
    $av['fsav32.exe'] = 'f-secure';
    $av['fshdll32.exe'] = 'f-secure';
    $av['fssm32.exe'] = 'f-secure';
    $av['nuaa.exe'] = 'norman';
    $av['nip.exe'] = 'norman';
    $av['npcsvc32.exe'] = 'norman';
    $av['oacat.exe'] = 'online armour';
    $av['oahlp.exe'] = 'online armour';
    $av['oasrv.exe'] = 'online armour';
    $av['oaui.exe'] = 'online armour';
    $av['sbpfcl.exe'] = 'sunbelt';
    $av['sbpflunch.exe'] = 'sunbelt';
    $av['sbpfsvc.exe'] = 'sunbelt';
    $av['spidergate.exe'] = 'dr.web';
    $av['spiderml.exe'] = 'dr.web';
    $av['spidernt.exe'] = 'dr.web';
    $av['spiderui.exe'] = 'dr.web';
    $av['mobkbackup.exe'] = 'mcafee';
    $av['mpfalert.exe'] = 'mcafee';
    $av['certificationmanagerservicent.exe'] = 'sophos';
    $av['managmentagentnt.exe'] = 'sophos';
    $av['mgntsvc.exe'] = 'sophos';
    $av['routernt.exe'] = 'sophos';
    $av['sbeconsole.exe'] = 'sophos';
    $av['sophosupdatemgr.exe'] = 'sophos';
	
    $str = strtolower($str);
    array_change_key_case_unicode($av, CASE_LOWER);
    
    if(isset($av[$str])){
        return $av[$str];
    }else{
        return false;
    }
}

/* То что приходит от scan4you.net и других */
function av_replace($str){    
    $av['AVG Free'] = 'avg';
    $av['Avast 5'] = 'avast';
    $av['AntiVir (Avira)'] = 'avira';
    $av['Dr.Web'] = 'dr.web';
    $av['Kaspersky Antivirus'] = 'kaspersky';
    $av['ESET NOD32'] = 'nod32';
    $av['Sophos'] = 'sophos';
    $av['Comodo'] = 'comodo';
    $av['COMODO Internet Security'] = 'comodo';
    $av['McAfee SiteAdvisor'] = 'mcafee';
    $av['F-Secure Internet Security'] = 'f-secure';
    $av['MS Security Essentials'] = 'mcafee';
    $av['f-prot antivirus'] = 'f-prot';
    $av['vba32 antivirus'] = 'vba32';
    $av['g data'] = 'gdata';
    $av['panda security'] = 'panda';
    $av['Trend Micro Internet Security'] = 'trendmicro';
    $av['immunet antivirus'] = 'immunet';
    $av['vexira antivirus'] = 'vexira';
    $av['solo antivirus'] = 'solo';
    $av['rising antivirus'] = 'rising';
    $av['quick heal antivirus'] = 'quickheal';
    $av['norton antivirus'] = 'norton';
    $av['ikarus security'] = 'ikarus';
    $av['clam antivirus'] = 'clamav';
    
    /*
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    $av[''] = '';
    */
    
    $av['nod32'] = 'nod32';
    $av['fprot'] = 'f-prot';
    $av['drweb'] = 'dr.web';
    $av['nod32 download'] = 'nod32';
    
    $str = strtolower($str);
    $av = array_change_key_case_unicode($av, CASE_LOWER);

    if(isset($av[$str])){
        return $av[$str];
    }else{
        return $str;
    }
}

function math_prio($str){
    $av['avast'] = 1;
    $av['avira'] = 1;
    $av['nod32'] = 1;
    $av['dr.web'] = 1;
    $av['kaspersky'] = 1;

    $str = strtolower($str);
    $av = array_change_key_case_unicode($av, CASE_LOWER);

    if(isset($av[$str])){
        return $av[$str];
    }else{
        return 0.1;
    }
}

function array_change_key_case_unicode($arr, $c = CASE_LOWER) {
    $c = ($c == CASE_LOWER) ? MB_CASE_LOWER : MB_CASE_UPPER;
    foreach ($arr as $k => $v) {
        $ret[mb_convert_case($k, $c, "UTF-8")] = $v;
    }
    return $ret;
}

?>