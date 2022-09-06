<?php 
/*
	
		SISTEMA DE CONTAGEM DE ACESSO
	
	$arquivo = 'contador.txt';

	if(file_exists($arquivo))
	{
		$valor = file_get_contents($arquivo);
		$valor = (int) trim($valor);
		$valor++;
	}
	else{
		$valor = 0;
	}

	//Grava o novo valor no arquivo
	$ponteiro = fopen($arquivo,'w');
	fwrite($ponteiro,$valor);
	fclose($ponteiro);

	echo "Total de visitas: {$valor}";*/

	$cnpj = '65.173.203/0001-25';

	var_dump(preg_match('/^\d{2}\.\d{3}\.\d{3}\/\d{4}\-\d{2}$/',$cnpj));