<?php

class ssh {
	var $stream;
	var $connection;
	var $data;
	public $history;

	function connect($host,$login,$pass,$port = 22) {
		$this->connection = ssh2_connect($host, $port);
		//file_put_contents('history.txt', $this->history);
		return ssh2_auth_password($this->connection, $login, $pass);
	}

	function cmd ($cmd, $i = 0) {
		$this->history .= $cmd . "\r\n";
		//file_put_contents('history.txt', $this->history);
		if(!($this->stream=ssh2_exec($this->connection,$cmd))) {
			//echo ":fail!";
			//$this->data = ':fail!';
			$i++;
			if($i > 3){
				$this->data = ':fail!';
			}else{
				$this->cmd($cmd, $i);
			}
		}else{
			stream_set_blocking($this->stream, true);
			$this->data = "";
			while($buf = fread($this->stream,4096) ) {
				$this->data .= $buf;
			}
		}
		$this->history .= $this->data . "\r\n\r\n";
		//file_put_contents('history.txt', $this->history);
		return $this->data;
	}

	function close () {
		fclose($this->stream);
	}
}

?>