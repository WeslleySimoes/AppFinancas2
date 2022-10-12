<?php 


function is_hex($hex_code) 
{
    if(str_contains($hex_code,'#'))
    {
        $hex_code = explode('#',$hex_code)[1];
    }
    
    $resultado = @preg_match("/^[a-f0-9]{2,}$/i", $hex_code) && !(strlen($hex_code) & 1);

    if(!$resultado){
        
    }
}


var_dump(is_hex('#f6b73c'));