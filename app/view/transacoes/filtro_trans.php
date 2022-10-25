<!-- <button id="btn-filtro-trans">Clique aqui</button> -->
<style>

    #form-filtro-trans  select{
        margin: 5px 0 0 0 !important;
    }

    #form-filtro-trans button{
        background-color: #0476B9;
        border: 1px solid #0476B9;
        color: white;
        font-size: 1.02em;
    }

</style>
<div id="fundoFiltroTrans">
    <div id="form-container">
        <div style="display: flex; justify-content:flex-end;">
            <span id="btn-fechar-filtro-trans">X</span>
        </div>
        <h2 style="margin-bottom: 5px; text-align: center; color: #263D52;">Filtro</h2>
        <form action="<?= HOME_URL ?>/transacoes" method="GET" id="form-filtro-trans">
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

                    <optgroup label="Todas">
                        <option value="">Todas</option>
                    </optgroup>

                    <optgroup label="Receita">
                        <?php foreach($listaCategorias as $categoria): ?> 
                            <?php if($categoria->tipo == 'receita'): ?>
                                <option value="<?= $categoria->idCategoria ?>"><?= $categoria->nome ?></option>     
                            <?php endif; ?>
                        <?php endforeach ?>
                    </optgroup>
                    <optgroup label="Despesa">
                        <?php foreach($listaCategorias as $categoria): ?> 
                            <?php if($categoria->tipo == 'despesa'): ?>
                                <option value="<?= $categoria->idCategoria ?>"><?= $categoria->nome ?></option>     
                            <?php endif; ?>
                        <?php endforeach ?>
                    </optgroup>

                </select>
            </div><br>
            <div>
                <label for="conta">Conta:</label><br>
                    <select name="conta" >  
                    <option value="">Todas</option>
                    <?php foreach($listaContas as $conta): ?>
                        <option value="<?= $conta->idConta ?>"><?= $conta->descricao ?></option>
                    <?php endforeach ?>

                </select>
            </div>
            <br> 

            <div>
                <input type="checkbox"  id="dataEspecifica">
                <label for="data">Data:</label><br>
                <input type="date" name="data" style="border: 1px solid #ccc;" id="dataEspecificaInput" value="<?= date('Y-m-d') ?>" disabled>
            </div>
            <div style="margin-top: 10px;">
                <input type="checkbox"  id="dataMes" checked>
                <label for="dataMes">Mês:</label><br>
                <input type="month" name="dataMes" style="width: 100%; padding: 10px;border: 1px solid #ccc; font-size: 1.02rem;" value="<?= date('Y-m') ?>" id="dataMesInput">
            </div>
            <br>

            <button type="submit" style="padding: 10px;">Aplicar filtro</button>
            <!-- <button type="reset" style="padding: 10px;">Limpar</button> -->
            
        </form>
    </div>
</div>

<script>
    const dataEspecifica = document.querySelector('#dataEspecifica');
    const dataMes        = document.querySelector('#dataMes');
    const dataEspecificaInput = document.querySelector("#dataEspecificaInput");
    const dataMesInput   = document.querySelector('#dataMesInput');


    dataEspecifica.onclick = () =>{
        dataMes.checked = false;
        dataMesInput.disabled = true;
        dataEspecificaInput.disabled = false;
    }

    dataMes.onclick = () =>{
        dataEspecifica.checked = false;
        dataEspecificaInput.disabled = true;
        dataMesInput.disabled = false;
    }
</script>
