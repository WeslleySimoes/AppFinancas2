<h3 style="margin-bottom: 20px; color: #263D52;">Detalhe de planejamento personalizado</h3>


<?php if(isset($detalhePP)): ?>
    <div style="border: 1px solid lightgrey; padding: 10px;">
        <p>
            <b style="font-size: 1.1em;">Descrição:</b>    <?= $detalhePP->descricao ?><br>
            <b style="font-size: 1.1em;">Data inicial:</b> <?= formataDataBR($detalhePP->data_inicio) ?><br>
            <b style="font-size: 1.1em;">Data final:</b>   <?= formataDataBR($detalhePP->data_fim) ?>
        </p>
    </div>
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
            <td>R$ <?= formatoMoeda($detalhePP->valor) ?></td>
            <td>R$ <?= formatoMoeda($detalhePP->getTotalGasto(" DATE('{$detalhePP->data_inicio}') AND DATE('{$detalhePP->data_fim}')")) ?></td>
            <td>R$ <?= formatoMoeda($detalhePP->resultado('PESONALIZADO')) ?></td>
            <td ><progress id="file" value="<?= $detalhePP->getPorcentagemGasto(true,'PERSONALIZADO') ?>" max="100" style="width: 250px;"></progress><?= $detalhePP->getPorcentagemGasto(false,'PERSONALIZADO') ?>%</td>
            <td>
                <div class="tooltip"> 
                    <a href="<?= HOME_URL."/planejamento/editarPP?id={$detalhePP->idPlan}"?>" style="color:blue;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                    <span class="tooltiptext">
                        Editar
                    </span>
                </div>
                |
                <div class="tooltip"> 
                    <a href="<?= HOME_URL."/planejamento/removerPP?id={$detalhePP->idPlan}"?>" style="color:red;"><i class="fa fa-trash-o" aria-hidden="true" onclick="return confirm('Tem certeza que deseja Remover?');"></i></a>
                    
                    <span class="tooltiptext">
                        Excluir
                    </span>
                </div>
            </td>

        </tr>
        <?php foreach($detalhePP->getPlanCategorias() as $planCate): ?>

            <?php $planCate->getCategoria(); ?>

            <tr>
                <td> <span style='font-size:20px;'>&#10148;</span> <?= $planCate->categoria_obj->nome ?></td>
                <td>R$ <?= formatoMoeda($planCate->valorMeta) ?></td>
                <td>R$ <?= formatoMoeda($planCate->categoria_obj->TotalGastoPeriodo(" DATE('{$detalhePP->data_inicio}') AND DATE('{$detalhePP->data_fim}')"))?></td>
                <td>R$ <?= formatoMoeda($planCate->resultado()) ?></td>

                <td colspan="2"><progress id="file" value="<?=  $planCate->getPorcentagemGasto() ?? 100   ?>" max="100" style="width: 250px;"></progress><?= $planCate->getPorcentagemGasto(false);?>%</td>
                
            </tr>
            <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Nenhum planejamento encontrado!</p>
    <a href="<?= HOME_URL ?>/planejamento/cadastrarPM">Criar planejamento</a>
<?php endif; ?>

<!-- INÍCIO - MOSTRA O PLANEJAMENTO DO MÊS ATUAL  -->