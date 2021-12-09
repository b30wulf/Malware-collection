<?php

//globals used by rtl tester
$working = "";
$status = "";
$instructions = "";
$already_loaded = extension_loaded('ionCube Loader');

if (!isset($HTTP_SERVER_VARS))
    $HTTP_SERVER_VARS = &$_SERVER;  
    
if (!isset($HTTP_GET_VARS))
    $HTTP_GET_VARS = &$_GET;  

//install assistant globals

//
// Determine PHP flavour
//

$php_version = phpversion();
$php_flavour = substr($php_version,0,3);


//
// Get the full name and short name for the OS
//

$os_name = substr(php_uname(),0,strpos(php_uname(),' '));
$os_code = strtolower(substr($os_name,0,3));

$dll_sfix = (($os_code == 'win') ? '.dll' : '.so');


//
// Analyse the PHP build
//

ob_start();
phpinfo(INFO_GENERAL);
$php_info = ob_get_contents();
ob_end_clean();

$thread_safe = false;
$debug_build = false;
$php_ini_path = '';


//
// Text or HTML output?
//

$cli = (php_sapi_name() == 'cli');
$nl =  ($cli ? "\n" : '<br>');


function check_safe_mode()
{
    $sm = ini_get('safe_mode');
    return ($sm=='1');
}

function getCSS()
{
	$css = "
				body, td
				{
					font-size:70%;
					font-family:verdana, helvetica, arial;
					line-height:200%;
				}

				div.main
				{
					width:80%;
					text-align:left;
					top:20px;
					position:relative;
					border:2px solid #F0F0F0;
					padding:20px;
				}

				code
				{
					font-family:courier, helvetica, arial;
				}
				table.ini_line
				{
					border:2px solid #c8e8c8;	
					background-color:white;
				}

				table.ini_line tr td
				{
					background-color:#c8e8c8;	
				}

				table.analysis
				{
					border:2px solid #e0e0ff;	
					background-color:white;
				}

				table.analysis tr td
				{
					background-color: #e0e0ff;	
				}
	";
	return $css;
}

function displayLogo()
{

	$img_encoded="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";

	header("Content-type: image/gif");
	header("Cache-control: public");
	echo base64_decode($img_encoded);

}

function weblink($url, $display, $new_window=false)
{
  if (use_html()) {
      $extra = "";
      if ($new_window)
	  $extra = 'target="_blank"';

    return "<a href=\"$url\" $extra>$display</a>";
  } else {
    return $url;
  }
}

//
// Where are we?
//
$here = dirname(__FILE__);




//rtl tester specific functions
function echo_working($msg)
{
	global $working;
	$working.=$msg;
}

function echo_status($msg)
{
	global $status;
	$status.=$msg;
}

function echo_instructions($msg)
{
	global $instructions;
	$instructions.=$msg;
}



//
// Detect some system parameters
//
function ic_system_info()
{
  $thread_safe = false;
  $debug_build = false;
  $cgi_cli = false;
  $php_ini_path = '';

  ob_start();
  phpinfo(INFO_GENERAL);
  $php_info = ob_get_contents();
  ob_end_clean();

  foreach (split("\n",$php_info) as $line) {
    if (eregi('command',$line)) {
      continue;
    }

    if (preg_match('/thread safety.*(enabled|yes)/Ui',$line)) {
      $thread_safe = true;
    }

    if (preg_match('/debug.*(enabled|yes)/Ui',$line)) {
      $debug_build = true;
    }

    if (eregi("configuration file.*(</B></td><TD ALIGN=\"left\">| => |v\">)([^ <]*)(.*</td.*)?",$line,$match)) {
      $php_ini_path = $match[2];

      //
      // If we can't access the php.ini file then we probably lost on the match
      //
      if (!@file_exists($php_ini_path)) {
	$php_ini_path = '';
      }
    }

    $cgi_cli = ((strpos(php_sapi_name(),'cgi') !== false) ||
		(strpos(php_sapi_name(),'cli') !== false));
  }

  return array('THREAD_SAFE' => $thread_safe,
	       'DEBUG_BUILD' => $debug_build,
	       'PHP_INI'     => $php_ini_path,
	       'CGI_CLI'     => $cgi_cli);
}

function rtl_tester()
{

	global $cli;
	global $nl;
	global $working;
	global $instructions;
	global $status;
	global $already_loaded;

	$working = "";
	$status = "";
	$instructions = "";

	$ok					= true;
	$already_installed	= false;

	
	echo_working("\n");

	//
	// Is the loader already installed?
	//
	if ($already_loaded) {
	  echo_status("An ionCube Loader is already installed and run-time loading is unnecessary.\n"
.	"Encoded files should load without problems.$nl");

	  echo_instructions("If you have problems running encoded files, make sure that you select binary\n"
.			    "mode if using FTP. If unpacking files with WinZIP you must also disable the\n" 
.			    "'TAR Smart CR/LF conversion' feature.$nl$nl");
	  $already_installed = true;
	} else {
	  //
	  // Intro
	  //
	  echo_working("Testing whether your system supports run-time loading...$nl$nl");
	}



	//
	// Test some system info
	//
	$sys_info = ic_system_info();

	if (!$already_installed) {
	  if ($sys_info['THREAD_SAFE'] && !$sys_info['CGI_CLI']) {
		echo_status("Your PHP install appears to have threading support and run-time Loading\n"
	."is only possible on threaded web servers if using the CGI, FastCGI or\n"
	."CLI interface.$nl");
		echo_instructions("To run encoded files please install the Loader in the php.ini file.");
		$ok = false;
	  }

	  if ($sys_info['DEBUG_BUILD']) {
		echo_status("Your PHP installation appears to be built with debugging support\n"
.	"enabled and this is incompatible with ionCube Loaders.$nl${nl}Debugging support in PHP produces slower execution, is\n"
.	"not recommended for production builds and was probably a mistake.$nl");

		echo_instructions("You should rebuild PHP without the --enable-debug option.$nl");
		$ok = false;
	  }

	  //
	  // Check safe mode and for a valid extensions directory
	  //
	  if (check_safe_mode()) {
		echo_status("PHP safe mode is enabled and run time loading will not be possible.");
		echo_instructions(  "To run encoded files please install the Loader in the php.ini file.\n"
		.		    "Alternatively contact your hosting provider or system administrator,\n"
		.		    "and ask them to disable safe mode for your account.");
		$ok = false;
	  } 
	  /*
		elseif (!is_dir(realpath(ini_get('extension_dir')))) {
		echo "The setting of extension_dir in the php.ini file is not a directory
		or may not exist and run time loading will not be possible. You do not need
		write permissions on the extension_dir but for run-time loading to work
		a path from the extensions directory to wherever the Loader is installed
		must exist.$nl";
		$ok = false;
		}
	  */

	  // If ok to try and find a Loader
	  if ($ok) {
		//
		// Look for a Loader
		//

		// Old style naming should be long gone now
		$test_old_name = false;

		$_u = php_uname();
		$_os = substr($_u,0,strpos($_u,' '));
		$_os_key = strtolower(substr($_u,0,3));

		$_php_version = phpversion();
		$_php_family = substr($_php_version,0,3);

		$_loader_sfix = (($_os_key == 'win') ? '.dll' : '.so');

		$_ln_old="ioncube_loader.$_loader_sfix";
		$_ln_old_loc="/ioncube/$_ln_old";

		$_ln_new="ioncube_loader_${_os_key}_${_php_family}${_loader_sfix}";
		$_ln_new_loc="/ioncube/$_ln_new";

		echo_working("${nl}Looking for Loader '$_ln_new'");
		if ($test_old_name) {
		  echo_working(" or '$_ln_old'");
		}
		echo_working($nl.$nl);

		$_extdir = ini_get('extension_dir');
		if ($_extdir == './') {
		  $_extdir = '.';
		}

		$_oid = $_id = realpath($_extdir);

		$_here = dirname(__FILE__);
		if ((@$_id[1]) == ':') {
		  $_id = str_replace('\\','/',substr($_id,2));
		  $_here = str_replace('\\','/',substr($_here,2));
		}
		$_rd=str_repeat('/..',substr_count($_id,'/')).$_here.'/';

		if ($_oid !== false) {
		  echo_working("Extensions Dir: $_extdir ($_id)$nl");
		  echo_working("Relative Path:  $_rd$nl");
		} else {
		  echo_working("Extensions Dir: $_extdir (NOT FOUND)$nl$nl");

			echo_status("The directory set for the extension_dir entry in the\n"
.	"php.ini file may not exist, and run time loading will not be possible.$nl");
			echo_instructions(  "Please ask your hosting provider or system administrator to create the\n"
			.		    "directory$nl$nl"
			.		    "$_extdir$nl$nl"
			.		    "ensuring that it is accessible by the web server software. They do not\n"
			.		    "need to restart the server. Then rerun this script. As an alternative,\n"
			.		    "your host could install the Loader in the php.ini file.$nl");
		  $ok = false;
		}

		if ($ok) {
		  $_ln = '';
		  $_i=strlen($_rd);
		  while($_i--) {
		if($_rd[$_i]=='/') {
		  if ($test_old_name) {
			// Try the old style Loader name
			$_lp=substr($_rd,0,$_i).$_ln_old_loc;
			$_fqlp=$_oid.$_lp;
			if(@file_exists($_fqlp)) {
			  echo_working("Found Loader:   $_fqlp$nl");
			  $_ln=$_lp;
			  break;
			}
		  }
		  // Try the new style Loader name
		  $_lp=substr($_rd,0,$_i).$_ln_new_loc;
		  $_fqlp=$_oid.$_lp;
		  if(@file_exists($_fqlp)) {
			echo_working("Found Loader:   $_fqlp$nl");
			$_ln=$_lp;
			break;
		  }
		}
		  }

		  //
		  // If Loader not found, try the fallback of in the extensions directory
		  //
		  if (!$_ln) {
		if ($test_old_name) {
		  if (@file_exists($_id.$_ln_old_loc)) {
			$_ln = $_ln_old_loc;
		  }
		}
		if (@file_exists($_id.$_ln_new_loc)) {
		  $_ln = $_ln_new_loc;
		}

		if ($_ln) {
		  echo_working("Found Loader $_ln in extensions directory.$nl");
		}
		  }

		  echo_working($nl);

		  if ($_ln) {
		echo_working("Trying to install Loader - this may produce an error...$nl$nl");
		dl($_ln);

		if(extension_loaded('ionCube Loader')) {
		  echo_status(	"The Loader was successfully installed and encoded files should be able to\n"
.				"automatically install the Loader when needed.$nl");
		  echo_instructions( "No changes to your php.ini file\n"
.				"are required to use encoded files on this system.${nl}");
		} else {
		  echo_status("The Loader was not installed.$nl");
		} 
		  } else {
		echo_status(	"Run-time loading should be possible on your system but no suitable Loader\n"
.				"was found.$nl$nl");
		echo_instructions(	"The $_os Loader for PHP $_php_family releases is required.$nl"
.					"Loaders can be downloaded from " . weblink("http://www.ioncube.com/loaders.php","www.ioncube.com"));
		  }
		}
	  }
	}

	//$instructions should never be empty.
	//if it is, then put default:
	if (empty($instructions))
	{	
		$instructions = "Run-time loading is not currently possible.$nl"
		.				"If you require further assistance, please contact ionCube support providing${nl}a link to this script.";
	}

	if ($cli)
	{
		return "$working$status$instructions\n";
	}
	else
	{

		//echo "${nl}Please send the output of this script to $email if you have questions or require further assistance.$nl$nl";

		$body = "<center><h2>Run-Time Loading Installation Instructions</h2></center>"
		.		"<h3>Testing Server</h3>"
		.		"$working"
		.		"<h3>Results</h3>"
		.		"$status"
		.		"<h3>Instructions</h3>"
		.		$instructions;

		return $body;

	}
}
//END OF RTL-TESTER FUNCTIONS

//BEGIN INSTALL ASSISTANT RELATED FUNCTIONS


function query_self($text, $query)
{
  global $HTTP_SERVER_VARS;
  
  if (use_html()) {
    return '<a target=_blank href="'.@$HTTP_SERVER_VARS['PHP_SELF']."?page=install-assistant&q=$query\">$text</a>";
  } else {
    return $text;
  }
}



function use_html()
{
  return (php_sapi_name() != 'cli');
}

function para($text)
{
  return ($text . (use_html() ? '<p>' : "\n\n"));
}

function code($text)
{
  return (use_html() ? "<code>$text</code>" : $text);
}

function table($contents)
{
	  if (use_html()) {
		$res = '<table class="analysis" cellpadding=5 cellspacing=1 border=0>';
		foreach ($contents as $row) {
		  $res .= "<tr>\n";
		  foreach ($row as $cell) {
		$res .= "<td>$cell</td>\n";
		  }
		  $res .= "</tr>\n";
		}
		$res .= "</table>\n";
	  } else {
		$colwidths = array();
		foreach ($contents as $row) {
		  $cv = 0;
		  foreach ($row as $cell) {
		$l = @$colwidths[$cv];
		$cl = strlen($cell);

		if ($cl > $l) {
		  $colwidths[$cv] = $cl;
		}
		$cv++;
		  }
		}
		$tw = 0;
		foreach ($colwidths as $cw) { $tw += ($cw + 2); }
		$tw2 = $tw + count($colwidths) - 1 + 2;
		$res = '+' . str_repeat('-',$tw2 - 2) . "+\n";
		foreach ($contents as $row) {
		  $cv = 0;
		  foreach ($row as $cell) {
		$res .= '| ' . str_pad($cell, $colwidths[$cv]) . ' ';
		$cv++;
		  }
		  $res .= "|\n";
		}
		$res .= '+' . str_repeat('-',$tw2 - 2) . "+\n";
	  }

	  return $res;
}

function ilia_header()
{
	if (!use_html()) {
		
	return "\n"
.	"ionCube Loader Install Assistant\n"
.	"--------------------------------\n"
.	"\n"
.	"\n";
	  }
	else
	{

	     return "<center><h2>php.ini Installation Instructions</h2></center>"
	     .	    "<h3>Analysis</h3>";;
	   
	}

}

function heading($text)
{
	  if (use_html()) {
	    return para("<font face=\"helvetica,verdana\"><b>$text</b></font>");
	  } else {
	    return para($text . "\n" . str_repeat('-', strlen($text)));
	  }
}

function ilia_analysis()
{
      global $php_version, $php_flavour, $os_name, $thread_safe, $php_ini_path, $required_loader,$os_code, $cli;

      $res = para('Analysis of your system configuration shows:')
	    . table(array(array("PHP Version",$php_version),
		      array("Operating System",$os_name),
		      array("Threaded PHP",($thread_safe ? 'Yes' : 'No')),
		      array("php.ini file", ($php_ini_path ? $php_ini_path : query_self("Check phpinfo() output for\n" .'location','phpinfo'))),
		      array("Required Loader",$required_loader)
		      ))
	    . para('');

    if (!$cli)
	$res .= "<h3>Instructions</h3>";

    if ($php_ini_path)
    {
	  $res .= para('To install the Loader in your '.code('php.ini')." file, edit or create the file\n\n"
	.	code($php_ini_path) . "\n\n"
	.	"and add the following line before any other ".code('zend_extension').' lines:');
    }
    else
    {
	$res .= para(	'To install the loader in your '.code('php.ini')." file, first check the location of the\n"
	.		"php.ini file from phpinfo output. Then edit or create the file, and add the\n"
	.		"following line before any other ".code('zend_extension').' lines:');
    }

      if ($os_code == 'win') {
	    if (use_html()) {
	      $path = '&lt;drive&gt;:\\&lt;path&gt;\\';
	    } else {
	      $path = '<drive>:\\<path>\\';
	    }

	    $ini = "zend_extension_ts = $path$required_loader";
      } else {
	    if (use_html()) {
	      $path = '/&lt;path&gt;/';
	    } else {
	      $path = '/<path>/';
	    }

	    if ($thread_safe) {
	      $ini = "zend_extension_ts = $path$required_loader";
	    } else {
	      $ini = "zend_extension = $path$required_loader";
	    }
      }

      if (use_html()) {
	    $res .= "<table class=\"ini_line\" cellpadding=4 cellspacing=1 border=0><tr><td><code>$ini</code></td></tr></table><p>";
      } else {
	    $res .= para("  $ini");
      }

      if ($os_code == 'win') {
	    $res .= para('where '.code($path).' is where you\'ve installed the loader.');
      } else {
	      $res .= para('where '.code($path).' is where you\'ve installed the loader, e.g. '.code('/usr/local/ioncube/'));
      }

	
	$res .= para("If there are no zend_extension lines already, you can put the
line at any point in the file.");

	$res .= para("Finally, stop and restart your web server software for the changes to\n"
    .			"take effect.");

      if (!check_safe_mode() && ($os_code != 'win')) {
	    $res .= heading('Installing the Loader for run-time loading');

	    $res .= para(	"To install for runtime loading, create a directory called ".code('ioncube') . "\n"
    .				"at or above the top level of your encoded files, and ensure that the directory\n"
    .				"contains the ".code($required_loader) . " loader. If run-time install of\n"
    .				"the Loader is possible on your system then encoded files should automatically\n"
    .				"install the loader when needed.");
      }

      return $res;
}

function ilia_debug_builds_unsupported()
{
	  return para(	"IMPORTANT NOTE: Your PHP installation may be incorrect\n"
.			"------------------------------------------------------\n"
.			"\n"
.			"Your PHP installation appears to be built with debugging\n"
.			"support enabled, and extensions cannot be installed in this case.")
  		.para(	"Debugging support in PHP produces slower execution, is not recommended for\n"
.			"production builds, and was probably a mistake.")
		.para(	"Debugging support may sometimes be incorrectly detected, and so please\n"
.			"continue to follow the installation instructions and try the Loader.\n" 
.			"However do contact the ionCube helpdesk if the Loader fails to\n" 
.			"install, and include a web link to either this script or a page that\n"
.			"calls phpinfo() so that we can help.");
}

function install_assistant()
{
	global $php_version, $php_flavour, $os_name, $thread_safe, $php_ini_path, $required_loader,$os_code, $php_info,$dll_sfix,$HTTP_GET_VARS;

	if ($q = @$HTTP_GET_VARS['q']) {
	  if ($q == 'phpinfo') {
		phpinfo(INFO_GENERAL);
	  }
	  exit(0);
	}
	
	foreach (split("\n",$php_info) as $line) {
	  if (eregi('command',$line)) {
		continue;
	  }

	  if (preg_match('/thread safety.*(enabled|yes)/Ui',$line)) {
		$thread_safe = true;
	  }

	  if (preg_match('/debug.*(enabled|yes)/Ui',$line)) {
		$debug_build = true;
	  }

	  if (eregi("configuration file.*(</B></td><TD ALIGN=\"left\">| => |v\">)([^ <]*)(.*</td.*)?",$line,$match)) {
		$php_ini_path = $match[2];

		//
		// If we can't access the php.ini file then we probably lost on the match
		//
		if (@!file_exists($php_ini_path)) {
		  $php_ini_path = '';
		}
	  }
	}

	//
	// We now know enough to give guidance
	//
	$ts = ((($os_code != 'win') && $thread_safe) ? '_ts' : '');

	$required_loader = "ioncube_loader_${os_code}_${php_flavour}${ts}${dll_sfix}";

	//
	// Create the output
	//

	
	$out = "";
	$out.=ilia_header();
	$out.=ilia_analysis();
	if ($debug_build) {
	  $out.=ilia_debug_builds_unsupported();
	}

	return $out;
}

function getInstructions()
{
    global $nl;
    global $cli;

    if ($cli)
    {
	return	"\nAn ionCube Loader file is required by PHP to read files encoded with the\n"
	.	"ionCube Encoder. This page will determine how you can install Loaders\n"
	.	"on this particular server.$nl$nl";

    }
    else
    {

	return "Please read the ".weblink("http://www.ioncube.com/loader_installation.php", "online documentation", true).", or review the file readme.txt enclosed with the Loader bundle,  for more help with Loader installation.$nl$nl";
    }
}


//main page

function index()
{
	global $HTTP_SERVER_VARS;
	global $nl;
	global $already_loaded;

	$self	= @$HTTP_SERVER_VARS['PHP_SELF'];
	$host	= @$HTTP_SERVER_VARS['HTTP_HOST'];

	ob_start();
	$success = include dirname(__FILE__).'/ioncube-encoded-file.php';
	$php_info = ob_get_contents();
	ob_end_clean();

	$body = "";

	$body.="An ionCube Loader file is required by PHP to read files encoded with the ionCube Encoder. This page will determine how you can install Loaders on this particular server.$nl$nl";

	
	$dir = dirname($self);

	$rtl = "<a href=\"$self?page=rtl-tester\">run-time loading installation instructions</a>";
	$sys = "<a href=\"$self?page=sysinfo\">Server System Information</a>";
	$ass = "<a href=\"$self?page=install-assistant\">php.ini installation instructions</a>";
	

	if ($success)
	{
		$body.=	"An ionCube encoded file has been loaded <b><font color=green>successfully</font></b>.<br>"
		.		"Encoded files should now function correctly.<br><br>";


	    if (!$already_loaded)
	    {
		    $body.="If you have permission to edit the php.ini configuration file for this server you can increase performance by following the $ass.<br><br>";
	    }

	     $body.="If you have a problem with your PHP application, please contact the application provider.";
	}
	else
	{
		$body.=	"The loading of ionCube encoded files is not currently working correctly on this server.<br><br>\n";

		$body.=	"If you have permission to edit the php.ini configuration file for this server, please follow the "
	.	"$ass.<br><br>";

		$body.=	"If you do not access to the php.ini file, please follow the "
	.	"$rtl.<br><br>";
	
		$body.=getInstructions();
	}
	
	return $body;
}



function read($fp) {
    $input = rtrim(fgets($fp, 1024));
    return $input;
}

function doMenu($fp)
{
	
	echo("\n>> Please choose an option followed by Return, or 0 to exit this script.\n\n");
	echo("0. Exit this script\n");
	echo("1. php.ini installation instructions\n");
	echo("2. Run-time loading installation instructions\n");
	echo("\n");
	echo(">> ");

	do
	{
		$command = read($fp);
	}
	while(strlen($command)==0);
	return $command;
}



if ($cli)
{
	$fp=fopen('php://stdin', 'r');
	echo(getInstructions());

	$success =  include dirname(__FILE__).'/ioncube-encoded-file.php';
	if ($success)
	{
		echo	"An ionCube encoded file has been loaded successfully.\n"
		.	"Encoded files should now function correctly.\n\n";


	    if (!$already_loaded)
	    {
		    echo "If you have permission to edit the php.ini configuration file for this server you can increase performance by choosing option 1 below.\n\n";
	    }

	     echo "If you have a problem with your PHP application, please contact the application provider.";
	}
	else
	{
		echo	"The loading of ionCube encoded files is not currently working correctly\n"
		.	"on this server.\n\n";

		echo	"If you have permission to edit the php.ini configuration file for this server,\n"
		.	"please choose option 1 below.\n\n";

		echo	"If you do not access to the php.ini file, please choose option 2 below.\n\n";
	
		$body.=getInstructions();
	}
	

	while(true)
	{
		$command = doMenu($fp);
		if ($command==0)
			exit(0);
		elseif ($command==2)
			echo(rtl_tester());
		elseif ($command==10)
			phpinfo();
		elseif ($command==1)
			echo(install_assistant());
	}
	fclose($fp);
}
else
{
	global $HTTP_SERVER_VARS,$HTTP_GET_VARS;
	$us		=	@$HTTP_SERVER_VARS['PHP_SELF'];
	$page	=	@$HTTP_GET_VARS['page'];

	$css = getCSS();
	$out =	"<html>\n"
	.		"<head>\n"
	.		"<style>$css</style>\n"
	.		"<title>ionCube Loader Installation Tool</title>"
	.		"</head>\n"
	.		"<body>\n"

	;

	$body = "";
	$body.="<div align=center><div class=\"main\">\n";
	$body.="<img src=\"$us?page=logo\"><br><br>\n";


	
	if ($page=="rtl-tester")
		$body.= rtl_tester();
	else if($page=="sysinfo")
		return phpinfo();
	else if($page=="install-assistant")
	{
		$body.= install_assistant();
	}
	else if($page=="logo")
	{
		displayLogo();
		exit(1);
	}
	else
		$body.= index();

	$body.="</div></div>\n";
	$out.=$body;
	$out.=	"</body></html>\n";
	echo($out);
}

?>
