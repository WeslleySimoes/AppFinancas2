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
    <h3 class="title-form">Cadastro de transferência</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">

    <form action="<?= HOME_URL ?>/transferencia/editar?id=<?= $_GET['id'] ?>" method="POST">    

        <label for="transCheck" class="checkbox-container">
            <input type="checkbox" name="transCheck" value="1" class="checkboxForm"  <?= $dadosTransferencia[0]->getTransferencia()->status_transf == 'fechado' ? 'checked' : '' ?>>

            <span class="checkmark">Transferido</span> 
        </label>
        
        <label for="valor">Valor (R$):</label>
        <input type="text" class="money" name="valor" value="<?= $dadosTransferencia[0]->getTransferencia()->valor ?>">
        
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" value="<?= $dadosTransferencia[0]->getTransferencia()->descricao ?>">
        
        <label for="dataTransferencia">Data:</label>
        <input type="date" name="dataTransferencia" value="<?= explode(' ',$dadosTransferencia[0]->getTransferencia()->data_transf)[0] ?>">
        
        <label for="contaOrigem">Conta de origem:</label>

        <select name="contaOrigem" id="contaOrigem">
            <?php if(count($listaContas) > 0): ?>
                <?php foreach($listaContas as $conta): ?>

                    <?php if($conta->idConta == $dadosTransferencia[0]->getTransferencia()->id_conta_origem): ?>
                        <option value="<?= $conta->idConta ?>" selected><?= $conta->descricao ?></option>
                    <?php else: ?>
                        <option value="<?= $conta->idConta ?>"><?= $conta->descricao ?></option>
                    <?php endif; ?>


                <?php endforeach; ?>
            <?php endif; ?>
        </select>  
        
        <label for="contaDestino">Conta de destino:</label>
        <select name="contaDestino" id="contaDestino">
             <?php if(count($listaContas) > 0): ?>
                <?php foreach($listaContas as $conta): ?>

                    <?php if($conta->idConta == $dadosTransferencia[0]->getTransferencia()->id_conta_destino): ?>
                        <option value="<?= $conta->idConta ?>" selected><?= $conta->descricao ?></option>
                    <?php else: ?>
                        <option value="<?= $conta->idConta ?>"><?= $conta->descricao ?></option>
                    <?php endif; ?>

                <?php endforeach; ?>
            <?php endif; ?>
        </select>  
        <button class="btn-success">Alterar</button>
    </form>
    <?= $msg ?>
</div>
