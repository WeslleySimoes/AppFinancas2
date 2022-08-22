<?php 	
	// Define o nome do diretório base
	define('NAME_BASE_DIR','projeto_finan'); 
	// Define o caminho da url base
	define('HOST','http://localhost');

	/*CAMINHOS VIA DIRETÓRIO*/
	define('HOME_DIR',$_SERVER['DOCUMENT_ROOT'].'/'.NAME_BASE_DIR);
	define('CONFIG_DIR',$_SERVER['DOCUMENT_ROOT']."/".NAME_BASE_DIR."/app/config");
	define('VIEW_DIR',$_SERVER['DOCUMENT_ROOT']."/".NAME_BASE_DIR."/app/view");
	define('CONTROLLER_DIR',$_SERVER['DOCUMENT_ROOT']."/".NAME_BASE_DIR."/app/controller");
	define('MODEL_DIR',$_SERVER['DOCUMENT_ROOT']."/".NAME_BASE_DIR."/app/model");

	/*CAMINHOS VIA URL*/
	define('HOME_URL',HOST.'/'.NAME_BASE_DIR); //url base do projeto
	define('ASSET_URL',HOST."/".NAME_BASE_DIR."/assets");
	define('ASSET_CSS_URL',HOST."/".NAME_BASE_DIR."/assets/css");
	define('ASSET_JS_URL',HOST."/".NAME_BASE_DIR."/assets/js");

	//CONFIGURAÇÃO DE LOCALIZAÇÃO DO SISTEMA
	setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');