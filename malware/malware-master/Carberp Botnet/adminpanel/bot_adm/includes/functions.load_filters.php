<?php

if(!function_exists('load_filters')){	function load_filters($row){
		global $filters, $flist;
        if(is_object($row)) $row = get_object_vars($row);
		if(!isset($flist['bf_filter_' . $row['id']])){
    		$check = create_filter($row['id']);
    	}else{
    		$check = true;
    	}

    	if($check == true){
    		if(strpos($row['host'], ',') != false){
    			$hosts = explode(',', $row['host']);
    			if(count($hosts) > 0){
    				foreach($hosts as $host){
    					$row['host'] = $host;
    					$filters[$row['host']] = $row;
    				}
    			}
    		}else{
    			$filters[$row['host']] = $row;
    		}
		}
	}
}

if(!function_exists('load_flist')){	function load_flist($row){		global $flist;
		if(!empty($row->Name)){
			$flist[$row->Name] = true;
   	 	}
	}
}

?>