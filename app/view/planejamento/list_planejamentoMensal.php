<!-- INÍCIO - TITULO DA PÁGINA -->
<h3 style="margin-bottom: 20px; color: #263D52;">
    <?php if(isset($_GET['p']) == 'personalizado'): ?>
        Planejamento Personalizado
    <?php else:  ?>
        Planejamento Mensal
    <?php endif; ?>
</h3>
<!-- FIM - TITULO DA PÁGINA -->

<!-- INÍCIO - LINKS DA PÁGINA -->
<div class="dropdown">
    <button onclick="myFunction()" class="dropbtn"> <span style='font-size:16px;'>&#9660;</span><?php if(isset($_GET['p']) == 'personalizado'): ?>
        Planejamento Personalizado
    <?php else:  ?>
        Planejamento Mensal
    <?php endif; ?></button>
    <div id="myDropdown" class="dropdown-content">
    <a href="<?= HOME_URL ?>/planejamento">Planejamento mensal</a>
    <a href="<?= HOME_URL ?>/planejamento?p=personalizado">Planejamento personalizado</a>
    </div>
</div>
<!-- FIM - LINKS DA PÁGINA -->

<!-- INÍCIO - MOSTRA OS PLANEJAMENTOS PERSONALIZADOS -->
<?php if(isset($_GET['p']) == 'personalizado'): ?>

    <input id="bday-month" type="month" name="bday-month" value="<?= date('Y-m') ?>">
    
    <p style="margin: 20px 0;"><b>Personalizado</b></p>

<!-- FIM - MOSTRA OS PLANEJAMENTOS PERSONALIZADOS  -->
<!-- --------------------------------------------- -->
<!-- INÍCIO - MOSTRA O PLANEJAMENTO DO MÊS ATUAL   -->
<?php else:  ?>
    <?php if(count($total_plan_mensal) > 0): ?>
    
    <table style="margin-top: 20px; width: 100%;">
        <tr>
            <th>Categoria</th>
            <th>Meta</th>
            <th>Valor Gasto</th>
            <th>Resultado</th>
            <th>Progresso</th>
            <th>Ações</th>
        </tr>
        <tr>
            <td><b>Total</b></td>
            <td>R$ <?= formatoMoeda($total_plan_mensal[0]->calcularMetaGasto()) ?></td>
            <td>R$ <?= formatoMoeda($total_plan_mensal[0]->getTotalGasto()) ?></td>
            <td>R$ <?= formatoMoeda($total_plan_mensal[0]->resultado()) ?></td>
            <td><progress id="file" value="<?= $total_plan_mensal[0]->getPorcentagemGasto() ?>" max="100" style="accent-color:blue;"></progress> <?= $total_plan_mensal[0]->getPorcentagemGasto(false) ?>%</td>

            <td>
                <div class="tooltip"> 
                    <a href="<?= HOME_URL."/planejamento/editarPM?id={$total_plan_mensal[0]->idPlan}"?>" style="color:blue;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                    <span class="tooltiptext">
                        Editar
                    </span>
                </div>
                |
                <div class="tooltip"> 
                    <a href="<?= HOME_URL."/planejamento/removerPM?id={$total_plan_mensal[0]->idPlan}"?>" style="color:red;"><i class="fa fa-trash-o" aria-hidden="true" onclick="return confirm('Tem certeza que deseja Remover?');"></i></a>

                    <span class="tooltiptext">
                        Excluir
                    </span>
                </div>
            </td>
        </tr>
        <?php foreach( $total_plan_mensal[0]->getPlanCategorias() as $planCate): ?>

            <?php $planCate->getCategoria(); ?>

            <tr>
                <td> <span style='font-size:20px;'>&#10148;</span> <?= $planCate->categoria_obj->nome ?></td>
                <td>R$ <?= formatoMoeda($planCate->valorMeta) ?></td>
                <td>R$ <?= formatoMoeda($planCate->categoria_obj->TotalGastoMesAtual())?></td>
                <td>R$ <?= formatoMoeda($planCate->resultado()) ?></td>

                <td colspan="2"><progress id="file" value="<?=  $planCate->getPorcentagemGasto() ?? 100   ?>" max="100"></progress><?= $planCate->getPorcentagemGasto(false);?>%</td>
                
            </tr>
            <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p>Nenhum planejamento encontrado!</p>
        <a href="<?= HOME_URL ?>/planejamento/cadastrarPM">Criar planejamento</a>
    <?php endif; ?>

<?php endif; ?>
<!-- INÍCIO - MOSTRA O PLANEJAMENTO DO MÊS ATUAL  -->

<?= $msg ?>

<!-- INÍCIO - SCRIPT JAVASCRIPT -->
<script>
    function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
    }
</script>
<!-- FIM - SCRIPT JAVASCRIPT -->