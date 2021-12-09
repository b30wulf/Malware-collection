<?php

class get_http {
    public $curl, $url, $config, $webpage, $status, $header,$add_http;

	function __construct(){
        $this->clear();
	}

	public function clear($cookie = ''){		$this->curl = curl_init();

		$this->config = array();
        $this->add_http = 0;

		$this->url = '';
		$this->config['useragent'] = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)';
		$this->config['followlocation'] = true;
		$this->config['timeout'] = 30;

		if(empty($cookie)){
			$this->config['cookieFileLocation'] = dirname(__FILE__) . DIRECTORY_SEPARATOR . '/cookie.txt';
		}else{			$this->config['cookieFileLocation'] = $cookie;
		}

		$this->config['post'] = false;
		$this->config['postFields'] = false;
		$this->config['referer'] = '';
		$this->config['includeHeader'] = false;
		$this->config['nobody'] = false;
		$this->config['socks'] = false;
		$this->config['socks_type'] = CURLPROXY_SOCKS5;
		$this->config['referer'] = false;

		$this->config['attempt'] = false;
		$this->config['attempt_code'] = 200;

		$this->webpage = '';
		$this->status = '';
		$this->header['in'] = '';
		$this->header['out'] = '';
	}

	public function open($url){
		if(!empty($url)) $this->url = $url;
        //$this->webpage = '';
		curl_setopt($this->curl,CURLOPT_URL, $this->url);

		curl_setopt($this->curl,CURLOPT_TIMEOUT, $this->config['timeout']);
		curl_setopt($this->curl,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl,CURLOPT_FOLLOWLOCATION, $this->config['followlocation']);
		curl_setopt($this->curl,CURLOPT_COOKIEJAR, $this->config['cookieFileLocation']);
		curl_setopt($this->curl,CURLOPT_COOKIEFILE, $this->config['cookieFileLocation']);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 30);
		//curl_setopt($this->curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);

		if(!empty($this->config['referer'])){
			curl_setopt($this->curl,CURLOPT_REFERER, $this->config['referer']);
		}else{			curl_setopt($this->curl,CURLOPT_REFERER, false);
		}

		if($this->config['socks'] != false){
			curl_setopt($this->curl, CURLOPT_PROXYTYPE, $this->config['socks_type']);
			curl_setopt($this->curl, CURLOPT_PROXY, $this->config['socks']);
		}else{			curl_setopt($this->curl, CURLOPT_PROXYTYPE, false);
			curl_setopt($this->curl, CURLOPT_PROXY, false);
		}

		if($this->config['post'] == true){
			curl_setopt($this->curl,CURLOPT_POST, true);
			curl_setopt($this->curl,CURLOPT_POSTFIELDS, $this->config['postFields']);
		}else{			curl_setopt($this->curl,CURLOPT_POST, false);
			curl_setopt($this->curl,CURLOPT_POSTFIELDS, false);
		}

		if($this->config['includeHeader'] == true){
			curl_setopt($this->curl,CURLOPT_HEADER, true);
		}else{			curl_setopt($this->curl,CURLOPT_HEADER, false);
		}

		if($this->config['nobody'] == true){
			curl_setopt($this->curl,CURLOPT_NOBODY, true);
		}else{
			curl_setopt($this->curl,CURLOPT_NOBODY, false);
		}

		curl_setopt($this->curl,CURLOPT_USERAGENT, $this->config['useragent']);
		curl_setopt($this->curl,CURLOPT_REFERER, $this->config['referer']);

		$this->webpage = curl_exec($this->curl);
		$this->status = curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
		$this->header['out'] = curl_getinfo($this->curl,CURLINFO_HEADER_OUT);

		if($this->config['attempt'] != false){			if(empty($this->webpage) && $this->status == $this->config['attempt_code']){				for($i = 0; $i < $this->config['attempt']; $i++){					$this->add_http++;
					$this->webpage = curl_exec($this->curl);
					$this->status = curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
					$this->header['out'] = curl_getinfo($this->curl,CURLINFO_HEADER_OUT);
					if(!empty($this->webpage)){						break;
					}
					usleep(500000); // Спать 0.5 секунды
				}
			}
		}

		curl_close($this->curl);
	}
}

?>