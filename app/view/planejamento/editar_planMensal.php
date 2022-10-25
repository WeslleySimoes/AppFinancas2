<div class="container-form">
    <h3 class="title-form">Editar planejamento Mensal</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;">
    <div>

    <form action="<?= HOME_URL ?>/planejamento/editarPM?id=<?= $_GET['id'] ?>" method="POST">
            
            <label for="">Renda mensal (R$):</label>
            <input type="text" name="renda" id="valorRenda"  style="width: 100%;" class="money" value="<?= formatoMoeda($planejamento_atual->valor) ?>" >

            <label for="">Meta de gasto (%):</label>
            <input type="number" name="porcentMeta" id="porcentMeta" min="10" max="80"  value="<?= $planejamento_atual->porcentagem  ?>" style="width:80px;"/>

            <input type="text" name="metaGasto"  id="metaGasto" style="border: none; color: red; width: auto;" value="R$ <?= formatoMoeda($planejamento_atual->valor*($planejamento_atual->porcentagem/100)) ?>" disabled >

            <label for="" style="display: block;">Insira uma meta para cada categoria:</label>

            <div id="check-categorias">

                <div style="padding: 10px 0;">
                    
                    <?php $i = 0; foreach($categoriasDesp as $cd): ?>

                        <?php $verifica = false ?>

                        <?php foreach($planejamento_atual->getPlanCategorias() as $planCat): ?>
                            <?php $planCat->getCategoria() ?>
                            <?php if($planCat->categoria_obj->idCategoria === $cd->idCategoria): ?>

                                <input type="checkbox" name="categoria[<?= $i ?>]"  value="<?= $cd->idCategoria ?>" onclick="if (this.checked){ document.getElementById('campoCate<?= $i ?>').removeAttribute('disabled'); somaTotalCategoria();}else{document.getElementById('campoCate<?= $i ?>').setAttribute('disabled', 'disabled'); somaTotalCategoria();}" checked>

                                <label for=""> <?= $cd->nome ?>: R$ </label>

                                <input type="text" id="campoCate<?= $i ?>" name="item[<?= $i ?>]"  style="min-width: 72%;" value="<?= formatoMoeda($planCat->valorMeta) ?>" class="formValorCate money" required >


                            <?php $i++; $verifica = true; break; endif; ?>
                        <?php endforeach; ?>

                        <?php if(!$verifica): ?>
                            <input type="checkbox" name="categoria[<?= $i?>]"  value="<?= $cd->idCategoria ?>" onclick="if (this.checked){ document.getElementById('campoCate<?= $i ?>').removeAttribute('disabled'); somaTotalCategoria();}else{document.getElementById('campoCate<?= $i ?>').setAttribute('disabled', 'disabled'); somaTotalCategoria();}">

                            <label for=""> <?= $cd->nome ?>: R$ </label>

                            <input type="text" id="campoCate<?= $i ?>" name="item[<?= $i ?>]"  style="min-width: 72%;" value="0.00" class="formValorCate money" disabled required >
                        <?php $i++; endif;  ?>

                    <?php endforeach ?>
                </div>

            </div>
            <div style="margin-bottom: 10px;" id="totalCategorias"><b style="color:green;">Valor restante: R$ 0,00</b></div>
            <button type="submit" id="btnCadastrarPlan" class="botao-link botao-link-edit2"  style="font-size: 1.02em">Editar</button>
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