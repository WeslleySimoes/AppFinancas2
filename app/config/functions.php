<?php 

//Função de debug
function dd($var,$config = true)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';

    if($config) exit();
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
function formataDataBR($data,$formato = 'd/m/Y')
{
    $data = new \DateTime($data);
    return $data->format($formato);
}

//Retorna a data final de mês atual
function getDataFinalMesAtual($data = null,$soma = 0)
{
    if($data)
    {
        $d = explode('-',$data);
        $mes = date($d[1]); // Mês desejado, pode ser por ser obtido por POST, GET, etc.
        $ano = date($d[0]); // Ano atual
    }
    else{
        $mes = date('m'); // Mês desejado, pode ser por ser obtido por POST, GET, etc.
        $ano = date("Y"); // Ano atual
    }
    $ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // Mágica, plim!
    $ultimo_dia+= $soma;

    if($data)
    {
        return $ultimo_dia;    
    }

    return date('Y-m')."-{$ultimo_dia}";
}

function diffDataDias($data_fim)
{
    $data_inicio = new DateTime();
    $data_fim = new DateTime($data_fim);

    // Resgata diferença entre as datas
    $dateInterval = $data_inicio->diff($data_fim);
    return $dateInterval->days;
}

// function getDatasDoMes()
// {
//     $inicio = date('Y-m').'-01';
//     $final  = getDataFinalMesAtual();

//     $period = new \DatePeriod(
//         new \DateTime('2022-10-31'),
//         new \DateInterval('P1D'),
//         new \DateTime('2022-10-01'),
//     );
    
//     $arrDate = [];
    
//     foreach ($period as $key => $value) {
//         $arrDate[] =  $value->format('Y-m-d');
//     }

//     return $arrDate;
// }