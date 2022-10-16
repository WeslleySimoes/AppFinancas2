
<div style="display: flex; justify-content: center; margin-bottom: 10px;">
    <form action="<?= HOME_URL ?>/contas/listar" id="form_data" method="GET">
        <input type="month" name="data" onchange="handler();" value="<?= !isset($_GET['data']) ? date('Y-m') :  date($_GET['data']) ?>"  style="border: 2px solid #0476B9; padding: 10px 5px; font-size: 0.9em; border-radius: 10px; font-weight: bold; color: #0476B9;">
    </form>
</div>
<h3 style="margin-bottom: 20px; color: #263D52;">
    Dashboard
    <br>
    <span style="font-size: 15px; color:#2e465c; ">
        <?= getSDMA() ?>
    </span>
</h3>

<div class="dadosFinanceiros">
    <div class="dadoItem dadoItem-1">
        <div style="display: flex;padding:20px 0 0 15px;">
            <div>
                <i class="fa fa-area-chart" aria-hidden="true"></i>
            </div>
            <div>
                Receitas: <br/> R$ <?= formatoMoeda($totalReceitas->total) ?>
            </div>
        </div>
        <div style="border: 1px solid #2F8F5A; position:absolute; bottom: 0;  border-bottom-left-radius: 25px; padding: 3px 15px; border-bottom-right-radius: 25px; width: 100%; background-color:#35A165; ">
            <a href="<?= HOME_URL ?>/transacoes?tipo=receita" style="text-decoration: none; color: white; display:inline-block; width: 100%;">ver mais ...</a>
        </div>
    </div>

    <div class="dadoItem dadoItem-2">
        <div style="display: flex;padding: 20px 0 0 15px;">
            <div>
                <i class="fa fa-shopping-basket" aria-hidden="true"></i> 
            </div>
            <div>
                Despesas:<br/> R$ <?= formatoMoeda($totalDespesas->total) ?>
            </div>
        </div>
        <div style="border: 1px solid #903142; position:absolute; bottom: 0;  border-bottom-left-radius: 25px; padding: 3px 15px; border-bottom-right-radius: 25px; width: 100%; background-color:#D84A63; ">
            <a href="<?= HOME_URL ?>/transacoes?tipo=despesa" style="text-decoration: none; color: white; display:inline-block; width: 100%;">ver mais ...</a>
        </div>
    </div>

    <div class="dadoItem dadoItem-3">

        <div style="display: flex;padding: 20px 0 0 15px;">
            <div>
                <i class="fa fa-university" aria-hidden="true"></i> 
            </div>
            <div>
                Saldo em Contas:<br/> R$ <?= formatoMoeda($totalSaldoContas) ?>
            </div>
        </div>
        <div style="border: 1px solid #2A73AA; position:absolute; bottom: 0;  border-bottom-left-radius: 25px; padding: 3px 15px; border-bottom-right-radius: 25px; width: 100%; background-color:#3694DB; ">
            <a href="<?= HOME_URL ?>/contas/listar" style="text-decoration: none; color: white; display:inline-block; width: 100%;">ver mais ...</a>
        </div>    
    </div>
</div>

<!-- <div class="formularioUsuario">
    <h3>Pendências e Alertas</h3>
    <div class="containerForm">
        Despesas pendentes<br>
        R$ 2.586,00
    </div>
</div> -->

<div class="formularioUsuario">
    <h3>Receitas x Despesas</h3>
    <div class="containerForm dsd">
        <canvas id="myChart" width="400" height="125"></canvas>
    </div>
</div>

<div class="outrosDados">

    <div class="formularioUsuario">
        <h3>Despesas por categoria</h3>
        <div class="containerForm">
            <?php if(strlen($arr_total_despesas) > 0): ?>
                <canvas id="myChartPizza" width="100%" height="400"></canvas>
            <?php else: ?>
                Nenhuma despesa encontrada!
            <?php endif; ?>
        </div>
    </div>
    <div class="formularioUsuario">
        <h3>Receitas por categoria</h3>
        <div class="containerForm">
        <?php if(strlen($arr_total_receitas) > 0): ?>
                <canvas id="myChartPizza2" width="600" height="400"></canvas>
            <?php else: ?>
                Nenhuma receita encontrada!
            <?php endif; ?>
        </div>
    </div>
</div>

<div>
<div class="formularioUsuario">
        <h3>Últimas 10 transações</h3>
        <div class="containerForm">
            <?php if(!empty($ultimasTransacoes)): ?>
                <table style="width: 100%;">
                    <tr>
                        <th>Data</th>
                        <th>Valor</th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                    </tr>
                    <?php foreach($ultimasTransacoes  as  $transacao ): ?>
                        <tr>
                            <td><?= formataDataBR($transacao->data_trans) ?></td>
                            <td style="white-space: nowrap;">R$ <?= formatoMoeda($transacao->valor) ?></td>

                            <?php if(isset($transacao->id_categoria)): ?>
                                <td style="white-space: nowrap;" ><span style="width: 15px; height: 15px;display:inline-block;border-radius: 50%; margin-right: 5px; background-color:<?=  $transacao->getCorCategoria() ?>;"></span><?= $transacao->getNomeCategoria() ?></td>
                            <?php else: ?>
                                <td style="white-space: nowrap;"> <span style="width: 15px; height: 15px;display:inline-block;border-radius: 50%; margin-right: 5px; background-color:grey;"></span>transferência</td>
                            <?php endif; ?>

                            <td> <?= ucfirst($transacao->tipo) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                Nenhuma transação encontrada!
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- SCRIPT CHART.JS -->

<script>
    const ctx = document.getElementById('myChart');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Fevereiro', 'Março', 'Abril', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: 'Despesas',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ]
            }]
        },
        options: {
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return tooltipItem.xLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            "responsonsive": true,
            "maintainAspectRatio": false,
            
            // oculta a label(legenda) do gráfico
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>

<script>
    
    const dataPizza = {
        labels: [<?= $arr_nomeCate_despesas ?>],
        datasets: [{
            label: 'My First Dataset',
            data: [<?= $arr_total_despesas ?>],
            backgroundColor: [<?= $cores_despesas ?>],
            hoverOffset: 4
        }]
    };
    const configPizza = {
     type: 'pie',
     data: dataPizza
    // ,options:{
    //     // oculta a label(legenda) do gráfico
    //     plugins: {
    //         legend: {
    //             display: false
    //         }
    //     }
    // }

   };

const myChartPizza = new Chart(
        document.getElementById('myChartPizza'),
        configPizza
    );
</script>


<script>
    
    const dataPizza2 = {
        labels: [<?= $arr_nomeCate_receitas ?> ],
        datasets: [{
            label: 'My First Dataset',
            data: [<?= $arr_total_receitas ?>],
            backgroundColor: [<?= $cores_receitas ?>],
            hoverOffset: 4
        }]
    };
    const configPizza2 = {
     type: 'pie',
     data: dataPizza2
    // ,options:{
    //     // oculta a label(legenda) do gráfico
    //     plugins: {
    //         legend: {
    //             display: false
    //         }
    //     }
    // }

   };

    const myChartPizza2 = new Chart(
        document.getElementById('myChartPizza2'),
        configPizza2
    );
</script>
<!-- FIM DO SCRIPT CHART.JS -->