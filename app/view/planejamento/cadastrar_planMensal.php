<div class="container-form">
    <h3 class="title-form">Cadastro de planejamento Mensal</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <div>

    <form action="<?= HOME_URL ?>/planejamento/cadastrarPM<?= isset($_GET['data']) ? '? data='.$_GET['data'] : '' ?>" method="POST">
            
            <label for="">Renda mensal (R$):</label>
            <input type="text" name="renda" id="valorRenda"  style="width: 100%;" class="money" value="0,00" >

            <label for="">Meta de gasto (%):</label>
            <input type="number" name="porcentMeta" id="porcentMeta" min="10" max="80"  value="10" style="width: 80px;"/>

            <input type="text" name="metaGasto"  id="metaGasto" style="border: none; color: red; width: auto;" value="R$ 0,00" disabled >

            <label for="" style="display: block;">Insira uma meta para cada categoria:</label>
            <div id="check-categorias">
                <?php $i = 0; foreach($categoriasDesp as $cd): ?>

                    <div style="padding: 10px 0;">

                        <input type="checkbox" name="categoria[<?= $i?>]"  value="<?= $cd->idCategoria ?>" onclick="if (this.checked){ document.getElementById('campoCate<?= $i ?>').removeAttribute('disabled'); somaTotalCategoria();}else{document.getElementById('campoCate<?= $i ?>').setAttribute('disabled', 'disabled'); somaTotalCategoria();}">

                        <label for=""> <?= $cd->nome ?>: R$ </label>

                        <input type="text" id="campoCate<?= $i ?>" name="item[<?= $i ?>]"  style="min-width: 72%;" value="0.00" class="formValorCate money" disabled required >
                    </div>

                <?php $i++; endforeach; ?>
            </div>
            <div style="margin-bottom: 10px;" id="totalCategorias"><b style="color:red;">Valor restante: R$ 0,00</b></div>
            <button type="submit" id="btnCadastrarPlan" class="botao-link botao-link-edit2" disabled style="font-size: 1.02em">Cadastrar</button>
        </form>
    </div>
    <?= $msg ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.date').mask('00/00/0000');
        $('.time').mask('00:00:00');
        $('.cep').mask('00000-000');
        $('.phone').mask('(00) 0000-0000');
        $('.celular').mask('(00) 00000-0000');
        $('.cpf').mask('000.000.000-00');
        $('.money').mask('000.000.000.000.000,00', {reverse: true});
    });
</script>
<script src="<?=ASSET_JS_URL ?>/script_plan_mensal.js"></script>