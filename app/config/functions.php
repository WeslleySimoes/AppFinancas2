<?php 

//Função de debug
function dd($var,$config = true)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';

    if($config)
    {
        exit();
    }
}

/*Formata o float para moeda */
function formatoMoeda(float $valor,$moeda = 'REAL',$casasDecimais = 2)
{
    switch ($moeda) {
        case 'REAL':
            return  number_format($valor, $casasDecimais, ',', '.');
            break;
        case 'DOLAR':
            return  number_format($valor, $casasDecimais, '.', ',');
            break;
        case 'EURO':
            return  number_format($valor, $casasDecimais, ',', ' ');
            break;
        default:
            throw new \Exception("Nenhuma moeda encontrada!");
    }
}

/*Formata Moeda para float */


/* FUNCÃO QUE RETORNA DIA DA SEMANA, DIA DE MES TAL E O ANO, EX: segunda-feira, 08 de agosto de 2022 */
/*
    S - Nome do dia da Semana atual (segunda-feira)
    D - Dia do mês atual (08)
    M - Nome do mês atual (Agosto)
    A - Ano atual (2022)
*/
function getSDMA()
{
    return ucfirst(utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today')))); 
}


/*FORMATA DATA PARA MODELO BRASILEIRO*/
function formataDataBR($data)
{
    $data = new \DateTime($data);
    return $data->format('d/m/Y');
}

