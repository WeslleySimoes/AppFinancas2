<!-- <button id="btn-filtro-trans">Clique aqui</button> -->

<div id="fundoFiltroTrans">
    <div id="form-container">
        <h1 style="margin-bottom: 10px; text-align: center;">Filtro</h1>
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
                    <option value="">Todas</option>
                    <?php foreach($listaCategorias as $categoria): ?>
                        <option value="<?= $categoria->idCategoria ?>"><?= $categoria->nome ?></option>
                    <?php endforeach ?>

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
