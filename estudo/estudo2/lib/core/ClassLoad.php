<?php 

namespace lib\core;

class ClassLoad
{
	public function register()
	{
		spl_autoload_register(array($this,'load'));
	}

	public function load($classe)
	{
        $arquivo = str_replace('\\',DIRECTORY_SEPARATOR,$classe);
        $arquivo = HOME_DIR."/{$arquivo}.php";

        if(file_exists($arquivo))
        {
            require_once $arquivo;
        }			
	}
}