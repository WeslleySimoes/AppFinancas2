<?php 

	namespace app\session;

	class Usuario extends Session
	{
		public static function setProp(array $valor)
		{
			self::add('usuario',$valor);
		}

		public static function getProp($prop = null)
		{
			if($prop != null)
			{
				return self::get('usuario')[$prop];
			}
			return self::get('usuario');
		}

		public static function logado()
		{
			if(empty(self::get('usuario')))
			{
				header('location: ./');
				exit;
			}
		}

	}