<?php 
	namespace lib\widgets;

	class Alerta
	{
		const TIPOS = ['info','success','danger','warning'];

		private static $tipo;
		private static $msg;

		public static function set($tipo = 'info',$msg)
		{
			if(!in_array($tipo,self::TIPOS))
			{
				throw new \Exception('Tipo incorreto de alerta!');
				exit;
			}

			self::$tipo = $tipo;
			self::$msg  = $msg;
		}

		public static function get()
		{
			if(isset(self::$tipo) and isset(self::$msg))
			{
				if(is_array(self::$msg) && count(self::$msg) > 1){

					$divisao =  "<div class='card ".self::$tipo."'> <ul>";
					foreach(self::$msg as $mensagem)
					{	
						$divisao .= "<li>{$mensagem}</li>";
					}
					$divisao .= "</ul> </div>";

					return $divisao;
				}
				
				return "<div class='card ".self::$tipo."'>".(is_array(self::$msg) ? self::$msg[0] : self::$msg)."</div>";
			}
			return null;
		}
	}