<style>
    label
    {
        display: block;
    }

    input[type='date']
    {
        display: block;
        width: 100%;
        padding: 10px;
        font-size: 16px;
        margin: 5px 0 20px 0;
    }
    .checkbox-container{
        position: relative;
        display: block;
        margin-bottom: 10px;
    }
    .checkmark{
        position: relative;
        top: -8px;
        left: 5px;
    }
    .checkboxForm
    {
        width: 18px;
        height: 28px;
    }

    #totalParcelas{
        display: none;
    }
</style>

<div class="container-form">
    <h3 class="title-form"><?= $titulo ?></h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">

    <form action="<?= HOME_URL ?>/receita/editarFP?id=<?= $_GET['id'] ?>&t=<?= $_GET['t'] ?>" method="POST">

        <label for="receitaRecebida" class="checkbox-container">
            <input type="checkbox" name="receitaRecebida" value="1" class="checkboxForm" <?= $dadosEdicao[0]->status_rec == 'fechado' ? 'checked' : '' ?>>
            <span class="checkmark">Finalizar</span> 
        </label>

        <label for="valor">Valor (R$):</label>
        <input type="text" class="money" name="valor" value="<?= $dadosEdicao[0]->valor ?>">
        
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" value="<?= $dadosEdicao[0]->descricao ?>">      
        
        <label for="categoriaReceita">Categoria:</label>
        <select name="categoriaReceita">
            <?php if(count($listaCategorias) > 0): ?>
                <?php foreach($listaCategorias as $categoria): ?>

                    <?php if($categoria->idCategoria == $dadosEdicao[0]->id_categoria ): ?>

                    <option value="<?= $categoria->idCategoria ?>" selected><?= $categoria->nome ?></option>

                    <?php else: ?>

                    <option value="<?= $categoria->idCategoria ?>"><?= $categoria->nome ?></option>

                    <?php endif ?>

                <?php endforeach; ?>
            <?php endif; ?>
        </select>  
        
        <label for="ContaReceita">Conta:</label>
        <select name="ContaReceita">
             <?php if(count($listaContas) > 0): ?>
                <?php foreach($listaContas as $conta): ?>

                    <?php if($conta->idConta == $dadosEdicao[0]->id_conta ): ?>

                    <option value="<?= $conta->idConta ?>" selected><?= $conta->descricao ?></option>

                    <?php else: ?>

                    <option value="<?= $conta->idConta ?>"><?= $conta->descricao ?></option>

                    <?php endif ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>  
        <button  class="botao-link botao-link-edit" style="font-size: 1.1em;">Editar</button>
    </form>
    <?= $msg ?>
</div>
