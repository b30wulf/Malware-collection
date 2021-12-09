<?php

global $__serviceClasses;

$__serviceClasses=array();

function RegisterService($className){
    global $__serviceClasses;
    $__serviceClasses[]=$className;
}

function GetRegisteredServices(){
    global $__serviceClasses;
    $checkedServices=array();
    //выделяем реально существующие классы
    foreach($__serviceClasses as $value){
        try{
            $r=new ReflectionClass($value);
            $checkedServices[]=$value;
        }catch(Exception $e){
            //
        }
    }
    
    $res=array();
    
    foreach($checkedServices as $service){
        $serviceClass=new ReflectionClass($service);
        $methods=$serviceClass->getMethods();
        
        foreach($methods as $method){
            if(strpos($method->name,"_")===0) continue;
            $mthd=getMethodAsJSON($method,$service);
            $res[]=$mthd;
        }
    }
    
    return json_encode($res);
}

function getMethodAsJSON(ReflectionMethod $func,$class){
    $res=array();
    $res['name']=$func->getName();
    $res['params']=array();
    $res['comment']=$func->getDocComment();
    $params = $func->getParameters();
    foreach ($params as $param){
        $res['params'][]= $param->getName();
    }
    $res['class']=$class;
    return json_encode($res);
}

function convert($from, $to, $var){
    if (is_array($var)) {
        $new = array();
        foreach ($var as $key => $val){
            $new[convert($from, $to, $key)] = convert($from, $to, $val);
        }
        $var = $new;
    }else if (is_string($var)) {
        $var = iconv($from, $to, $var);
    }
    return $var;
}

?>