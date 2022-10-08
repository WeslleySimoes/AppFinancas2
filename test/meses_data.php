<?php 

$data1text = '2022-07-08';
$data2text = '2022-10-08';

$date1 = new DateTime($data1text);
$date2 = new DateTime($data2text);
//Repare que inverto a ordem, assim terei a subtração da ultima data pela primeira.
//Calculando a diferença entre os meses
$meses = ((int)$date2->format('m') - (int)$date1->format('m'))
//    e somando com a diferença de anos multiplacado por 12
    + (((int)$date2->format('y') - (int)$date1->format('y')) * 12);

echo $meses;//2me