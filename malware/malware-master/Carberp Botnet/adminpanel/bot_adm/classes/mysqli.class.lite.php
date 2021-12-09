<?php
class mysqli_db{	public $settings, $sql, $db, $errors;

	function __construct(){
		$this->errors = array();

		$this->settings = array();
		$this->settings["debug"] = false;
		$this->settings["timezone"] = '+03:00';

		@$this->settings['options'][MYSQLI_INIT_COMMAND] = array();
		@$this->settings['options'][MYSQLI_INIT_COMMAND][] = 'SET NAMES utf8';
		@$this->settings['options'][MYSQLI_INIT_COMMAND][] = 'SET TIME_ZONE = \''.$this->settings["timezone"].'\';';

		@$this->settings['options'][MYSQLI_OPT_CONNECT_TIMEOUT] = 5;
	}

	function connect($host, $user, $password, $use_db,$socket = ''){
		$mysqli = mysqli_init();

		if(count($this->settings["options"]) > 0){			foreach($this->settings["options"] as $key => $value){				if(is_array($value)){					if(count($value) > 0){						foreach($value as $a_value){							$mysqli->options($key, $a_value);
						}
					}
				}else{					$mysqli->options($key, $value);
				}
			}
		}

		@$mysqli->real_connect($host, $user, $password, $use_db);

		if($mysqli->connect_error){			$this->errors[] = $mysqli->connect_error;
			unset($mysqli);
		}else{			$this->db = $mysqli;
			unset($mysqli);
		}
	}

	function disconnect(){
		$this->db->close();
	}

	function query($sql, $db = null, $function = null, $check_one = true){
		switch(1){
			case (stripos($sql, 'show') === 0):
			case (stripos($sql, 'select') === 0):
			    if(null == ($result = $this->db->query($sql))) return null;

			    switch(null){
			    	case !$function:
			    	    while($row = $result->fetch_object()) call_user_func_array($function, array($row));
			    	    $result->free_result();
			    	break;

            		default:
            			if($check_one == true){
	            			if($result->num_rows == 1){
	            				$return = $result->fetch_object();
	            			}else{
	            				while($row = $result->fetch_object()) $return[] = $row;
	            			}
            			}else{
            				while($row = $result->fetch_object()) $return[] = $row;
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

	function real_escape_string($value){
    	return $this->db->real_escape_string($value);
    }
}
?>