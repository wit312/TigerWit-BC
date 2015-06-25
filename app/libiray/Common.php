<?php
function isMobile($value,$match='/^[(86)|0]?(13\d{9})|(14\d{9})|(15\d{9})|(17\d{9})|(18\d{9})$/'){
    $v = trim($value);
    if(empty($v) || strlen($v)>15) return false;
    return preg_match($match,$v);
}