<h3 style="margin-bottom: 20px; color: #263D52;">
    Dashboard
    <br>
    <span style="font-size: 15px; color:#2e465c; ">
        <?= getSDMA() ?>
    </span>
</h3>

<div class="dadosFinanceiros">
    <div class="dadoItem dadoItem-1">
        <i class="fa fa-area-chart" aria-hidden="true"></i>
        Receitas: <br/> R$ 1.500,00 
        <a href="<?= HOME_URL ?>/transacoes?tipo=receita">ver mais</a></div>

    <div class="dadoItem dadoItem-2">
        <i class="fa fa-shopping-basket" aria-hidden="true"></i> 
        Despesas:<br/> R$ 1.500,00
        <a href="<?= HOME_URL ?>/transacoes?tipo=despesa">ver mais</a>
    </div>

    <div class="dadoItem dadoItem-3">
        <i class="fa fa-university" aria-hidden="true"></i>
        Saldo em Contas:<br/> R$ 1.500,00
        <a href="<?= HOME_URL ?>/contas/listar">ver mais</a>
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
    <h3>Receitas X Despesas dos últimos 12 meses</h3>
    <div class="containerForm dsd">
        <canvas id="myChart" width="400" height="125"></canvas>
    </div>
</div>

<div class="outrosDados">
    <div class="formularioUsuario">
        <h3>Últimas 10 transações</h3>
        <div class="containerForm">
            Gráficos das receitas e despesas dos últimos 12 meses.
        </div>
    </div>
    <div class="formularioUsuario">
        <h3>Despesas por categoria</h3>
        <div class="containerForm">
            Lorem ipsum dolor sit amet.
        </div>
    </div>
</div>

<!-- SCRIPT CHART.JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
<script>
    const ctx = document.getElementById('myChart');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
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
<!-- FIM DO SCRIPT CHART.JS -->