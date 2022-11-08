<?php 
$corGraficoLinha = '';

if(isset($_POST['selecione']))
{
    if($_POST['selecione'] == 'despesas')
    {
        $corGraficoLinha = "rgb(255,0,0)";
    }
    else{
        $corGraficoLinha = "rgb(7, 152, 0)";
    }
}else{
    $corGraficoLinha = "rgb(255,0,0)";
}

?>
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

.conteudo3{
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
</style>
<?= $msg.'</br>' ?>
<div class="titulo_rela">
<h3 style="margin-bottom: 20px; color: #263D52;">Relatórios</h3>

    <div id="links-grafico-relatorios">
        <a href="<?= HOME_URL ?>/relatorios"><i class="fa fa-pie-chart" aria-hidden="true" style="color: red;"></i></a>
        <a href="<?= HOME_URL ?>/relatorios/linha"><i class="fa fa-line-chart" aria-hidden="true" style="color: blue;"></i></a>
        <a href="<?= HOME_URL ?>/relatorios/barra"><i class="fa fa-bar-chart" aria-hidden="true " style="color: green;"></i></a>
    </div>
</div>
<div style="padding: 10px; margin-top: 10px;border: 1px solid lightgray; display: flex; justify-content:space-between; align-items: center;">
    <h3 style="color: #263D52;">
        <?php if(isset($_POST['selecione'])): ?>
            <?= ucfirst($_POST['selecione']) ?>
        <?php else: ?>
            Despesas
        <?php endif; ?>
    </h3>
    <h3>
        <?php if(isset($_POST['dataRadio'])): ?>
            <?php if($_POST['dataRadio'] == 'dataMesAno'): ?>
                 <?= formataDataBR($_POST['mesAno'],'m/Y') ?>
            <?php elseif($_POST['dataRadio'] == 'dataPeriodo'): ?>
                De <?= formataDataBR($_POST['dataInicio']) ?> Até <?= formataDataBR($_POST['dataFim']) ?>
            <?php elseif($_POST['dataRadio'] == 'dataAno'): ?>
                <?= $_POST['ano'] ?>
            <?php endif; ?>
        <?php else: ?>
                <?= date('m/Y') ?>
        <?php endif; ?>
    
    </h3>
    <button class="dropbtn" id="filtro-pizza" style="padding: 11px;"><i class="fa fa-filter" ></i></button>

</div>
<div class="conteudo3">
    <canvas id="myChart"></canvas>
    <div style="margin: 20px 0px;">

        <table style="width: 100%;">
            <tr style="text-align: left;">
                <th>Data</th>
                <th>Valor</th>
            </tr>
            <?php foreach($arr_dados as $data => $valor): ?>
                <tr>
                    <td><?= ucfirst($data) ?></td>
                    <td style="width: 50%;">R$ <?= formatoMoeda($valor) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr style="text-align: left;">
                <th>Total</th>
                <th>R$ <?= formatoMoeda(array_sum(array_values($arr_dados))) ?></th>
            </tr>
        </table>

    </div>
</div>

</div>

<div id="background-popUp-pizza" style="position: fixed; top:0; left: 0; width: 100%; min-height: 100vh;background-color: rgba(1,1,1,0.6); display:none; z-index: 3000;">
<div style="width: 600px; background-color: white; padding: 10px;" class="popup-relatorios-pag">

        <div style="display: flex; justify-content:flex-end;">
            <span id="fechar-popUp-pizza" class="btn-close-popup">X</span>
        </div>
        <h2 style="text-align: center; color: #263D52;">Filtro</h2>

    <form action="<?= HOME_URL ?>/relatorios/linha" method="POST" style="padding: 5px;">

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
            <label for="selecione" style="font-weight: bold;">Selecione:</label>
            <select name="selecione" style="width: 100%;padding: 10px 5px;">
                <option value="despesas">Despesas</option>
                <option value="receitas" >Receitas</option>
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
            <label for="categoria" style="font-weight: bold;">Categorias:</label>
            <select name="categoria" style="width: 100%;padding: 10px 5px;">
                    <optgroup label="Todas">
                        <option value="todas">Todas</option>
                    </optgroup>

                    <optgroup label="Receita">
                        <?php foreach($categorias_usuario as $categoria): ?> 
                            <?php if($categoria->tipo == 'receita'): ?>
                                <option value="<?= $categoria->idCategoria ?>"><?= $categoria->nome ?></option>     
                            <?php endif; ?>
                        <?php endforeach ?>
                    </optgroup>
                    <optgroup label="Despesa">
                        <?php foreach($categorias_usuario as $categoria): ?> 
                            <?php if($categoria->tipo == 'despesa'): ?>
                                <option value="<?= $categoria->idCategoria ?>"><?= $categoria->nome ?></option>     
                            <?php endif; ?>
                        <?php endforeach ?>
                </optgroup>
            </select>
        </div>

        <div style="margin: 10px 0;">
            <label for="conta" style="font-weight: bold;">Conta:</label>
            <select name="conta" style="width: 100%;padding: 10px 5px;">
                <option value="0">Todas</option>
                <?php foreach($contas_usuario as $contaU): ?>
                    <option value="<?= $contaU->idConta ?>"><?= $contaU->descricao ?></option>
                <?php endforeach ?>
            </select>
        </div>


        <button type="submit">Filtrar</butt on>
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
                labels:[<?= "'".implode("','",array_keys($arr_dados))."'" ?>],
                datasets: [{
                    //label: 'My First Dataset',
                    data: [<?= "'".implode("','",array_values($arr_dados))."'" ?>],
                    fill: false,
                    borderColor: "<?= $corGraficoLinha ?>",
                    tension: 0.1
                }]
            };

            const config = {
                type: 'line',
                data: data,
                //plugins: [ChartDataLabels],
                options:{
                    // oculta a label(legenda) do gráfico
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip:{
                            callbacks: {
                                label: (value,context) =>{
                                    //console.log(value);
                                    return 'R$ '+parseFloat(value.raw).toLocaleString('pt-br', {minimumFractionDigits: 2});
                                }
                            }
                        }
                    },
                    //locale: 'pt-BR',
                    scales:{
                        y:{
                            beginAtZero: true,
                            ticks:{
                                callback: (value,index,values) => {
                                    return 'R$ '+ value.toLocaleString('pt-br', {minimumFractionDigits: 2});
                                }
                            }
                        }
                    }
                }
            };

            const myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
    </script>
</body>
</html>