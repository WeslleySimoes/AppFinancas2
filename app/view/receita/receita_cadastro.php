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
    <h3 class="title-form">Cadastro de despesa</h3>

    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <form action="<?= HOME_URL ?>/despesa/cadastrar" method="POST">
    
        <label for="despesaPaga" class="checkbox-container">
            <input type="checkbox" name="despesaPaga" value="1" class="checkboxForm">
            <span class="checkmark">Pago</span> 
        </label>

        
        <label for="valor">Valor (R$):</label>
        <input type="text" class="money" name="valor">
        
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao">
        
        <label for="dataDespesa">Data:</label>
        <input type="date" name="dataDespesa" value="<?= date("Y-m-d") ?>">
        
        <label for="categoriaDespesa">Categoria:</label>
        <select name="categoriaDespesa">
            <?php if(count($listaCategorias) > 0): ?>
                <?php foreach($listaCategorias as $categoria): ?>
                    <option value="<?= $categoria->idCategoria ?>"><?= $categoria->nome ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>  
        
        <label for="ContaDespesa">Conta:</label>
        <select name="ContaDespesa">
            <?php if(count($listaContas) > 0): ?>
                <?php foreach($listaContas as $conta): ?>
                    <option value="<?= $conta->idConta ?>"><?= $conta->descricao ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>  
        
        <label for="despesaFixa" class="checkbox-container">
            <input type="checkbox" id="despFixa" name="despesaFixa" value="1" class="checkboxForm">
            <span class="checkmark">Despesa Fixa</span> 
        </label>
        
        <label for="despesaPercelada" class="checkbox-container">
            <input type="checkbox" id="despParce" name="despesaPercelada" value="1" class="checkboxForm">
            <span class="checkmark">Parcelado</span> 
        </label>

        <div id="totalParcelas">
            <label for="totalParcelas">Total de Parcelas:</label>
            <input type="number" name="totalParcelas">
        </div><br>

        <button class="btn-success">Cadastrar</button>
    </form>
    <?= $msg ?>
</div>


<script>
    const despFixa = document.querySelector('#despFixa');
    const despParce = document.querySelector('#despParce');
    const totalParcelas = document.querySelector('#totalParcelas');


    despFixa.onclick = () => {
        despParce.checked = false; 

        displayTotalParcelas();
    }

    despParce.onclick = () => {
        despFixa.checked = false;

        displayTotalParcelas();
    }

    function displayTotalParcelas(){
        if(despParce.checked)
        {
            totalParcelas.style.display='block';
        }
        else{
            totalParcelas.style.display='none';
        }
    }
</script>