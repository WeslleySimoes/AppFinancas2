
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
            <h3>Despesas por categorias</h3>
            <h3>14/09/22 - 22/09/22</h3>
            <button style="font-size: 25px;" id="filtro-pizza"> <i class="fa fa-filter" aria-hidden="true"></i></button>
        </div>
        <div class="conteudo">
            <div class="grafico" style="display: flex; align-items: center; justify-content:center;">
                <div style="position: absolute; font-size: 1.2rem;">Total: R$ 0,00</div>
                <canvas id="myChart" ></canvas>
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

    <div id="background-popUp-pizza" style="position: fixed; top:0; left: 0; width: 100%; min-height: 100vh;background-color: rgba(1,1,1,0.6); display:none;">
        <div style="width: 600px; height: 500px; background-color: white; padding: 10px;">
            <button id="fechar-popUp-pizza">X</button>

            <form action="pag_relatorios.php" method="GET" style="padding: 10px;">
                <label for="mesAno">Mês:</label>
                <input type="month" name="mesAno" value="<?= date("Y-m") ?>"><br>
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

        
    </script>

        
    <script>
        const data = {
            labels: [
                'Red',
                'Blue',
                'Yellow'
            ],
            datasets: [{
                label: 'My First Dataset',
                data: [300, 50, 100],
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
                document.getElementById('myChart'),
                config
            );
    </script>
</body>
</html>