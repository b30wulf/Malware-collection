<?php

if(!function_exists('create_filter')){	function create_filter($id){		global $mysqli;
		if(!empty($id)){			return $mysqli->query('CREATE TABLE bf_filter_'.$id.' ( id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, prefix VARCHAR(16) NOT NULL, uid VARCHAR(64) NOT NULL, country VARCHAR(3) NOT NULL, url TEXT NOT NULL, fields TEXT NOT NULL, data TEXT NOT NULL, size INT(11) NOT NULL, md5_hash varchar(32) NOT NULL, program VARCHAR(32) NOT NULL, type INT(1) NOT NULL, save int(11) NOT NULL DEFAULT \'0\', post_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX type(type), INDEX prefix_uid(prefix, uid), INDEX prefix_uid_type(prefix, uid, type), UNIQUE md5_hash(md5_hash, type), INDEX save (save)) ENGINE = MYISAM');
		}else{			return false;
		}
	}
}

?>