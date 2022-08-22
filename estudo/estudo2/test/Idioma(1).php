<?php 
	
	/**
	 * Classe responsável por retornar um array com a linguagem requisitada
	*/
	class Idioma
	{
		const IDIOMAS = ['pt-br','en'];

		private $idioma;
		private $file;

		public function __construct($idioma,$file)
		{
			$this->setIdioma($idioma);
			$this->setFile($file);
		}

		public function setFile($file)
		{
			if(file_exists($file))
			{
				$this->file = $file;
			}
			else{
				throw new Exception('Arquivo não encontrado!');
			}
		}

		public function setIdioma($idioma)
		{
			if(in_array($idioma,self::IDIOMAS))
			{
				$this->idioma = $idioma;				
			}
			else{
				$this->idioma = self::IDIOMAS[0];
			}
		}

		public function obterArray()
		{
			if(isset($this->file))
			{
				return parse_ini_file($this->file,true)[$this->idioma];
			}
		}

	}