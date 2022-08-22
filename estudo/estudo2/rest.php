<?php 

require_once __DIR__.'/app/config/config.php';

header('Content-Type: application/json; charset=utf-8');

spl_autoload_register(function($classe)
{
    $classe = str_replace('\\',DIRECTORY_SEPARATOR,$classe);

    if(file_exists($classe.'.php'))
    {
        require_once $classe.'.php';
    }
});

if(isset($_GET['token']) && $_GET['token'] == '123')
{
    $an = new app\services\AnotacaoServices;
    
    $arr = [
        'status' => 'success',
        'data'   => $an->listar()
    ];
    
    echo json_encode($arr);
}
else{
    echo json_encode(['status' =>'error','msg'=>'Token nao encontrado ou incorreto!']);
}




/*
$arr = [
    [
        'id'        => 1,
        "nome"      => 'Francisco',
        'sobrenome' => 'Silva',
        'email'     => 'fran@teste.com'
    ],
    [
        'id'        => 1,
        "nome"      => 'Francisco',
        'sobrenome' => 'Silva',
        'email'     => 'fran@teste.com'
    ],
    [
        'id'        => 1,
        "nome"      => 'Francisco',
        'sobrenome' => 'Silva',
        'email'     => 'fran@teste.com'
    ]
];

$retorno = [
    'status' => 'error  ',
    'data' => $arr
];

echo json_encode($retorno);

*/