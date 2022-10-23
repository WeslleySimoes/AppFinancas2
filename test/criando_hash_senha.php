<?php 

$senha = '12345678';

$hashCriado =  password_hash($senha,PASSWORD_DEFAULT);

$verifica = password_verify('12345678',$hashCriado);

var_dump($verifica);


// jose@pires.com
// Rta3v123

// weslley@teste.com
// 123456ac

// weslley_silvas@hotmail.com
// 12345678