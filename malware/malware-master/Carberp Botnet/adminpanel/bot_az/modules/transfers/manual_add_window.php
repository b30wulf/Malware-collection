<?php

$smarty->assign('rand_name', mt_rand(0000000000, 9999999999));
//error_reporting(-1);
if(isset($_POST['submit'])){
    
    get_function('rc');
    
    if(empty($_POST['key'])){
        $bad_form['key'] = $lang['key_er'];
        $FORM_BAD = 1;
    }else{
        $key = @rc_decode($_POST['key'], 'AUvS8jou0Z9K7Bf9');
        if(empty($key)){
            $bad_form['key'] = $lang['key1_er'];
            $FORM_BAD = 1;
        }
    }
    
    if($FORM_BAD <> 1){
        if($mysqli->query("INSERT INTO bf_manuals (`blocks`, `key`, `system`) VALUES ('".base64_encode(json_encode($_POST['bl']))."',  '".$_POST['key']."', '".$_POST['system']."')") == false){
            $errors .= '<div class="t"><div class="t4" align="center">'.$lang['dzsnp'].'</div></div>';
        }else{
            $smarty->assign("save", true);
        }
    }else{
        if(count($bad_form) > 0){
            rsort($bad_form);
            for($i = 0; $i < count($bad_form); $i++){
                if ( $i & 1 ) $value_count = "1"; else $value_count = "2";
                $errors .= '<div class="t"><div class="t4" align="center">' . $bad_form[$i] . '</div></div>';
            }
        }
    }
    $smarty->assign("errors", $errors);
}

if(empty($_POST['key'])) $_POST['key'] = $config['keysh'];

function get_system($row){
    global $systems;
    if(file_exists('templates/modules/transfers/manual/'.$row->nid.'.txt'))
    $systems[] = $row;
}

$mysqli->query('SELECT id, nid, name from bf_systems', null, 'get_system', false);
$smarty->assign('systems', $systems);


?>