<?php

function TimeStampToStr($time_stamp, $time_zone = '+3', $format_date = 'd.m.Y H:i:s', $GMT_SHOW = True){
    switch($GMT_SHOW){
        case True:
            return gmdate($format_date, $time_stamp + (60*60) * ($time_zone+date("I"))) . " (GMT " . $time_zone . ")";
        break;

        case False:
            return gmdate($format_date, $time_stamp + (60*60) * ($time_zone+date("I")));
        break;
    }
}

function ts2str($time_stamp, $time_zone = '+3', $format_date = 'd.m.Y H:i:s', $GMT_SHOW = True){	return TimeStampToStr($time_stamp, $time_zone, $format_date, $GMT_SHOW);
}

?>