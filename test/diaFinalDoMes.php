<?php 

//OBTENDO O ÚLTIMO DIA DOS MÊS DEFINIDO
$mes = '10';      // Mês desejado, pode ser por ser obtido por POST, GET, etc.
$ano = date("Y"); // Ano atual
$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // Mágica, plim!

echo $ultimo_dia;