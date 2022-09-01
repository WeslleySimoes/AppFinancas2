<?php	
	session_start();

	require_once __DIR__."/app/config/config.php";
	require_once __DIR__."/app/config/functions.php";
	require_once __DIR__."/core/ClassLoad.php";
	require_once __DIR__."/core/Rota.php";
	
	(new ClassLoad)->register();
	
	$rota = new Rota;

	// LOGIN
	$rota->add('/','login@index');

	//DASHBOARD
	$rota->add('/dashboard','home@index');
	
	// Area de contas
	$rota->add('/contas/listar','contas@index');
	$rota->add('/contas/cadastrar','contas@cadastrar');

	// Area de transacoes
	$rota->add('/transacoes','transacoes@index');

	// Area de receitas
	$rota->add('/receita/cadastrar','receita@cadastrar');
	$rota->add('/receita/editar','receita@editar');
	$rota->add('/receita/remover','receita@remover');
	$rota->add('/receita/efetivar','receita@efetivar');
	$rota->add('/receita/editarFP','receita@editarFP');
	$rota->add('/receita/removerFP','receita@removerFP');

	// Area de despesas
	$rota->add('/despesa/cadastrar','despesa@cadastrar');
	$rota->add('/despesa/editar','despesa@editar');
	$rota->add('/despesa/remover','despesa@remover');
	$rota->add('/despesa/efetivar','despesa@efetivar');
	$rota->add('/despesa/editarFP','despesa@editarFP');
	$rota->add('/despesa/removerFP','despesa@removerFP');

	// Area de categorias
	$rota->add('/categorias','categoria@index');
	$rota->add('/categorias/cadastrar','categoria@cadastrar');

	// Transferencias
	$rota->add('/transferencia/cadastrar','transferencia@cadastrar');
	$rota->add('/transferencia/editar','transferencia@editar');
	$rota->add('/transferencia/remover','transferencia@remover');
	$rota->add('/transferencia/efetivar','transferencia@efetivar');

	
	// Deslogar
	$rota->add('/deslogar','Home@deslogar');
	
	$rota->executar();


	/*
		Links para estudo:

		https://www.eversondaluz.com.br/menu-vertical-com-3-niveis-css

		https://docs.awesomeapi.com.br/api-de-moedas

		https://phptherightway.com/

	*/
