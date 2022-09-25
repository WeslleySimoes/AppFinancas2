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

    .titulo_rela{
        display: flex;
        justify-content:space-between;
        align-items: center;
        /* border: 1px solid black; */
    }

    .conteudo2{
        margin: 10px 0;
        padding: 10px;
        display: flex;
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

    select
    {
        margin-bottom: 0px !important;
    }

</style>
    <div class="titulo_rela">
        <h1 style="color: #263D52;">Relatórios</h1>

        <div id="links-grafico-relatorios">
            <a href="#pizza"><i class="fa fa-pie-chart" aria-hidden="true"></i></a>
            <a href="#linha"><i class="fa fa-line-chart" aria-hidden="true"></i></a>
            <a href="#barra"><i class="fa fa-bar-chart" aria-hidden="true"></i></a>
        </div>
    </div>
    <div style="padding: 10px; margin-top: 10px;border: 1px solid lightgray; display: flex; justify-content:space-between; align-items: center;">
        <h3>Despesas por categorias</h3>
        <h3>14/09/22 - 22/09/22</h3>
        <button style="font-size: 25px;" id="filtro-pizza"> <i class="fa fa-filter" aria-hidden="true"></i></button>
    </div>

    <div class="conteudo2">
        <div class="grafico" style="display: flex; align-items: center; justify-content:center;">
            <div style="position: absolute; font-size: 1.2rem;">Total: R$ 0,00</div>

            <canvas id="graficoPizza" width="479" height="479" style="display: block; box-sizing: border-box; height: 479px; width: 479px;"></canvas>
        </div>

        <div class="content-grafico">
            <div class="item-content-grafico">
                <span style="background-color: #FF6384;"></span>
                <div style="width: calc(100% - 50px); ">
                    <div style="display: flex; justify-content:space-between; ">
                        <div>Categoria</div>
                        <div>R$ 0,00</div>
                    </div>
                    <div style="display: flex; justify-content:space-between; ">
                        <div style="font-size: 0.99rem;">Porcentagem</div>
                        <div style="font-size: 0.99rem;">0%</div>
                    </div>
                </div>
            </div>
            <div class="item-content-grafico">
                <span style="background-color: #FFC234;"></span>
                <div style="width: calc(100% - 50px);">
                    <div style="display: flex; justify-content:space-between; ">
                        <div>Categoria</div>
                        <div>R$ 0,00</div>
                    </div>
                    <div style="display: flex; justify-content:space-between; ">
                        <div style="font-size: 0.99rem;">Porcentagem</div>
                        <div style="font-size: 0.99rem;">0%</div>
                    </div>
                </div>
            </div>
            <div class="item-content-grafico">
                <span style="background-color: #36A2EB;"></span>
                <div style="width: calc(100% - 50px);">
                    <div style="display: flex; justify-content:space-between; margin-bottom: 5px; ">
                        <div>Categoria</div>
                        <div>R$ 0,00</div>
                    </div>
                    <div style="display: flex; justify-content:space-between; ">
                        <div style="font-size: 0.99rem;">Porcentagem</div>
                        <div style="font-size: 0.99rem;">0%</div>
                    </div>
                </div>
            </div>
            <div class="item-content-grafico">
                <span style="background-color: #36A2EB;"></span>
                <div style="width: calc(100% - 50px);">
                    <div style="display: flex; justify-content:space-between; margin-bottom: 5px; ">
                        <div>Categoria</div>
                        <div>R$ 0,00</div>
                    </div>
                    <div style="display: flex; justify-content:space-between; ">
                        <div style="font-size: 0.99rem;">Porcentagem</div>
                        <div style="font-size: 0.99rem;">0%</div>
                    </div>
                </div>
            </div>
            <div class="item-content-grafico">
                <span style="background-color: #36A2EB;"></span>
                <div style="width: calc(100% - 50px);">
                    <div style="display: flex; justify-content:space-between; margin-bottom: 5px; ">
                        <div>Categoria</div>
                        <div>R$ 0,00</div>
                    </div>
                    <div style="display: flex; justify-content:space-between; ">
                        <div style="font-size: 0.99rem;">Porcentagem</div>
                        <div style="font-size: 0.99rem;">0%</div>
                    </div>
                </div>
            </div>
            <div class="item-content-grafico">
                <span style="background-color: #36A2EB;"></span>
                <div style="width: calc(100% - 50px);">
                    <div style="display: flex; justify-content:space-between; margin-bottom: 5px; ">
                        <div>Categoria</div>
                        <div>R$ 0,00</div>
                    </div>
                    <div style="display: flex; justify-content:space-between; ">
                        <div style="font-size: 0.99rem;">Porcentagem</div>
                        <div style="font-size: 0.99rem;">0%</div>
                    </div>
                </div>
            </div>
            <div class="item-content-grafico">
                <span style="background-color: #36A2EB;"></span>
                <div style="width: calc(100% - 50px);">
                    <div style="display: flex; justify-content:space-between; margin-bottom: 5px; ">
                        <div>Categoria</div>
                        <div>R$ 0,00</div>
                    </div>
                    <div style="display: flex; justify-content:space-between; ">
                        <div style="font-size: 0.99rem;">Porcentagem</div>
                        <div style="font-size: 0.99rem;">0%</div>
                    </div>
                </div>
            </div>
            <div class="item-content-grafico">
                <span style="background-color: #36A2EB;"></span>
                <div style="width: calc(100% - 50px);">
                    <div style="display: flex; justify-content:space-between; margin-bottom: 5px; ">
                        <div>Categoria</div>
                        <div>R$ 0,00</div>
                    </div>
                    <div style="display: flex; justify-content:space-between; ">
                        <div style="font-size: 0.99rem;">Porcentagem</div>
                        <div style="font-size: 0.99rem;">0%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="background-popUp-pizza" style="position: fixed; top:0; left: 0; width: 100%; min-height: 100vh;background-color: rgba(1,1,1,0.6); display:none; z-index: 3000;">
    <div style="width: 600px;  background-color: white; padding: 10px;">

        <div style="margin-bottom: 20px;">
            <button id="fechar-popUp-pizza" style="float: right; border: none; font-size: 23px;">X</button>
        </div>

        <form action="<?= HOME_URL ?>/relatorios" method="GET" style="padding: 10px;">

            <div>
                <input type="radio" id="dataMesAno" name="dataRadio" value="dataMesAno" checked>
                <label for="mesAno" style="font-weight: bold;">Mês:</label><br>
                <input type="month" name="mesAno" value="<?= date("Y-m") ?>" style="width: 100%;  padding: 10px 5px; font-size: 1.02rem;" id="mesAno"><br>
            </div>

            <div style="margin: 10px 0;">
                <input type="radio" id="dataPeriodo" name="dataRadio" value="dataPeriodo">
                <label for="Periodo" style="font-weight: bold;">Periodo:</label>
                <div style="display: flex; margin: 5px 0;">
                    <label for="dataIncio" style="display: flex; align-items: center;">De:</label>
                    <input type="date" name="dataInicio" value="<?= date("Y-m-d") ?>" style="width: 50%;margin: 0 10px 0 5px;" id="dataPeriodoInicio" disabled>
                    <label for="dataFim" style="display: flex; align-items: center;">Até:</label>
                    <input type="date" name="dataFim" value="<?= date("Y-m-d") ?>" style="width: 50%; margin-left: 5px;"  id="dataPeriodoFim" disabled>
                </div>
            </div>
            <hr>
            <div style="margin: 10px 0;">
                <label for="" style="font-weight: bold;">Selecione:</label>
                <select name="select" style="width: 100%;padding: 10px 5px;">
                    <option value="1">Despesa por categoria</option>
                    <option value="2" >Despesa por conta</option>
                    <option value="3">Receita por categoria</option>
                    <option value="4">Receita por conta</option>
                    <option value="5">Saldo por conta</option>
                </select>
            </div>
            <hr>
            <div style="margin: 10px 0;">
                <label for="" style="font-weight: bold;">Situação:</label>
                <select name="situacao" style="width: 100%;padding: 10px 5px;">
                    <option value="todas">Todas</option>
                    <option value="efetuadas">Efetuadas</option>
                    <option value="pendentes">Pendentes</option>
                </select>
            </div>
            <hr>
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

    //inputs
    const mesAno = document.querySelector("#mesAno");
    const dataPeriodoInicio = document.querySelector("#dataPeriodoInicio");
    const dataPeriodoFim    = document.querySelector("#dataPeriodoFim");


    dataPeriodo.onclick = () => 
    {
        if(dataPeriodo.checked)
        {
            mesAno.disabled = true;
            dataPeriodoInicio.disabled = false;
            dataPeriodoFim.disabled = false;
        }
    }

    dataMesAno.onclick = () => 
    {
        if(dataMesAno.checked)
        {
            mesAno.disabled = false;
            dataPeriodoInicio.disabled = true;
            dataPeriodoFim.disabled = true;
        }
    }  
</script>

<script>
    const data = {
        labels: [
            'Categoria1',
            'Categoria2',
            'Categoria3'
        ],
        datasets: [{
            label: 'My First Dataset',
            data: [300.21, 50, 100],
            backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
        }]
    };

    const config = {
        type: 'doughnut',
        data: data,
        options:{
            // oculta a label(legenda) do gráfico
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    };
    const myChart = new Chart(
        document.getElementById('graficoPizza'),
        config
    );
</script>
