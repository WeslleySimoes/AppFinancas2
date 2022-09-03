<?php


$transacoes = [
    ['23/09/2022', 562.00],
    ['23/09/2022', 254.00],
    ['23/09/2022', 985,00],
    ['22/09/2022', 50.66],
];


$somaTotal = [
    ['23/09/2022', 1801],
    ['22/09/2022', 50.66]
];


$dataItem = '';
$soma = 0;

foreach($somaTotal as $total)
{
    echo "<b>Saldo Previsto Final do Dia: R$ {$total[1]}</b> <br>";
    
    foreach($transacoes as $t)
    {
        if($t[0] == $total[0])
        {
            echo $t[1],'<br>';
        }
    }
}