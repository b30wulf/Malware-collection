<?php
    // MySQL Database interface class
    class DB {
        private $sql;
        private $mysql;
        private $result;
        private $db;

        public $serialize_bool = true; // Arrays are serialized automatically, booleans too?

        function __construct($db,$user,$pass,$server='localhost'){
            $this->mysql = mysql_connect($server,$user,$pass);
            if(!$this->mysql){
	            trigger_error('Mysql error "'.mysql_error().'"');
            }
            if(!@mysql_select_db($db,$this->mysql)){
	            trigger_error('Mysql error "'.mysql_error().'"');
            }
            $this->db = $db;
        }

        public function select($table,$where=array(),$limit=false,$order=false,$where_mode="AND",$print_query=false){
            $this->result = null;
            $this->sql = null;
            $query = 'SELECT * FROM `'.$table.'`';
            if(!empty($where)){
                $query .= ' WHERE';
                if(is_array($where)){
                    $nr = 0;
                    foreach($where as $k => $v){
                        if(substr($v,0,2) == 'in'){
                            $query .= ' `'.$k."`  ".mysql_real_escape_string($v);
                        }elseif(!is_integer($k)){
                            $query .= ' `'.$k."`='".mysql_real_escape_string($v)."'";
                        }else{
                            $query .= ' '.$v;
                        }
                        $nr++;
                        if($nr != count($where)){ $query .= ' '.$where_mode; }
                    }
                }else{
                    $query .= ' '.$where;
                }
            }
            if($order){
                $query .= ' ORDER BY '.$order;
            }
            if($limit){
                $query .= ' LIMIT '.$limit;
            }
            if($print_query){
            echo $query;
            }
            return $this->query($query);
        }

        public function query($query){
            $this->sql = $query;
            $this->result = mysql_query($query,$this->mysql);
            if(mysql_error($this->mysql) != ''){
                echo '<br /><br /><b>Fatal Database error:</b> '.mysql_error($this->mysql).'<br />';
                $this->result = null;
            }
            return $this;
        }

        public function sql(){
            return $this->sql;
        }

        public function result_raw(){
            return $this->result;
        }

        public function result(){
            if($this->result){
                $result = array();
                $n = 0;
                while($row = mysql_fetch_assoc($this->result)){
                    $result[$n] = new stdClass();
                    foreach($row as $k=>$v){
                        if($this->is_serialized($v)){ $v = unserialize($v); }
                        if(is_array($v)){
                            $result[$n]->{$k} = new stdClass();
                            foreach($v as $k2=>$v2){
                                if(is_array($v2)){
                                    $result[$n]->{$k}->{$k2} = new stdClass();
                                    foreach($v2 as $k3=>$v3){
                                        $result[$n]->{$k}->{$k2}->{$k3} = $v3;
                                    }
                                }else{
                                    $result[$n]->{$k}->{$k2} = $v2;
                                }
                            }
                        }else{
                            $result[$n]->{$k} = $v;
                        }
                    }
                    $n++;
                }
                return $result;
            }
        }

        public function result_array(){
            if($this->result){
                $result = array();
                $n = 0;
                while($row = mysql_fetch_assoc($this->result)){
                    $result[$n] = array();
                    foreach($row as $k=>$v){
                        if($this->is_serialized($v)){ $v = unserialize($v); }
                        if(is_array($v)){
                            $result[$n][$k] = array();
                            foreach($v as $k2=>$v2){
                                $result[$n][$k][$k2] = $v2;
                            }
                        }else{
                            $result[$n][$k] = $v;
                        }
                    }
                    $n++;
                }
                return $result;
            }
        }

        public function row($r=0){
            if($this->result){
                $nr = 0;
                while($c = mysql_fetch_assoc($this->result)){
                    if($nr == $r){
                        $row = new stdClass();
                        foreach($c as $k=>$v){
                            if($this->is_serialized($v)){ $v = unserialize($v); }
                            $row->{$k} = $v;
                        }
                        return $row;
                    }
                    $nr++;
                }
            }
        }

        public function row_array($r=0){
            if($this->result){
                $nr = 0;
                while($c = mysql_fetch_assoc($this->result)){
                    if($nr == $r){
                        $row = array();
                        foreach($c as $k=>$v){
                            if($this->is_serialized($v)){ $v = unserialize($v); }
                            $row[$k] = $v;
                        }
                        return $row;
                    }
                    $nr++;
                }
            }
        }

        public function count(){
            if($this->result){
                return mysql_num_rows($this->result);
            }
        }

        function table_exists($name) {
	        $res = mysql_query("SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_schema = '".mysql_real_escape_string($this->db)."' AND table_name = '".mysql_real_escape_string($name)."'", $this->mysql);
	        return (mysql_result($res, 0) == 1);
	    }

        /* Insert/update functions */
        function insert($table, $fields=array()){
            $this->result = null;
            $this->sql = null;
            $query = 'INSERT INTO `'.mysql_real_escape_string($table).'`';
            if(is_array($fields)){
                $query .= ' (';
                $nr = 0;
                foreach($fields as $k => $v){
                    $query .= ' `'.$k.'`';
                    $nr++;
                    if($nr != count($fields)){ $query .= ','; }
                }
                $query .= ' ) VALUES (';

                $nr = 0;
                foreach($fields as $k => $v){
                    if(is_array($v) || ($this->serialize_bool && is_bool($v))){ $v = serialize($v); }
                    $query .= " '".mysql_real_escape_string($v)."'";
                    $nr++;
                    if($nr != count($fields)){ $query .= ','; }
                }
                $query .= ' )';
            }else{
                $query .= ' '.$fields;
            }
            $this->sql = $query;
            $this->result = mysql_query($query,$this->mysql);
            if(mysql_error($this->mysql) != ''){
                echo '<br /><br /><b>Fatal Database error:</b> '.mysql_error($this->mysql).'<br />';
                $this->result = null;
                return false;
            }else{
                return $this;
            }
        }

        function update($table, $fields=array(), $where=array()){
            $this->result = null;
            $this->sql = null;
            $query = 'UPDATE `'.$table.'` SET';
            if(is_array($fields)){
                $nr = 0;
                foreach($fields as $k => $v){
                    if(is_array($v) || ($this->serialize_bool && is_bool($v))){ $v = serialize($v); }
                    $query .= ' `'.$k."`='".mysql_real_escape_string($v)."'";
                    $nr++;
                    if($nr != count($fields)){ $query .= ','; }
                }
            }else{
                $query .= ' '.$fields;
            }
            if(!empty($where)){
                $query .= ' WHERE';
                if(is_array($where)){
                    $nr = 0;
                    foreach($where as $k => $v){
                        $query .= ' `'.$k."`='".mysql_real_escape_string($v)."'";
                        $nr++;
                        if($nr != count($where)){ $query .= ' AND'; }
                    }
                }else{
                    $query .= ' '.$where;
                }
            }
            $this->sql = $query;
            $this->result = mysql_query($query,$this->mysql);
            if(mysql_error($this->mysql) != ''){
                echo '<br /><br /><b>Fatal Database error:</b> '.mysql_error($this->mysql).'<br />';
                $this->result = null;
                return false;
            }else{
                return $this;
            }
        }

        function delete($table, $where=array()){
            $this->result = null;
            $this->sql = null;
            $query = 'DELETE FROM `'.$table.'`';
            if(!empty($where)){
                $query .= ' WHERE';
                if(is_array($where)){
                    $nr = 0;
                    foreach($where as $k => $v){
                        $query .= ' `'.$k."`='".mysql_real_escape_string($v)."'";
                        $nr++;
                        if($nr != count($where)){ $query .= ' AND'; }
                    }
                }else{
                    $query .= ' '.$where;
                }
            }
            $this->sql = $query;
            $this->result = mysql_query($query,$this->mysql);
            if(mysql_error($this->mysql) != ''){
                echo '<br /><br /><b>Fatal Database error:</b> '.mysql_error($this->mysql).'<br />';
                $this->result = null;
                return false;
            }else{
                return $this;
            }
        }

        public function id(){
            return mysql_insert_id($this->mysql);
        }

        /* Helper functions */
        private function is_serialized($str){
        	try{
        		if($str == 'b:0;' || preg_match('/^([adObis]:|N;)/',$str)){
        			return true;
                }
        	}catch(Exception $e){}

            return false;
        }

        final public static function getInstance() {
		// Singleton code to get instance for the use of e();
        static $aoInstance = array();

        $calledClassName = get_called_class();

        if (! isset ($aoInstance[$calledClassName])) {
            $aoInstance[$calledClassName] = new $calledClassName();
        }

        return $aoInstance[$calledClassName];
    }
    }
?>
