<?php 
	namespace app\controller;

	use lib\control\Controller;

	class Erro extends Controller
	{
		public function index()
		{
			http_response_code(404);
			$this->view(['erro404']);
		}
	}