<?php 

namespace lib\control;

class Controller
{
	protected function view(array $templates, array $dados = [])
	{
		if(empty($templates))
		{
			throw new \Exception('Nenhum template encontrado!');
		}
		
		if(!empty($dados))
		{
			extract($dados);
		}

		foreach ($templates as $template) {
			require_once VIEW_DIR."/{$template}.php";
		}
	}
}