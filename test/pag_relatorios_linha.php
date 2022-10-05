<?php 

if(!empty($_GET))
{
    echo '<pre>';
    var_dump($_GET);
    echo '</pre>';
}



$period = new DatePeriod(
    new DateTime('2022-09-29'),
    new DateInterval('P1D'),
    new DateTime('2022-10-06'),
);

$arrDate = [];

foreach ($period as $key => $value) {
   $arrDate[] =  $value->format('Y-m-d');
}

//$arrDate = array_reverse($arrDate);

$arrFinal = "'".implode("','",$arrDate)."'";

// echo $arrFinal;


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <script src="chart.js/package/dist/chart.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&family=Roboto:ital,wght@1,500&display=swap" rel="stylesheet">

    <title>Document</title>
    <style>
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Lato', sans-serif;
        }

        #links-grafico-relatorios{
            border: 1px solid lightgrey;
            border-radius: 25px;
            font-size: 25px;
            text-decoration: none;
            
            display: flex;
            padding: 10px;
        }

        #links-grafico-relatorios a{
            display: block;
            padding: 0 10px;
        }

        .container{
            width: 960px;
            margin: 0 auto;
            padding: 10px;
            min-height: 100vh;
        }

        .titulo_rela{
            display: flex;
            justify-content:space-between;
            align-items: center;
            /* border: 1px solid black; */
        }

        .conteudo{
            margin: 10px 0;
            padding: 10px;
            border: 1px solid lightgray;
        }
        .grafico
        {
            width: 55%;
            margin-right: 10px;
        }

        .content-grafico{
            width: 45%;
            /* border: 1px solid black; */
            height: 600px;
            overflow-y: scroll;
        }

        .grafico,.content-grafico{
            /* border: 1px solid black; */
            padding: 10px;

        }
        .item-content-grafico{
            /* border: 1px solid black; */
            /* background-color: ; */
            padding: 10px;
            margin: 10px 0;
            font-size: 1.2em;
            display: flex;
            border-radius: 25px;
            -webkit-box-shadow: -1px 10px 17px 0px rgba(59,59,59,0.38); 
             box-shadow: -1px 10px 17px 0px rgba(59,59,59,0.38);
        }

        .item-content-grafico span 
        {
            display: block;
            margin-right: 5px;
            /* border: 1px solid black; */
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: tomato;
        }
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color:#e0e0e0 ;  
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="titulo_rela">
            <h1 style="color: #263D52;">Relatórios</h1>

            <div id="links-grafico-relatorios">
                <a href="#pizza"><i class="fa fa-pie-chart" aria-hidden="true"></i></a>
                <a href="#linha"><i class="fa fa-line-chart" aria-hidden="true"></i></a>
                <a href="#barra"><i class="fa fa-bar-chart" aria-hidden="true"></i></a>
            </div>
        </div>
        <div style="padding: 10px; margin-top: 10px;border: 1px solid lightgray; display: flex; justify-content:space-between; align-items: center;">
            <h3>Despesas</h3>
            <h3>14/09/22 - 22/09/22</h3>
            <button style="font-size: 25px;" id="filtro-pizza"> <i class="fa fa-filter" aria-hidden="true"></i></button>
        </div>
        <div class="conteudo">
            <canvas id="myChart"></canvas>
            <div style="margin: 20px 0px;">
                <table style="width: 100%;">
                    <!-- <tr style="text-align: left;">
                        <th>Data</th>
                        <th>Valor</th>
                    </tr> -->
                    <?php for( $i = 0 ; $i <= 10 ; $i++ ): ?>
                        <tr>
                            <td>12/09/2022</td>
                            <td>R$ 500,00</td>
                        </tr>
                    <?php endfor; ?>
                    <tr style="text-align: left;">
                        <th>Total</th>
                        <th>R$ 5.000,00</th>
                    </tr>
                </table>
            </div>
        </div>

    </div>

    <div id="background-popUp-pizza" style="position: fixed; top:0; left: 0; width: 100%; min-height: 100vh;background-color: rgba(1,1,1,0.6); display:none; ">
        <div style="width: 600px; background-color: white; padding: 10px;">

            <div style="margin-bottom: 20px;">
                <button id="fechar-popUp-pizza" style="float: right; border: none; font-size: 23px;">X</button>
            </div>

            <form action="pag_relatorios_linha.php" method="GET" style="padding: 10px;">

            <div style="margin: 10px 0;"> 
                <input type="radio" id="dataAno" name="dataRadio" value="dataAno" checked>
                <label for="ano"><b>Ano:</b></label>
                <select name="ano" data-component="date" style="width: 100%; padding: 10px;" id="selectedANO" disabled>
                <?php
                    for($year=date('Y'); $year>=1900; $year--){
                        if($year == date('Y'))
                        {
                            echo '<option value="'.$year.'" selected>'.$year.'</option>';
                        }
                        else{
                            echo '<option value="'.$year.'">'.$year.'</option>';
                        }
                    }
                ?>
                </select>
            </div>

                <div>
                    <input type="radio" id="dataMesAno" name="dataRadio" value="dataMesAno" checked>
                    <label for="mesAno" style="font-weight: bold;">Mês:</label><br>
                    <input type="month" name="mesAno" value="<?= date("Y-m") ?>" style="width: 100%;  padding: 10px 5px;" id="mesAno"><br>
                </div>

                <div style="margin: 10px 0;">
                    <input type="radio" id="dataPeriodo" name="dataRadio" value="dataPeriodo">
                    <label for="Periodo" style="font-weight: bold;">Periodo:</label>
                    <div style="display: flex; margin: 5px 0;">
                        <label for="dataIncio" style="display: flex; align-items: center;">De:</label>
                        <input type="date" name="dataInicio" value="<?= date("Y-m-d") ?>" style="width: 50%;margin: 0 10px 0 5px;" id="dataPeriodoInicio" disabled>
                        <label for="dataFim" style="display: flex; align-items: center;">Até:</label>
                        <input type="date" name="dataFim" value="<?= date("Y-m-d") ?>" style="width: 50%; margin-left: 5px; padding: 10px 5px;"  id="dataPeriodoFim" disabled>
                    </div>
                </div>
                <hr>
                <div style="margin: 10px 0;">
                    <label for="" style="font-weight: bold;">Selecione:</label>
                    <select name="select" style="width: 100%;padding: 10px 5px;">
                        <option value="1">Despesas</option>
                        <option value="2" >Receitas</option>
                    </select>
                </div>
                <div style="margin: 10px 0;">
                    <label for="" style="font-weight: bold;">Situação:</label>
                    <select name="situacao" style="width: 100%;padding: 10px 5px;">
                        <option value="todas">Todas</option>
                        <option value="efetuadas">Efetuadas</option>
                        <option value="pendentes">Pendentes</option>
                    </select>
                </div>
              
                <div style="margin: 10px 0;">
                    <label for="" style="font-weight: bold;">Categorias:</label>
                    <select name="conta" style="width: 100%;padding: 10px 5px;">
                        <option value="0">Todas</option>
                        <option value="1">Categoria 1</option>
                        <option value="2">Categoria 2</option>
                        <option value="3">Categoria 3</option>
                        <option value="4">Categoria 4</option>
                    </select>
                </div>

                <div style="margin: 10px 0;">
                    <label for="" style="font-weight: bold;">Conta:</label>
                    <select name="conta" style="width: 100%;padding: 10px 5px;">
                        <option value="0">Todas</option>
                        <option value="1">Conta1</option>
                        <option value="2">Conta2</option>
                        <option value="3">Conta3</option>
                    </select>
                </div>


                <button type="submit">Filtrar</button>
            </form>
        </div>
    </div>


    <script>
        const fechar_popUp_pizza = document.querySelector("#fechar-popUp-pizza");
        const filtro_pizza = document.querySelector("#filtro-pizza");

        const background_popUp_pizza = document.querySelector("#background-popUp-pizza");

        fechar_popUp_pizza.onclick = () => {
    
            background_popUp_pizza.style.display = 'none';
        }

        filtro_pizza.onclick = () => {
            background_popUp_pizza.style.display = 'flex';
            background_popUp_pizza.style.justifyContent = 'center';
            background_popUp_pizza.style.alignItems  = 'center';
        }

        /* DATA */
        const dataMesAno  = document.querySelector("#dataMesAno");
        const dataPeriodo = document.querySelector("#dataPeriodo");
        const selectedANO = document.querySelector("#selectedANO");

        //inputs
        const dataAno = document.querySelector("#dataAno");
        const mesAno = document.querySelector("#mesAno");
        const dataPeriodoInicio = document.querySelector("#dataPeriodoInicio");
        const dataPeriodoFim    = document.querySelector("#dataPeriodoFim");


        dataAno.onclick = () => {
            if(dataAno.checked)
            {
                mesAno.disabled = true;
                selectedANO.disabled = false;
                dataPeriodoInicio.disabled = true;
                dataPeriodoFim.disabled = true;
            }
        }

        dataPeriodo.onclick = () => 
        {
            if(dataPeriodo.checked)
            {
                mesAno.disabled = true;
                selectedANO.disabled = true;
                dataPeriodoInicio.disabled = false;
                dataPeriodoFim.disabled = false;
            }
        }

        dataMesAno.onclick = () => 
        {
            if(dataMesAno.checked)
            {
                mesAno.disabled = false;
                selectedANO.disabled = true;
                dataPeriodoInicio.disabled = true;
                dataPeriodoFim.disabled = true;
            }
        }

    </script>

        
    <script>

function getWeekFromStartDay(start) {
  var weekDays = [];
  var curr = new Date(); // get current date
  var first = curr.getDate() - curr.getDay() + start;

  for (let i = first; i < first + 7; i++) {
    let day = new Date(curr.setDate(i)).toISOString().slice(0, 10);
    weekDays.push(day);
  }
  return weekDays;
}

console.log("Last week");
console.log(getWeekFromStartDay(-7));
            
           
            const data = {
                labels: [<?= $arrFinal ?>],
                datasets: [{
                    //label: 'My First Dataset',
                    data: [65.25, 59.5, 80.58, 81.35, 56.15, 55.25, 40.8],
                    fill: false,
                    borderColor: 'rgb(255,0,0)',
                    tension: 0.1
                }]
            };

            const config = {
                type: 'line',
                data: data,
                options:{
                    // oculta a label(legenda) do gráfico
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    locale: 'pt-BR'
                }
            };

            const myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
    </script>
</body>
</html>