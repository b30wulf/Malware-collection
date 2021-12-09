<?php
class manager{
    public $settings, $errors, $socket, $start;
    
    function __construct(){
        $this->errors = array();
        
        $this->settings = array();
        $this->settings["host"] = '127.0.0.1';
        $this->settings["port"] = '689';
        
        $this->socket = false;
    }
    
    function connect(){
        if($this->socket === false){
            $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if($this->socket != false){
                $result = socket_connect($this->socket, $this->settings["host"], $this->settings["port"]);
                if ($result !== false) {
                    socket_set_nonblock($this->socket);
                    if(count($this->start) > 0){
                        foreach($this->start as $item){
                            $this->write($item);
                        }
                    }
                    return true;
                }else{
                    $this->close();
                    return false;
                }
            }else{
                $this->close();
                return false;
            }
        }else{
            return true;
        }
    }
    
    function shutdown($how = 2){
        @socket_shutdown($this->socket, $how);
    }
    
    function close(){
        $this->shutdown(2);
        @socket_close($this->socket);
        $this->socket = false;
    }
    
    function set($str){
        $result = $this->write($str);
        if($result == false){
            $this->close();
            $this->connect();
            $this->set($str);
        }
    }
    
    function get($str){
        $this->set($str);
        usleep(10000);
        return $this->read();
    }
    
    function write($str){
        $result = socket_write($this->socket, $str . "\r\n", strlen($str. "\r\n"));
        $this->shutdown(1);
        return $result;
    }
    
    function read(){
        socket_recv($this->socket, $buf, 65536, MSG_WAITALL);
        $this->shutdown(0);
        return $buf;
    }
    
    function parse_status($str){
        $str = explode("\r\n", $str);
        $list = array();
        
        foreach($str as $f){
            $f = explode("\t", $f);
            switch($f[0]){
                case 'TITLE':
                    $list['title'] = $f[1];
                break;
                
                case 'TIME':
                    $list['time'] = array($f[1], $f[2]);
                break;
                
                case 'CLIENT_LIST':
                    $tmp = array();
                    $tmp['name'] = $f[1];
                    $tmp['ip'] = $f[2];
                    $tmp['nip'] = $f[3];
                    $tmp['received'] = $f[4];
                    $tmp['sent'] = $f[5];
                    $tmp['time'] = $f[6];
                    $tmp['timec'] = $f[7];
                    $list['list'][$f[3]]  = $tmp;
                    unset($tmp);
                break;
            }
        }
        return $list;
    }
}
?>