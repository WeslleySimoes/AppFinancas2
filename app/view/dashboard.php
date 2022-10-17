

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
    <div class="containerForm">
        <?php if(array_sum($depMensal) > 0 OR array_sum($recMensal) > 0 ): ?>
            <canvas id="graficoBarra" height="120px"></canvas>
        <?php else: ?>
            <b>Nenhuma despesa ou receita encontrada!</b>
        <?php endif; ?>
    </div>
</div>

<div class="outrosDados">

    <div class="formularioUsuario">
        <h3>Despesas por categoria</h3>
        <div class="containerForm">
            <?php if(strlen($arr_total_despesas) > 0): ?>
                <canvas id="myChartPizza" width="100%" height="400"></canvas>
            <?php else: ?>
                <b>Opa! Você ainda não possui despesas este mês.</b>
            <?php endif; ?>
        </div>
    </div>
    <div class="formularioUsuario">
        <h3>Receitas por categoria</h3>
        <div class="containerForm">
        <?php if(strlen($arr_total_receitas) > 0): ?>
                <canvas id="myChartPizza2" width="600" height="400"></canvas>
            <?php else: ?>
                <b>Opa! Você ainda não possui receitas este mês.</b>
            <?php endif; ?>
        </div>
    </div>
</div>

<div>
<div class="formularioUsuario">
        <h3 style="display: flex; justify-content: space-between;align-items:flex-end;">
            <span> Últimas 10 transações </span>
            <a href="<?= HOME_URL ?>/transacoes" style="font-size: 0.8em; text-decoration: none; color: #263D52;"> Ver mais ...</a>
        </h3>
        
        <div class="containerForm">
            <?php if(!empty($ultimasTransacoes)): ?>
                <table style="width: 100%;">
                    <tr>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Valor</th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                    </tr>
                    <?php foreach($ultimasTransacoes  as $transacao ): ?>
                        <tr>
                            <td><?= formataDataBR($transacao->data_trans) ?></td>

                            <td style="display: flex; justify-content:center;">
                                <?php if($transacao->status_trans == 'fechado'): ?>
                                <!-- INÍCIO DO TOOLTIP -->
                                <div class="tooltip"> 
                                    <i class="fa fa-check-circle" aria-hidden="true" style="font-size: 25px; color:#5cb353;"></i>     
                                    <span class="tooltiptext">
                                        Efetuada
                                    </span>
                                    </div>
                                    <!-- FINAL DO TOOLTIP  -->
                                <?php else: ?>
                                <!-- INÍCIO DO TOOLTIP -->
                                <div class="tooltip"> 
                                    <i class="fa fa-exclamation-circle" aria-hidden="true" style="font-size: 25px; color: #f70039;"></i>
                                    <span class="tooltiptext">
                                        Pendente
                                    </span>
                                    </div>
                                    <!-- FINAL DO TOOLTIP  -->
                                <?php endif; ?>
                            </td>


                            <?php if($transacao->tipo == 'receita'): ?>
                                <td style="color: green; white-space: nowrap;">R$ <?= formatoMoeda($transacao->valor) ?></td>
                            <?php elseif($transacao->tipo == 'transferencia'): ?>
                                <td style="color: blue; white-space: nowrap;">R$ <?= formatoMoeda($transacao->valor) ?></td>
                            <?php else: ?>
                                <td style="color: red; white-space: nowrap;">R$ <?= formatoMoeda($transacao->valor) ?></td>
                            <?php endif; ?>



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
                <b>Opa! Você ainda não possui transaão este mês.</b>
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


<script>
    new Chart(document.getElementById("graficoBarra"), {
    type: 'bar',
    data: {
      labels:['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
      datasets: [
        {
          label: "Despesa",
          backgroundColor: "#ff3232",
          data: [<?= implode(',',array_values($depMensal)) ?>]
        }, {
          label: "Receita",
          backgroundColor: '#0BB783',
          data: [<?= implode(',',array_values($recMensal)) ?>]
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