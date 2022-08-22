<?php 

	namespace app\model;

	class Artigos
	{
		public static function getAll()
		{
			return [
					[
						'titulo_artigo' 	=> 'Artigo 1',
						'conteudo_artigo'   => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo'
					],
					[
						'titulo_artigo' 	=> 'Artigo 2',
						'conteudo_artigo'   => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo'
					],
					[
						'titulo_artigo' 	=> 'Artigo 3',
						'conteudo_artigo'   => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo'
					],
					[
						'titulo_artigo' 	=> 'Artigo 4',
						'conteudo_artigo'   => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo'
					]															
				];
		}
	}