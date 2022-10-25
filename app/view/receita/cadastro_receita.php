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
    <h3 class="title-form">Cadastro de receita</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">

    <form action="<?= HOME_URL ?>/receita/cadastrar" method="POST">

        <label for="receitaRecebida" class="checkbox-container">
            <input type="checkbox" name="receitaRecebida" value="1" class="checkboxForm">
            <span class="checkmark">Recebido</span> 
        </label>

        
        <label for="valor">Valor (R$):</label>
        <input type="text" class="money" name="valor">
        
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao">
        
        <label for="dataReceita">Data:</label>
        <input type="date" name="dataReceita" value="<?= date("Y-m-d") ?>">
        
        <label for="categoriaReceita">Categoria:</label>
        <select name="categoriaReceita">
            <?php if(count($listaCategorias) > 0): ?>
                <?php foreach($listaCategorias as $categoria): ?>
                    <option value="<?= $categoria->idCategoria ?>"><?= $categoria->nome ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>  
        
        <label for="ContaReceita">Conta:</label>
        <select name="ContaReceita">
             <?php if(count($listaContas) > 0): ?>
                <?php foreach($listaContas as $conta): ?>
                    <option value="<?= $conta->idConta ?>"><?= $conta->descricao ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>  
        
        <label for="receitaFixa" class="checkbox-container">
            <input type="checkbox" id="despFixa" name="receitaFixa" value="1" class="checkboxForm">
            <span class="checkmark">Receita Fixa</span> 
        </label>
        
        <label for="receitaPercelada" class="checkbox-container">
            <input type="checkbox" id="despParce" name="receitaPercelada" value="1" class="checkboxForm">
            <span class="checkmark">Parcelado</span> 
        </label>

        <div id="totalParcelas">
            <label for="totalParcelas">Total de Parcelas:</label>
            <input type="number" name="totalParcelas">
        </div><br>

        <button  class="botao-link botao-link-edit" style="font-size: 1.1em;">Cadastrar</button>
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