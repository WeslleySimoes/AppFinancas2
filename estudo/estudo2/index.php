<?php 
	session_start();

	require_once __DIR__."/app/config/config.php";
	require_once __DIR__."/lib/core/ClassLoad.php";
	require_once __DIR__."/lib/core/Router.php";

	(new lib\core\ClassLoad)->register();
	
	(new lib\core\Router([
		'/'		 		=> 'home@index',
		'/sobre' 		=> 'home@sobre',
		'/session' 		=> 'home@session',
		'/teste' 		=> 'home@teste',
		'/teste/index' 	=> 'teste@index'
	]))->executar();

	/*
		Links para estudo:

		https://www.eversondaluz.com.br/menu-vertical-com-3-niveis-css

		https://docs.awesomeapi.com.br/api-de-moedas

		https://phptherightway.com/

	*/
