<?php 

//OBTENDO O ÚLTIMO DIA DOS MÊS DEFINIDO
echo 'data inicio: '.date('Y-m').'-01','<br>';


$mes = date('m');      // Mês desejado, pode ser por ser obtido por POST, GET, etc.
$ano = date("Y"); // Ano atual
$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // Mágica, plim!

echo 'data fim: '.date('Y-m')."-{$ultimo_dia}",'<br>';