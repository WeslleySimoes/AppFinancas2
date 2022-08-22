<?php 
	

	/**
	 * Classe Responsável por manipular a linguagem do site, através da sessão
	 * */
	class LangSession
	{
		const IDIOMAS = ['pt-br','en'];

		public static function set($lang)
		{
			if(in_array($lang,self::IDIOMAS))
			{
				$_SESSION['lang'] = $lang;			
			}
			else{
				$_SESSION['lang'] = self::IDIOMAS[0];
			}
		}

		public static function get()
		{
			return $_SESSION['lang'];
		}
	}