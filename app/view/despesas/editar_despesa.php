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
    <h3 class="title-form">Editar despesa</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">

    <form action="<?= HOME_URL ?>/despesa/editar?id=<?= $_GET['id'] ?>" method="POST">


        <label for="despesaPaga" class="checkbox-container">
            <input type="checkbox" name="despesaPaga" value="1" class="checkboxForm" <?= $dadosDespesa[0]->status_trans == 'fechado' ? 'checked' : '' ?>>
            <span class="checkmark">Pago</span> 
        </label>


        
        <label for="valor">Valor (R$):</label>
        <input type="text" class="money" name="valor" value="<?= $dadosDespesa[0]->valor ?? '' ?>">
        
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" value="<?= $dadosDespesa[0]->descricao ?? '' ?>">
        
        <label for="dataDespesa">Data:</label>
        <input type="date" name="dataDespesa" value="<?= $dadosDespesa[0]->data_trans ?? '' ?>">
        
        <label for="categoriaDespesa">Categoria:</label>
        <select name="categoriaDespesa">
            <?php if(count($listaCategorias) > 0): ?>
                <?php foreach($listaCategorias as $categoria): ?>
                   
                    <?php if($categoria->idCategoria == $dadosDespesa[0]->id_categoria ): ?>

                    <option value="<?= $categoria->idCategoria ?>" selected><?= $categoria->nome ?></option>

                    <?php else: ?>

                    <option value="<?= $categoria->idCategoria ?>"><?= $categoria->nome ?></option>

                    <?php endif ?>

                <?php endforeach; ?>
            <?php endif; ?>
        </select>  
        
        <label for="ContaDespesa">Conta:</label>
        <select name="ContaDespesa">
            <?php if(count($listaContas) > 0): ?>
                <?php foreach($listaContas as $conta): ?>

                    <?php if($conta->idConta == $dadosDespesa[0]->id_conta ): ?>

                    <option value="<?= $conta->idConta ?>" selected><?= $conta->descricao ?></option>

                    <?php else: ?>

                    <option value="<?= $conta->idConta ?>"><?= $conta->descricao ?></option>

                    <?php endif ?>

                <?php endforeach; ?>
            <?php endif; ?>
        </select>  

        <button class="btn-success">Alterar</button>
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