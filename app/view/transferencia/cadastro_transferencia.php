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

    <form action="<?= HOME_URL ?>/transferencia/cadastrar" method="POST">

        <label for="transCheck" class="checkbox-container">
            <input type="checkbox" name="transCheck" value="1" class="checkboxForm">
            <span class="checkmark">Transferido</span> 
        </label>
        
        <label for="valor">Valor (R$):</label>
        <input type="text" class="money" name="valor">
        
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao">
        
        <label for="dataTransferencia">Data:</label>
        <input type="date" name="dataTransferencia" value="<?= date("Y-m-d") ?>">
        
        <label for="contaOrigem">Conta de origem:</label>

        <select name="contaOrigem" id="contaOrigem">
            <?php if(count($listaContas) > 0): ?>
                <?php foreach($listaContas as $conta): ?>
                    <option value="<?= $conta->idConta ?>"><?= $conta->descricao ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>  
        
        <label for="contaDestino">Conta de destino:</label>
        <select name="contaDestino" id="contaDestino">
             <?php if(count($listaContas) > 0): ?>
                <?php foreach($listaContas as $conta): ?>
                    <option value="<?= $conta->idConta ?>"><?= $conta->descricao ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>  
        <button class="btn-success">Cadastrar</button>
    </form>
    <?= $msg ?>
</div>
