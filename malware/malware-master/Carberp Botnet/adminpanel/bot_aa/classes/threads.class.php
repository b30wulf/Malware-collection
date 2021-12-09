<?php

error_reporting(-1);
set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '512M');

class threads {
	public $settings, $dir, $errors, $mysqli;

	function __construct($mysqli){
		$this->time = time();
		$this->count = array();

		$this->errors = array();
		$this->settings = array();
		$this->settings["mp"] = 10;
		$this->settings["uniq"] = '';
		$this->settings["file_proc"] = 'process.php';
		$this->settings["error_file"] = 'process.txt';
		$this->settings["disable_code_error"] = '8|';
		$this->settings["pid_file"] = 'pid.txt';
		$this->settings["set_pid"] = false;
		$this->settings["user_func"] = '';
		$this->settings["exit_script"] = '';
		$this->settings["memory_limit"] = '128M';
		$this->settings["MAX_PROCESS"] = &$this->settings["mp"];
		$this->settings["WIN_LOCALIZE_PID"] = 'PID';
		$this->settings["PHP_EXE"] = 'x:\WebServers\usr\local\php5\php-win.exe';
		$this->settings["IDOS"] = strtoupper(substr(PHP_OS, 0, 3));
	}

	private function get_count(){
		global $mysqli;

		//$this->count['cur'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_threads WHERE (script = \''.$this->settings["uniq"].'\') AND ((status = \'2\') OR (status = \'1\'))');
		//$this->count['task'] = $mysqli->query_name('SELECT COUNT(id) count FROM bf_threads WHERE (script = \''.$this->settings["uniq"].'\') AND (status = \'0\')');
        $this->count = $mysqli->query_name('SELECT COUNT(id) count, status FROM bf_threads WHERE (script = \''.$this->settings["uniq"].'\') GROUP by status', null, 'count', '0', false, 60, 'status');

        if(!isset($this->count[0])) $this->count[0] = 0;
        if(!isset($this->count[1])) $this->count[1] = 0;
        if(!isset($this->count[2])) $this->count[2] = 0;

        $this->count['cur'] = (@$this->count[1] + @$this->count[2]);
        $this->count['task'] = &$this->count[0];

        //print_r($this->count);
	}

	private function del_pid(){
		if(file_exists($this->settings["pid_file"])) @unlink($this->settings["pid_file"]);
	}

	private function ml($size){
		ini_set('memory_limit', $size);
	}

	private function check_childs(){
		global $mysqli;
		$childs = $mysqli->query('SELECT id,pid FROM bf_threads WHERE (script = \''.$this->settings["uniq"].'\') AND (status = \'2\') ', null, null, false);

		if(count($childs) > 0){
			if($this->settings["IDOS"] === 'WIN'){
				foreach($childs as $c){
					$check_pid = exec('tasklist /FI "'.$this->settings["WIN_LOCALIZE_PID"].' eq '.$c->pid.'" /NH');

					if(stripos($check_pid, $c->pid) === false){
						$mysqli->query('update bf_threads set status = \'3\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$c->id.'\')');
					}
				}
			}else{
			    /*
			    foreach($childs as $c){
					if(stripos(exec('ps -p '.$c->pid), $c->pid) === false){
						$mysqli->query('update bf_threads set status = \'3\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$c->id.'\')');
						usleep(100);
					}
				}
                */

				$pids = '';
				foreach($childs as $c){
					if(!empty($c->pid))	$pids .= $c->pid . ' ';
				}

				$check_pid = exec('ps -p "'.rtrim(preg_replace('~([ ]+)~is', ' ', $pids)).'"');
				unset($pids);
				foreach($childs as $k => $c){
					if(stripos($check_pid, $c->pid) === false){
						if(stripos(exec('ps -p '.$c->pid), $c->pid) === false){
							$tc = $mysqli->query('SELECT status FROM bf_threads WHERE (id = \''.$c->id.'\') LIMIT 1');
                            if($tc->status == '0' || $tc->status == '1' || $tc->status == '2'){
								$mysqli->query('update bf_threads set status = \'3\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$c->id.'\')');
								unset($childs[$k]);
								usleep(100);
							}
						}
					}
				}

				/*
				$pids = array();
				$cpid = array();
				foreach($childs as $c){
					if(!empty($c->pid)){
						$pids[] .= $c->pid . ' ';
						if(!empty($pids['10'])){
							$cpid[] = exec('ps -p "'.trim($pids).'"');
							$pids = array();
						}
					}
				}
				$cpid[] = exec('ps -p "'.rtrim($pids).'"');

                unset($pids);

				foreach($cpid as $cp){
					foreach($childs as $k => $c){
						if(stripos($cp, $c->pid) === false){
							if(stripos(exec('ps -p '.$c->pid), $c->pid) === false){
								$mysqli->query('update bf_threads set status = \'10\', last_date = CURRENT_TIMESTAMP WHERE (id = \''.$c->id.'\')');
								unset($childs[$k]);
								usleep(100);
							}
						}
					}
				}
				*/
			}
		}
		unset($childs);

		$this->time = time();
	}

	public function start(){
		global $mysqli;
		if($this->settings["set_pid"] == false) $this->set_pid();

		$mysqli->settings["ping"] = true;

		ini_set('error_log', $this->settings["error_file"]);
		set_error_handler(array(&$this,'error_handler'));

        $this->ml($this->settings["memory_limit"]);

        if(!empty($this->settings["user_func"]) && function_exists($this->settings["user_func"])){
        	call_user_func($this->settings["user_func"]);
        }

		do{
			$this->get_count();

			if($this->count['cur'] < $this->settings["mp"]){
				if($this->count['task'] > 0){
					$mysqli->query('SELECT id FROM bf_threads WHERE (script = \''.$this->settings["uniq"].'\') AND (status = \'0\') ORDER by unnecessary DESC LIMIT ' . ($this->settings["mp"] - $this->count['cur']), null, array(&$this,'st'), false);
					//usleep(250);
					usleep(100000);
				}
			}else{
				sleep(60);
			}

			if(($this->time+60) < time()){
				$this->check_childs();
			}

			if($this->count['cur'] == 0 && $this->count['task'] == 0){
				sleep(60);

				if(!empty($this->settings["user_func"]) && function_exists($this->settings["user_func"])){
					call_user_func($this->settings["user_func"]);
				}

				$this->get_count();
				if($this->count['cur'] == 0 && $this->count['task'] == 0){
					sleep(30);

					if(!empty($this->settings["user_func"]) && function_exists($this->settings["user_func"])){
						call_user_func($this->settings["user_func"]);
					}

					$this->get_count();
					if($this->count['cur'] == 0 && $this->count['task'] == 0){
						sleep(15);
						$this->get_count();
					}
				}
			}
		}while($this->count['cur'] > 0 || $this->count['task'] > 0);

		if(!empty($this->settings["exit_script"]) && function_exists($this->settings["exit_script"])){
        	call_user_func($this->settings["exit_script"]);
        }

		$this->close();
	}

	public function close(){
		$this->check_childs();
		$this->clear();

		//sleep(60);

		$this->del_pid();

		exit;
	}

	public function clear(){
		global $mysqli;
		$mysqli->query('delete from bf_threads WHERE (script = \''.$this->settings["uniq"].'\')');
	}

	public function set_pid($pid_file = ''){
		$this->settings["set_pid"] = true;

		if(!empty($pid_file) && $this->settings["pid_file"] != $pid_file){
			if(file_exists($this->settings["pid_file"])) @unlink($this->settings["pid_file"]);
			$this->settings["pid_file"] = $pid_file;
		}

		if(file_exists($this->settings["pid_file"])){
			if($this->settings["IDOS"] === 'WIN'){
				$pid = file_get_contents($this->settings["pid_file"]);
				if(stripos(exec('tasklist /FI "'.$this->settings["WIN_LOCALIZE_PID"].' eq '.$pid.'"'), $pid) === false){
					file_put_contents($this->settings["pid_file"], getmypid());
				}else{
					exit;
				}
			}else{
				$pid = file_get_contents($this->settings["pid_file"]);
				if(stripos(exec('ps -p '.$pid), $pid) === false){
					file_put_contents($this->settings["pid_file"], getmypid());
				}else{
					exit;
				}
			}
		}else{
			file_put_contents($this->settings["pid_file"], getmypid());
		}
	}

	public function error_handler($code, $msg, $file, $line){
		if(strpos($this->settings["disable_code_error"], $code . '|') !== false){
			 file_put_contents($this->settings["error_file"], print_r(array('code' => $code, 'msg' => $msg, 'file' => $file, 'line' => $line), true) . "\r\n", FILE_APPEND);
		}
	}

	public function st($r){
		global $mysqli;

		$mysqli->query('update bf_threads set status = \'1\', last_date = CURRENT_TIMESTAMP, post_date = CURRENT_TIMESTAMP WHERE (id = \''.$r->id.'\')');

		if($this->settings["IDOS"] === 'WIN'){
			$WshShell = new COM("WScript.Shell");
			$oExec = $WshShell->Run(addslashes($this->settings["PHP_EXE"] . ' ' . $this->settings["file_proc"] . ' ' . $r->id), 7, false);
			unset($WshShell,$oExec);
		}else{
			exec($this->settings["file_proc"] . ' ' . $r->id . ' > /dev/null &');
		}
	}
}
?>