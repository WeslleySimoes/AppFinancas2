<?php 	

	define('PASTA_BASE','estudo2');

	define('HOST','http://localhost');

	/*CAMINHOS VIA DIRETÓRIO*/
	define('HOME_DIR',$_SERVER['DOCUMENT_ROOT'].'/'.PASTA_BASE);
	define('CONFIG_DIR',$_SERVER['DOCUMENT_ROOT'].'/estudo2/app/config');
	define('VIEW_DIR',$_SERVER['DOCUMENT_ROOT'].'/estudo2/app/view');
	define('CONTROLLER_DIR',$_SERVER['DOCUMENT_ROOT'].'/estudo2/app/controller');
	define('MODEL_DIR',$_SERVER['DOCUMENT_ROOT'].'/estudo2/app/model');

	/*CAMINHOS VIA URL*/
	define('HOME_URL',HOST.'/estudo2');
	define('ASSET_CSS_URL',HOST.'/estudo2/asset/css');
	define('ASSET_JS_URL',HOST.'/estudo2/asset/js');

	


