<?php

class AvcheckAPI
{
    public $key, $services, $services_dw, $useragents, $check_interval, $type, $host, $status;

    public function __construct(
        $key,
        $host,
        $type = 'text',
        $services = array('all'),
        $useragents = array('all'),
        $services_dw = array('all'),
        $check_interval = 0
    )
    {
        $this->key = $key;
        $this->check_interval = $check_interval;
        $this->type = $type;
        $this->host = $host;
        $this->warnings = array();
        $this->errors = array();
        $this->services = $services;
        $this->services_dw = $services_dw;
        $this->useragents = $useragents;
        $this->public_link_uri = '';

        foreach(array('services', 'services_dw', 'useragents') as $item) {
            if (is_array($$item) && count($$item) == 0) {
                $this->$item = array('none');
            }
        }
    }

    public function _upload($request, $action='upload')
    {
        $ch = curl_init(
            'http://'.$this->host.'/check/remote/'.$action.'/'.$this->key
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $this->upload_response = $response;
    }
    
    public function get_link() {
        $upload_response = $this->upload_response;
        if (substr($upload_response, 0, 2) == 'ok')
        {
            $upload_response = explode("\n", $upload_response);
            $status = array_shift($upload_response);
            $id = array_shift($upload_response);
            
            //$result['public_link'] = array_shift($upload_response);
            $result['status'] = $status;
            $result['warnings'] = $upload_response;
            $result['id'] = $id;
        }
        else
        {
            $upload_response = explode("\n", $upload_response);
            $status = array_shift($upload_response);
            $result['status'] = 'error';
            $result['errors'] = $upload_response;
        }
        
        return $result;
    }
    
    public function get_status($id = '') {
        if(!empty($id))
        {
            $ask_response = $this->_ask($id);
            
            if (false === $ask_response)
            {
                $result['status'] = 'error';
                $result['errors'] = array('Check started, but connect to server was lost. Try out public link.');
            }
            elseif (substr($ask_response, 0, 4) == 'wait')
            {
                $result['status'] = 'wait';
            }
            else
            {
                if (substr($ask_response, 0, 2) === 'ok')
                {
                    $result['status'] = 'ok';
                    $result = array_merge($result, $this->_parse_monitor_response($ask_response));
                }
                else
                {
                    $ask_response = explode("\n", $ask_response);
                    $status = array_shift($ask_response);
                    $result['status'] = $status;
                    $result['errors'] = $ask_response;
                }
            }
        }
        
        return $result;
    }

    public function _result($monitor_after_upload) {
        $result = $this->_wait_for_response($monitor_after_upload);
        $this->public_link_uri =
            $result['status'] != 'error'? $result['public_link']: '';
        return $result['status']? $result: true;
    }

    public function _wait_for_response($wait=true)
    {
        $upload_response = $this->upload_response;
        $result = array('status' => '');
        if (substr($upload_response, 0, 2) == 'ok')
        {
            $upload_response = explode("\n", $upload_response);
            $status = array_shift($upload_response);
            $id = array_shift($upload_response);

            $result['public_link'] = array_shift($upload_response);
            $result['warnings'] = $upload_response;

            while ($wait && 1) {
                sleep(5);
                $ask_response = $this->_ask($id);

                if (false === $ask_response)
                {
                    $result['status'] = 'error';
                    $result['errors'] = array('Check started, but connect to server was lost. Try out public link.');
                    break;
                }
                elseif (substr($ask_response, 0, 4) == 'wait')
                {
                    continue;
                }
                else
                {
                    if (substr($ask_response, 0, 2) === 'ok')
                    {
                        $result['status'] = 'ok';
                        $result = array_merge($result, $this->_parse_monitor_response($ask_response));
                        break;
                    }
                    else
                    {
                        $ask_response = explode("\n", $ask_response);
                        $status = array_shift($ask_response);
                        $result['status'] = $status;
                        $result['errors'] = $ask_response;
                        break;
                    }
                }
            }
        }
        else
        {
            $upload_response = explode("\n", $upload_response);
            $status = array_shift($upload_response);
            $result['status'] = 'error';
            $result['errors'] = $upload_response;
        }
        return $result;
    }

    public function _ask($id)
    {
        $ch = curl_init(
            sprintf('http://'.$this->host.'/check/api/mon/%s', $id)
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function _parse_monitor_response($response)
    {
        $result = array('public_link' => '', 'files' => array());

        $response = explode("\n", $response, 3);
        $status = array_shift($response);
        $result['public_link'] = array_shift($response);

        $files = array_shift($response);
        $files = explode("\n\n", $files);

        foreach ($files as $file)
        {
            $lines = explode("\n", $file);
            $fname = array_shift($lines);
            $fr_entry = array('name' => $fname, 'results' => array());
            foreach ($lines as $fresult)
            {
                if (preg_match('|^([^:]+):(.*)|', $fresult, $matches))
                {
                    list($line, $id, $text) = $matches;
                    if (!$text) {
                        $text = $status == 'ok'? 'Clean': 'Blacklisted';
                    }
                    $fr_entry['results'][$id] = $text;
                }
            }
            $result['files'][] = $fr_entry;
        }
        return $result;
    }

    public function get_public_link() {
        return $this->host . $this->public_link_uri;
    }

    public function check_file($trg, $monitor_after_upload=true)
    {
        $request = array();
        # check url
        $url = filter_var($trg, FILTER_VALIDATE_URL);
        if ($url) {
            $request['url'] = $url;
        # check file
        } else {
            if (!file_exists($trg) || !is_readable($trg)) {
                return array(
                    'status' => 'error',
                    'errors' => array (
                        'Specified file does not exists or can not be read'
                    )
                );
            }
            $request['file'] = '@'.$trg;
        }
        $request['services'] = implode(',', $this->services);
        $request['services_dw'] = implode(',', $this->services_dw);
        $request['check_interval'] = $this->check_interval;
        $this->_upload($request);
        if (false === $this->upload_response)
        {
            return array(
                'status' => 'error',
                'errors' => array('Couldn\'t connect to host')
            );
        }

        return $this->_result($monitor_after_upload);
    }

    public function check_pack($trg, $monitor_after_upload=true)
    {
        $request = array();
        $url = filter_var($trg, FILTER_VALIDATE_URL);
        if (!$url) {
            return array(
                'status' => 'error',
                'errors' => array (
                    'Specified URL is not valid'
                )
            );
        }
        $request['url'] = $url;
        $request['services'] = implode(',', $this->services);
        $request['services_dw'] = implode(',', $this->services_dw);
        $request['useragents'] = implode(',', $this->useragents);
        $request['check_interval'] = $this->check_interval;
        $this->_upload($request, $action='check_pack');
        if (false === $this->upload_response)
        {
            return array(
                'status' => 'error',
                'errors' => array('Couldn\'t connect to host')
            );
        }

        return $this->_result($monitor_after_upload);
    }

    public function check_domain_ip($trg, $monitor_after_upload=true)
    {
        $request = array();
        $request['url'] = $trg;
        $request['services'] = implode(',', $this->services);
        $request['check_interval'] = $this->check_interval;
        $this->_upload($request, $action='check_domain_ip');
        if (false === $this->upload_response)
        {
            return array(
                'status' => 'error',
                'errors' => array('Couldn\'t connect to host')
            );
        }

        return $this->_result($monitor_after_upload);
    }

    # alias for existing scripts
    public function check($trg, $monitor_after_upload=true) {
        return $this->check_file($trg, $monitor_after_upload);
    }
}
