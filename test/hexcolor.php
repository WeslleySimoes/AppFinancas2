<?php 


function is_hex($hex_code) {
    return @preg_match("/^[a-f0-9]{2,}$/i", $hex_code) && !(strlen($hex_code) & 1);
}


var_dump(is_hex('f6b73c'));