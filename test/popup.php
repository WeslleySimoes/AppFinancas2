<style>
    *{padding: 0; margin: 0; box-sizing: border-box;}

    #fundo-escuro-popup{
        position:fixed;
        z-index: 30000;
        width: 100%;
        height: 100vh;
        background-color: rgba(0, 0, 0,0.5);
        display: none;
    }

    .conteudo-pop{
        width: 100%;
        height: 100vh;
        display:flex;
        justify-content: center;
        align-items: center;
        
    }

    #conteudo{
        width: 600px;
        height: 300px;
        background-color: white;
        padding: 20px;
    }
</style>

<div id="fundo-escuro-popup" class="conteudo-pop"> 
    <div class="conteudo-pop">
        <div id="conteudo">
            <h1 style="margin-bottom: 10px;">Deseja mesmo remover esta receita?</h1>

            <form action="popup.php" method="GET">
                <p>Por favor selecione uma das opções abaixo:</p>
                <br>
                <input type="radio" id="html" name="fav_language" value="HTML">
                <label for="html">Remover somente receitas Futuras</label><br>
                <input type="radio" id="css" name="fav_language" value="CSS">
                <label for="css">Remover todas as receitas</label><br>
                <br>
                <button class="fechar-popup">Remover</button>
                <span class="fechar-popup">Cancelar</span>
            </form>
            
        </div>
    </div>
</div>

<button class="remove-RFP">Clique Aqui</button>


<script>    
    const removeRFP = document.querySelectorAll('.remove-RFP');
    const fecharPopup = document.querySelectorAll('.fechar-popup');
    const fundoEscuroPopup = document.querySelector('#fundo-escuro-popup');

    for (let index = 0; index < removeRFP.length; index++) {
        removeRFP[index].onclick = () =>
        {
            fundoEscuroPopup.style.display = 'block';
        }
    }

    for (let index = 0; index < fecharPopup.length; index++) {
        fecharPopup[index].onclick = () =>
        {
            fundoEscuroPopup.style.display = 'none';
        }
    }

    function jsfunction()
    {
        window.alert('Olá mundo!');
    }

</script>
