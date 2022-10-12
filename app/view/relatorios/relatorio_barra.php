
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

        .conteudo978{
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
<?= $msg ?>
<div class="titulo_rela">
    <h3 style="margin-bottom: 20px; color: #263D52;">Relatórios</h3>

    <div id="links-grafico-relatorios">
        <a href="<?= HOME_URL ?>/relatorios"><i class="fa fa-pie-chart" aria-hidden="true"></i></a>
    <a href="<?= HOME_URL ?>/relatorios/linha"><i class="fa fa-line-chart" aria-hidden="true"></i></a>
    <a href="<?= HOME_URL ?>/relatorios/barra"><i class="fa fa-bar-chart" aria-hidden="true"></i></a>
    </div>
</div>
<div style="padding: 10px; margin-top: 10px;border: 1px solid lightgray; display: flex; justify-content:space-between; align-items: center;">
    <h3>Balanço mensal</h3>
    <h3>
        
        <form action="<?= HOME_URL ?>/relatorios/barra" method="get" id="form_data">
            <select name="ano" data-component="date" style="width: 100%; padding: 10px;" id="selectedANO" onchange="handler();">
                <?php
                    for($year=date('Y'); $year>=1900; $year--){
                        if(isset($_GET['ano']) AND $year == $_GET['ano'])
                        {
                            echo '<option value="'.$year.'" selected>'.$year.'</option>';
                        }
                        else{
                            echo '<option value="'.$year.'">'.$year.'</option>';
                        }
                    }
                ?>
            </select>
        </form>
    </h3>
    <!-- <button style="font-size: 25px;" id="filtro-pizza"> <i class="fa fa-filter" aria-hidden="true"></i></button> -->
</div>
<div class="conteudo978">
    <canvas id="myChart"></canvas>
    <div style="margin: 20px 0px;">
        <table style="width: 100%;">
            <tr style="text-align: left;">
                <th>Mês</th>
                <th>Receita</th>
                <th>Despesa</th>
                <th>Balanço</th>
            </tr>
            <?php foreach ($arr_despesas as $mes => $valor): ?>
                <tr>
                    <td style="font-weight:600;"><?= ucfirst($mes) ?></td>
                    <td style="color:#1b8716;font-weight:600;">R$ <?= formatoMoeda($arr_receitas[$mes]) ?></td>
                    <td style="color: #ff3232;font-weight:600;">R$ <?= formatoMoeda($valor) ?></td>
                    <td style="color:#2986cc; font-weight:600;">R$ <?= formatoMoeda($arr_balancoMensal[$mes]) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr style="text-align: left;">
                <th>Total</th>
                <th>R$ <?= formatoMoeda(array_sum(array_values($arr_receitas))) ?></th>
                <th>R$ <?= formatoMoeda(array_sum(array_values($arr_despesas))) ?></th>
                <th>R$ <?= formatoMoeda(array_sum(array_values($arr_balancoMensal))) ?></th>
            </tr>
        </table>
    </div>
</div>
<script>


const formData = document.querySelector('#form_data');

function handler()
{
    formData.submit();
}


    new Chart(document.getElementById("myChart"), {
    type: 'bar',
    data: {
      labels:['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
      datasets: [
        {
          label: "Despesa",
          backgroundColor: "#ff3232",
          data: [<?= implode(',',$arr_despesas) ?>]
        }, {
          label: "Receita",
          backgroundColor: '#0BB783',
          data: [<?= implode(',',$arr_receitas) ?>]
        },
        {
          label: "Balanço do Mês",
          backgroundColor: '#2986cc',
          data: [<?= implode(',',$arr_balancoMensal) ?>]
        }
      ]
    },
    options: {
      title: {
        display: true,
        text: 'Population growth (millions)'
      },
      scales:{
        y:{
            beginAtZero: true,
            grid:{
                color: (context) => {
                    if(context.tick.value === 0)
                    {
                        return 'rgba(1,1,1,1)'; //Colorindo a linha ZERO do gráfico
                    }else{
                        return 'rgba(0,0,0,0.1)';//Colorindo as outras linhas do gráfico
                    }
                }
            }
        }
      }
    }
});
    </script>
</body>
</html>