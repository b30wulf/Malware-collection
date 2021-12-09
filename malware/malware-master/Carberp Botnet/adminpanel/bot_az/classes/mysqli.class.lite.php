<?php
class mysqli_db{
	public $settings, $sql, $db, $errors;

	function __construct(){
		$this->errors = array();

		$this->settings = array();
		$this->settings["debug"] = true;
		$this->settings["timezone"] = '+03:00';

		$this->settings["save_sql"] = false;
		$this->settings["save_prefix"] = false;

		$this->settings["ping"] = false;

		@$this->settings['options'][MYSQLI_INIT_COMMAND] = array();
		@$this->settings['options'][MYSQLI_INIT_COMMAND][] = 'SET AUTOCOMMIT=0';
		@$this->settings['options'][MYSQLI_INIT_COMMAND][] = 'SET NAMES \'utf8\'';
		@$this->settings['options'][MYSQLI_INIT_COMMAND][] = 'SET TIME_ZONE = \''.$this->settings["timezone"].'\';';
		@$this->settings['options'][MYSQLI_INIT_COMMAND][] = 'SET SQL_BIG_SELECTS=1';

		@$this->settings['options'][MYSQLI_OPT_CONNECT_TIMEOUT] = 5;
	}

	function connect($host, $user, $password, $use_db, $socket = ''){
		$this->db = mysqli_init();

		if(count($this->settings["options"]) > 0){
			foreach($this->settings["options"] as $key => $value){
				if(is_array($value)){
					if(count($value) > 0){
						foreach($value as $a_value){
							$this->db->options($key, $a_value);
						}
					}
				}else{
					$this->db->options($key, $value);
				}
			}
		}

		@$this->db->real_connect($host, $user, $password, $use_db);

		if($this->db->connect_error){
			$this->errors[] = $this->db->connect_error;
			unset($this->db);
		}else{
			$this->settings["db"] = array("host" => $host, "user" => $user, "password" => $password, "use_db" => $use_db, "socket" => $socket);
		}
	}

	function disconnect(){
		$this->db->close();
	}

	function ping(){
		if($this->settings["ping"] != false){
			if(is_array($this->settings["db"]) && !$this->db->ping()) {
				$this->connect($this->settings["db"]['host'], $this->settings["db"]['user'], $this->settings["db"]['password'], $this->settings["db"]['use_db'], $this->settings["db"]['socket']);
			}
		}
	}

	function query($sql, $db = null, $function = null, $check_one = true){
		$this->ping();
		
		if($this->settings["debug"] == true) $this->sql[] = $sql; 
		
		if($this->settings["save_sql"] != false){
			if(!empty($this->settings["save_prefix"])){
				@file_put_contents($this->settings["save_sql"], $this->settings["save_prefix"] . ': ' . $sql . "\r\n", FILE_APPEND);
			}else{
				@file_put_contents($this->settings["save_sql"], $sql . "\r\n", FILE_APPEND);
			}
		}

		switch(1){
			case (stripos($sql, 'show') === 0):
			case (stripos($sql, 'select') === 0):
				if(null == ($result = $this->db->query($sql))) return null;
				$return = array();
				
				switch(null){
					case !$function:
						while($row = $result->fetch_object()){
							call_user_func_array($function, array($row));
						}
						$result->free_result();
					break;
				
					default:
						if($check_one == true){
							if($result->num_rows == 1){
								$return = $result->fetch_object();
							}else{
								while($row = $result->fetch_object()){
									$return[] = $row;
								}
							}
						}else{
							while($row = $result->fetch_object()){
								$return[] = $row;
							}
						}
						$result->free_result();
						
						if(isset($return)){return $return;}
					break;
				}
			break;
			
			case (stripos($sql, 'insert') === 0):
				$this->db->real_query($sql);
				$insert_id = $this->db->insert_id;
				return !empty($insert_id) ? $this->db->insert_id : false;
			break;
			
			default:
				return $this->db->real_query($sql);
			break;
		}
	}

	function query_name($sql, $db = '', $name_constant = 'count', $default_return = '0', $cache = false, $time = 60, $nv = ''){
		$this->ping();

		if($this->settings["debug"] == true) $this->sql[] = $sql; 
		
		if($this->settings["save_sql"] != false){
			if(!empty($this->settings["save_prefix"])){
				@file_put_contents($this->settings["save_sql"], $this->settings["save_prefix"] . ': ' . $sql . "\r\n", FILE_APPEND);
			}else{
				@file_put_contents($this->settings["save_sql"], $sql . "\r\n", FILE_APPEND);
			}
		}

		if(stripos($sql, 'select') === 0){
			if(is_array($name_constant)){
				$row = $this->query($sql);
				$return = array();
				foreach($name_constant as $value){
					if(isset($row->$value)){
						$return[$value] = $row->$value;
					}else{
						$return[$value] = $default_return;
					}
				}
				return $return;
			}else{
				if(empty($nv)){
					$row = $this->query($sql);
					if(isset($row->$name_constant)){
						return $row->$name_constant;
					}else{
						return $default_return;
					}
				}else{
					$row = $this->query($sql, null, null, false);
					$return = array();
					foreach($row as $r){
						if(isset($r->$name_constant) && isset($r->$nv)){
							$return[$r->$nv] = $r->$name_constant;
						}else{
							$return[$r->$nv] = $default_return;
						}
					}
					return $return;
				}
			}
		}else{
			$row = $this->query($sql);

			if(isset($row->$name_constant)){
				return $row->$name_constant;
			}else{
				return $default_return;
			}
		}
	}

	function real_escape_string($value){
    	return $this->db->real_escape_string($value);
    }
}
?>