<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    *{
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    #fundoFiltroTrans{
        position:fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background-color: rgba(1, 1, 1, 0.5);
        z-index: 30000;
        display: flex;
        align-items: center;
        justify-content: center;
        display: none;  
    }

    #form-container{
        width: 600px;
        /* height: 300px; */
        background-color: white;
        padding: 10px;
    }

    #btn-fechar-filtro-trans{
        cursor: context-menu;
        border: 1px solid grey;
        padding: 10px;
        border-radius: 2px;
    }
    #btn-fechar-filtro-trans:hover
    {
        background-color:#E5E5E5;
    }

    #form-filtro-trans label
    {
        font-weight: bold;
    }

    #form-filtro-trans select, input[type='date']{
        width: 100%;
        padding: 10px;
        font-size: 1.02rem;
    }
</style>
<body>
    <button id="btn-filtro-trans">Clique aqui</button>

    <div id="fundoFiltroTrans">
        <div id="form-container">
            <h1 style="margin-bottom: 10px; text-align: center;">Filtro</h1>
            <form action="filtro_trans.php" method="GET" id="form-filtro-trans">
                <div>
                    <label for="status">Status:</label><br>
                        <select name="status" >
                        <option value="">Todas</option>
                        <option value="fechado">Efetivado</option>
                        <option value="pendente">Pendente</option>
                    </select>
                </div><br>
                <div>
                    <label for="tipo">Tipo:</label><br>
                        <select name="tipo" >
                        <option value="">Todas</option>
                        <option value="receita">Receita</option>
                        <option value="despesa">Despesa</option>
                        <option value="transferencia">Transferência</option>
                    </select>
                </div><br>
                <div>
                    <label for="categoria">Categoria:</label><br>
                        <select name="categoria" >  
                        <option value="">Todas</option>
                        <?php for($i = 0; $i < 10 ; $i++): ?>
                            <option value="categoria<?=$i?>">Categoria <?=$i?></option>
                        <?php endfor ?>

                    </select>
                </div><br>
                <div>
                    <label for="conta">Conta:</label><br>
                        <select name="conta" >  
                        <option value="">Todas</option>
                        <?php for($i = 0; $i < 10 ; $i++): ?>
                            <option value="conta<?=$i?>">Conta <?=$i?></option>
                        <?php endfor ?>

                    </select>
                </div>
                <br>                 
                <div>
                    <label for="data">Data:</label><br>
                    <input type="date" name="data">
                </div>
                <br>

                <button type="submit" style="padding: 10px;">Aplicar filtro</button>
                <button type="reset" style="padding: 10px;">Limpar</button>
                <span id="btn-fechar-filtro-trans">Fechar</span>
            </form>
        </div>
    </div>

    <script>
        const btnFiltroTrans = document.querySelector("#btn-filtro-trans");
        const fundoFiltroTrans = document.querySelector("#fundoFiltroTrans");
        const btnFecharFiltroTrans = document.querySelector("#btn-fechar-filtro-trans");

        btnFiltroTrans.onclick = () =>{
            fundoFiltroTrans.style.display = 'flex';
            
        }

        btnFecharFiltroTrans.onclick = () => {
            fundoFiltroTrans.style.display = 'none';
        }
    </script>
</body>
</html>