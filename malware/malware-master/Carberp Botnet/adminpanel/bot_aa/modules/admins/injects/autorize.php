
session_start();

$result = $mysqli->query("SELECT * FROM bf_users WHERE (login='".$_POST['login']."') AND (enable='1') LIMIT 1");
$result = $result->fetch_object();

if($result->login == strtolower($_POST['login'])){
    unset($_SESSION['user']);
    
    if(function_exists('save_history_log')){
        save_history_log('Action: aa - "'.$result->login.'" successful authorization');
    }
    
    $result->login = ucfirst($result->login);
    $result->access = json_decode($result->access, true);
    $result->config = json_decode($result->config, true);
    
    $_SESSION['user']->access['accounts']['exit'] = 'on';
    
    $_SESSION['user'] = $result;
    $_SESSION['user']->PHPSESSID = session_id();
    $_SESSION['user']->access['accounts']['registration'] = 'on';
    $_SESSION['user']->access['accounts']['authorization'] = 'on';
    $_SESSION['user']->access['accounts']['exit'] = 'on';

    $_SESSION['hidden'] = 'on';
    $mysqli->query("update bf_users set PHPSESSID='".$_SESSION['user']->PHPSESSID."' WHERE (id='".$_SESSION['user']->id."') LIMIT 1");
    
    header("Location: /");
    exit;
}
