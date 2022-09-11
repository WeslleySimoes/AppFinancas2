<?php 

function arrayDiff()
{
    
    $a = [1,2,3,4,5];
    $b = [5,3,2]; 
    
    echo 'QTD B:'.count($b);
    echo '<br> QTD A:'.count($a).'<br>';
    
    echo '<pre>';
    var_dump(array_diff($b,$a));
    echo '</pre>';
    
    if(empty(array_diff($b,$a)))
    {
        echo 'Tudo Okay';
    }
    else{
        echo 'Contém ID inexistente';
    }
}

function arrayMap()
{
    $valores = [
        '',
        '0',
        '15.52'
    ];

    array_map(function ($valor){
        return $valor != '' or $valor != '0'
    },$valores);
}