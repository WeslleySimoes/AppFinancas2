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
        Personalizado
    <?php else:  ?>
        Mensal
    <?php endif; ?></button>
    <div id="myDropdown" class="dropdown-content">
    <a href="<?= HOME_URL ?>/planejamento">Mensal</a>
    <a href="<?= HOME_URL ?>/planejamento?p=personalizado">Personalizado</a>
    </div>
</div>
<!-- FIM - LINKS DA PÁGINA -->

<!-- INÍCIO - MOSTRA OS PLANEJAMENTOS PERSONALIZADOS -->
<?php if(isset($_GET['p']) == 'personalizado'): ?>
    
    <style>
body {font-family: Arial;}

/* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
  
}

/* Style the tab content */
.tabcontent {   
  display: none;
  padding: 10px 0px;
  /*border: 1px solid #ccc;*/
  border-top: none;
}

#inserir-plan-person{
    min-width: 200px;
    height: 200px;
    border: 1px solid lightgrey;
    border-radius: 15px;
    display: flex;
    justify-content: center;
    align-items:center;
    margin: 10px;
    -webkit-box-shadow: -1px 10px 17px 0px rgba(59,59,59,0.38); 
    box-shadow: -1px 10px 17px 0px rgba(59,59,59,0.38);
}

#inserir-plan-person a{
    text-decoration: none;
    color: grey;
    font-size: 1.3rem;
}

/**sadasdasd */
.item-perso
{
    border-radius: 15px;
    min-width: 250px;
    padding: 10px;
    margin: 10px;
    border: 1px solid lightgrey;
    -webkit-box-shadow: -1px 10px 17px 0px rgba(59,59,59,0.38); 
    box-shadow: -1px 10px 17px 0px rgba(59,59,59,0.38);
}
.plan-perso-header{
    display: flex;
    justify-content: space-between;
}
.item-menu-perso{
    display: inline-block;
    /* border: 1px solid black; */
    padding: 15px 10px;
    text-decoration: none;
    background-color: lightgray;
    color: black;
}

.item-menu-perso:hover{
    background-color: #a6a6a6;
}
</style>

<div class="tab" style="margin-top: 10px;background-color: lightgray;">
    
  <a href="<?= HOME_URL ?>/planejamento?p=personalizado&status=ativo" class="item-menu-perso" <?=  !isset($_GET['status']) || (isset($_GET['status']) && $_GET['status'] == 'ativo') ? "style='background-color: #a6a6a6 !important;'" : ' ' ?>>Ativos</button>

  <a href="<?= HOME_URL ?>/planejamento?p=personalizado&status=expirado" class="item-menu-perso" <?= isset($_GET['status']) && $_GET['status'] == 'expirado'? "style='background-color: #a6a6a6 !important;'" : ' ' ?>>Expirados</a>
</div>

<div style="display: flex;">

    <?php if(isset($_GET['status']) && $_GET['status'] != 'expirado'): ?>
    <div id="inserir-plan-person">
        <div>
            <a href="<?= HOME_URL ?>/planejamento/cadastrarPP"> <span style="display: block; text-align: center; font-size: 30px;"><i class="fa fa-plus" aria-hidden="true"></i></span>Novo planejamento</a>
        </div>
    </div>
    <?php endif;  ?>

    <?php if(count($total_plan_semanal) > 0): ?>
        <?php foreach($total_plan_semanal as $planS): ?>

            <div class="item-perso">
            <div class="plan-perso-header">
                <p style="font-size: 1.2em;"><b><?= $planS->descricao ?></b></p>
                <div>
                <?php if(isset($_GET['status']) && $_GET['status'] != 'expirado'): ?>

                <div class="tooltip"> 
                        <a href="<?= HOME_URL ?>/planejamento/editarPP?id=<?= $planS->idPlan ?>" style="display:inline-block; margin-right: 5px;font-size:1.15em; color:blue;"><i class="fa fa-pencil" aria-hidden="true" ></i></a>
                    <span class="tooltiptext">
                    Editar
                    </span>
                </div>
                <?php endif; ?>
                
                <div class="tooltip"> 
                        <a href="<?= HOME_URL ?>/planejamento/removerPP?id=<?= $planS->idPlan ?>" style="display:inline-block; margin-right: 5px;font-size:1.15em; color:red;" onclick="return confirm('Tem certeza que deseja remover o planejamento?');"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    <span class="tooltiptext">
                    Remover
                    </span>
                </div>

                <div class="tooltip"> 
                    <a href="<?= HOME_URL ?>/planejamento/detalhePP?id=<?= $planS->idPlan ?>" style="color: grey;"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                    
                <span class="tooltiptext">
                    Detalhes
                </span>
                </div>

                </div>
            </div>
            <p style="font-size: 0.85em;color:grey;font-weight:bold;margin-top:10px;"><?= formataDataBR($planS->data_inicio) ?> até <?= formataDataBR($planS->data_fim) ?></p>
            <div style="margin-top: 15px;">
                <span style="display:inline-block; text-align: center;">R$ <?= formatoMoeda($planS->valor) ?> <br> Meta</span>
                <b style="display: inline-bloc;margin: 0 5px;">-</b>
                <span style="display:inline-block; text-align: center;color:red;">R$ <?= formatoMoeda($planS->getTotalGasto(" DATE('{$planS->data_inicio}') AND DATE('{$planS->data_fim}')")) ?> <br> Valor Gasto</span>
                <b style="display: inline-bloc;margin: 0 5px;">=</b>
                <span style="display:inline-block; text-align: center;color:green;">R$ <?= formatoMoeda($planS->resultado('PESONALIZADO')) ?> <br> Restam</span>
            </div>
            <div style="margin-top: 15px;">
                <progress id="file" value="<?= $planS->getPorcentagemGasto(true,'PERSONALIZADO') ?>" max="100" style="width: 100%;"></progress>
                <div style="text-align: right;"><?= $planS->getPorcentagemGasto(false,'PERSONALIZADO') ?>%</div>
            </div>

            <?php if(isset($_GET['status']) && $_GET['status'] == 'expirado'): ?>
                <p style="margin-top: 5px; color:red;"><b>Expirado!</b></p>
            <?php else: ?>
                <p style="margin-top: 5px;"><b>Expira em:</b> <?= diffDataDias($planS->data_fim)+1 ?> dia(s)</p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "flex";
  document.getElementById(cityName).style.flexWrap = "wrap";

  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>

<!-- FIM - MOSTRA OS PLANEJAMENTOS PERSONALIZADOS  -->
<!-- --------------------------------------------- -->
<!-- INÍCIO - MOSTRA O PLANEJAMENTO DO MÊS ATUAL   -->
<?php else:  ?>
    <div style="display: flex;justify-content: center;align-items: center; padding: 10px;">
        <form action="<?= HOME_URL ?>/planejamento" method="GET" id="form_data">
            <!-- <label for="" style="display:inline-block;margin-right: 10px;font-weight: bold;">Mês: </label> -->
            <input id="bday-month" name="data" type="month" name="bday-month" value="<?= $_GET['data'] ?? date('Y-m') ?>" onchange="handler();"  style="border: 2px solid #0476B9; padding: 10px 5px; font-size: 0.9em; border-radius: 10px; font-weight: bold; color: #0476B9;">
        </form>
    </div>
    <?php if(count($total_plan_mensal) > 0): ?>


    <div style="border: 1px solid lightgrey; padding: 10px;">
        <b style="font-size: 1.1em;">Renda mensal:</b> R$ <?= formatoMoeda($total_plan_mensal[0]->valor)  ?><br>
        <b style="font-size: 1.1em;">Porcentagem da meta de gasto:</b> <?= $total_plan_mensal[0]->porcentagem ?>% <br>
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
            <td><span style="width: 15px; height: 15px;display:inline-block;border-radius: 50%; margin-right: 5px; background-color:grey;"></span><b>Total</b></td>
            <td style="color:blue;">R$ <?= formatoMoeda($total_plan_mensal[0]->calcularMetaGasto()) ?></td>
            <td style="color:red;">R$ <?= formatoMoeda($total_plan_mensal[0]->getTotalGasto()) ?></td>
            <td style="color: <?= $total_plan_mensal[0]->resultado()>= 0 ? 'green' : 'red' ?>">R$ <?= formatoMoeda($total_plan_mensal[0]->resultado()) ?></td>
            <td><progress id="file" value="<?= $total_plan_mensal[0]->getPorcentagemGasto() ?>" max="100" <?= $total_plan_mensal[0]->getPorcentagemGasto() > 100 ? 'style="accent-color:red;"' : '' ?>></progress> <?= $total_plan_mensal[0]->getPorcentagemGasto(false) ?>%</td>

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
                <td> <!--<span style='font-size:20px;'>&#10148;</span>  -->
                <span style="width: 15px; height: 15px;display:inline-block;border-radius: 50%; margin-right: 5px; background-color:<?= $planCate->categoria_obj->cor_cate ?>;"></span>
                <?= $planCate->categoria_obj->nome ?></td> 
                <td style="color:blue;">R$ <?= formatoMoeda($planCate->valorMeta) ?></td>
                <td style="color:red;">R$ <?= formatoMoeda($planCate->categoria_obj->TotalGastoMesAtual())?></td>
                <td style="color: <?= $planCate->resultado() >= 0 ? 'green' : 'red' ?>">R$ <?= formatoMoeda($planCate->resultado()) ?></td>

                <td colspan="2"><progress id="file" value="<?=  $planCate->getPorcentagemGasto() ?? 100   ?>" max="100" <?= $planCate->getPorcentagemGasto() > 100 ? 'style="accent-color:red;"' : '' ?>></progress><?= $planCate->getPorcentagemGasto(false);?>%</td>
                
            </tr>
            <?php endforeach; ?>
    </table>
    <?php else: ?>
        
        <div style="border: 1px solid #b7b4b4; display: flex; justify-content: center; align-items: center; height: 500px; box-shadow: -1px 10px 17px 0px rgb(59 59 59 / 38%); border-radius: 20px;">
            <div style="text-align: center;">               
                <?php if(isset($_GET['data'])): ?>
                    <a href="<?= HOME_URL ?>/planejamento/cadastrarPM?data=<?= $_GET['data'] ?>" style="text-decoration:none; color: grey; font-size: 1.8em;"> <span style="display: block; text-align: center; font-size: 30px; color:grey;"><i class="fa fa-plus" aria-hidden="true"></i></span> Criar planejamento</a>
                <?php else: ?>
                    <a href="<?= HOME_URL ?>/planejamento/cadastrarPM" style="text-decoration:none; color: grey; font-size: 1.8em;"> <span style="display: block; text-align: center; font-size: 30px; color:grey; "><i class="fa fa-plus" aria-hidden="true"></i></span> Criar planejamento</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

<?php endif; ?>
<!-- INÍCIO - MOSTRA O PLANEJAMENTO DO MÊS ATUAL  -->

<?= $msg ?>

<!-- INÍCIO - SCRIPT JAVASCRIPT -->
<script>
    function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    const formData = document.querySelector('#form_data');

    function handler()
    {
        formData.submit();
    }
</script>
<!-- FIM - SCRIPT JAVASCRIPT -->