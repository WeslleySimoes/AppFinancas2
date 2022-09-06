<?php 

namespace app\controller;

use lib\control\Controller;
use lib\validation\Validacao;
use lib\widgets\Alerta;

class Teste extends Controller
{
	public function index()
	{
		if(isset($_POST))
		{
			$validar = new Validacao;
			
			$validar->cep($_POST['cep']);
			$validar->moeda($_POST['moeda']);
			$validar->telefoneFixo($_POST['telefoneFixo']);
			$validar->celular($_POST['celular']);

			if($validar->validar())
			{
				Alerta::set('success','Todos os dados estão corretos!');
			}
			else{
				Alerta::set('danger',$validar->getMsgErros());
			}			
		}

		$this->view(['teste/home'],[
			'mensagem' => Alerta::get()
		]);
	}	
}