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
    min-width: 300px;
    height: 200px;
    border: 1px solid lightgrey;
    border-radius: 25px;
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
</style>

<div class="tab" style="margin-top: 10px;">
  <button class="tablinks" onclick="openCity(event, 'London')" id="defaultOpen">Ativos</button>
  <button class="tablinks" onclick="openCity(event, 'Paris')">Expirados</button>
</div>

<div id="London" class="tabcontent">
    <div id="inserir-plan-person">
        <div>
            <a href="<?= HOME_URL ?>/planejamento/cadastrarPP"> <span style="display: block; text-align: center; font-size: 30px;"><i class="fa fa-plus" aria-hidden="true"></i></span>Novo planejamento</a>
        </div>
            
    </div>

    <div id="inserir-plan-person">
        dfsdf
    </div>
    <div id="inserir-plan-person">
        dfsdf
    </div>
    <div id="inserir-plan-person">
        dfsdf
    </div>
    <div id="inserir-plan-person">
        dfsdf
    </div>
    <div id="inserir-plan-person">
        dfsdf
    </div>
</div>

<div id="Paris" class="tabcontent">
    <div id="inserir-plan-person">
        dfsdf
    </div>
    <div id="inserir-plan-person">
        dfsdf
    </div>
    <div id="inserir-plan-person">
        dfsdf
    </div>

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