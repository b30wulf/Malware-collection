<?php

class services {
    private $bot, $system, $mysqli;
    
    function __construct(){
        global $bot, $system, $mysqli;
        $this->bot = &$bot;
        $this->system = &$system;
        $this->db = &$mysqli;
    }
}

?>