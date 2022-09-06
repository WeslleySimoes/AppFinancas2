<!-- POPUP FORM -->
<?php require_once VIEW_DIR.'/templates/popup.php' ?>
<?php require_once VIEW_DIR.'/transacoes/filtro_trans.php' ?>
<!-- FIM POPUP FORM -->

  <h3 style="margin-bottom: 20px; color: #263D52;">
    <?php 
    if(isset($_GET['s']) and $_GET['s'] == 'receitasFixas')
    {
      echo 'Receitas Fixas/Parceladas';
    }
    elseif(isset($_GET['s']) and $_GET['s'] == 'despesasFixas')
    {
      echo 'Despesas Fixas/Parceladas';
    }
    else{
      echo 'Transações';
    }
    
    ?>
  </h3>

<!-- <div class="container-form">
    <h3 class="title-form">Transações</h3>
    <hr style="margin-bottom: 20px; border: 0.5px solid #cecdcd;"> -->

  <div class="dropdown">
    <button onclick="myFunction()" class="dropbtn">+ Cadastrar</button>
    <div id="myDropdown" class="dropdown-content">
    <a href="<?= HOME_URL ?>/receita/cadastrar">Receita</a>
    <a href="<?= HOME_URL ?>/despesa/cadastrar">Despesa</a>
    <a href="<?= HOME_URL ?>/transferencia/cadastrar">Transferência</a>
  </div>
  

  <!-- Tabela não fica com a largura completa por causa desta linha de codigo abaixo -->
  <div class="dropdown">
    <button onclick="myFunction2()" class="dropbtn">Transações</button>
    <div id="myDropdown2" class="dropdown-content">
    <a href="<?= HOME_URL ?>/transacoes">Transações</a>
    <a href="<?= HOME_URL ?>/transacoes?s=receitasFixas">Receitas fixas e/ou parceladas</a>
    <a href="<?= HOME_URL ?>/transacoes?s=despesasFixas">Despesas fixas e/ou parceladas</a>
  </div>
  <!-- ############################################################################ -->

  <button id="btn-filtro-trans" class="dropbtn">Filtro</button>

</div>

<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}

/*PAGINAÇÃO */
.pagination {
  display: inline-block;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
}

.pagination a.active {
  background-color: #0476B9;
  color: white;
}

.pagination a:hover:not(.active) {background-color: #ddd;}

tr td:last-child {
    width: 1%;
    white-space: nowrap;
}
</style>

<!-- LISTAGEM DE RECEITAS,DESPESAS,TRANSFERÊNCIAS -->
<?php if(isset($transacoes_cliente) and count($transacoes_cliente) > 0): ?>
  <table style="margin-top: 20px; width: 100%;">
    <tr>
      <th>Data</th>
      <th>Status</th>
      <th>Valor</th>
      <th>Descrição</th>
      <th>Categoria</th>
      <th>Conta</th>
      <th>Tipo</th>
      <th>Ações</th>
    </tr>

    <!-- LISTAGEM DE TODAS AS TRANSAÇÕES DO MÊS ATUAL -->
    <?php foreach($transacoes_cliente as $tc):  ?>
      <tr>
        <td><?= formataDataBR($tc->data_trans) ?></td>
        <td><?= ucfirst($tc->status_trans) ?></td>

        <?php if($tc->tipo == 'receita'): ?>
          <td style="color: green; white-space: nowrap;">R$ <?= formatoMoeda($tc->valor) ?></td>
        <?php elseif($tc->tipo == 'transferencia'): ?>
          <td style="color: blue; white-space: nowrap;">R$ <?= formatoMoeda($tc->valor) ?></td>
        <?php else: ?>
          <td style="color: red; white-space: nowrap;">R$ <?= formatoMoeda($tc->valor) ?></td>
        <?php endif; ?>
        <td><?= ucfirst($tc->descricao) ?></td>
        

  
        <?php if($tc->tipo == 'transferencia'): ?>
          <td> #</td>
        <?php else: ?>
          <td><?= $tc->getNomeCategoria() ?></td>
        <?php endif ?>

        
        <?php if($tc->tipo == 'transferencia'): ?>
          <td style="white-space: nowrap;"><?= $tc->getTransferencia()->getNomeConta('origem').' > '.$tc->getTransferencia()->getNomeConta('destino') ?></td>
        <?php else: ?>
        <td><?= $tc->getDescricaoConta() ?></td>
        <?php endif; ?>



        <td><?= ucfirst($tc->tipo) ?></td>

        <?php if($tc->tipo == 'receita'): ?>
          <td> 


            <?php if($tc->status_trans == 'pendente'): ?>

               <!-- INÍCIO DO TOOLTIP -->
              <div class="tooltip"> 
                <a href="<?= HOME_URL ?>/receita/efetivar?id=<?= $tc->idTransacao ?>" style="color:green;"><i class="fa fa-check-circle-o" aria-hidden="true"></i></a> |
                <span class="tooltiptext">
                  Efetivar
                </span>
              </div>
              <!-- FINAL DO TOOLTIP  -->


            <?php endif ?>

            <!-- INÍCIO DO TOOLTIP -->
            <div class="tooltip"> <a href="<?= HOME_URL ?>/receita/editar?id=<?= $tc->idTransacao ?>" style="color:blue;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
              <span class="tooltiptext">
                Editar
              </span>
            </div>
            <!-- FINAL DO TOOLTIP  -->
            |
            <!-- INÍCIO DO TOOLTIP -->
            <div class="tooltip"> 
              <a href="<?= HOME_URL ?>/receita/remover?id=<?= $tc->idTransacao ?>" style="color:red;"><i class="fa fa-trash-o" aria-hidden="true" onclick="return confirm('Tem certeza que deseja Remover?');"></i></a>

              <span class="tooltiptext">
                Excluir
              </span>
            </div>
            <!-- FINAL DO TOOLTIP  -->

          </td>
          
        <?php elseif($tc->tipo == 'transferencia'): ?>
          <td> 

            <?php if($tc->status_trans == 'pendente'): ?>

               <!-- INÍCIO DO TOOLTIP -->
               <div class="tooltip"> 
                 <a href="<?= HOME_URL ?>/transferencia/efetivar?id=<?= $tc->idTransacao ?>" style="color:green;"><i class="fa fa-check-circle-o" aria-hidden="true"></i></a> 
                
                <span class="tooltiptext">
                  Efetivar
                </span>
              </div>
              <!-- FINAL DO TOOLTIP  -->
              |
            <?php endif ?>

            <!-- INÍCIO DO TOOLTIP -->
            <div class="tooltip"> 
              <a href="<?= HOME_URL ?>/transferencia/editar?id=<?= $tc->idTransacao ?>" style="color:blue;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                
              <span class="tooltiptext">
                Editar
              </span>
            </div>
            <!-- FINAL DO TOOLTIP  -->

            |

            <!-- INÍCIO DO TOOLTIP -->
            <div class="tooltip"> 
              
              <a href="<?= HOME_URL ?>/transferencia/remover?id=<?= $tc->idTransacao ?>" style="color:red;"><i class="fa fa-trash-o" aria-hidden="true" onclick="return confirm('Tem certeza que deseja Remover?');"></i></a>
                
              <span class="tooltiptext">
                Excluir
              </span>
            </div>
            <!-- FINAL DO TOOLTIP  -->

          </td>
        <?php else: ?>
          <td> 

            <?php if($tc->status_trans == 'pendente'): ?>
              <!-- INÍCIO DO TOOLTIP -->
              <div class="tooltip"> 
                <a href="<?= HOME_URL ?>/despesa/efetivar?id=<?= $tc->idTransacao ?>" style="color:green;"><i class="fa fa-check-circle-o" aria-hidden="true"></i></a> |
                   
                <span class="tooltiptext">
                  Efetivar
                </span>
              </div>
              <!-- FINAL DO TOOLTIP  -->
            <?php endif ?>

             <!-- INÍCIO DO TOOLTIP -->
             <div class="tooltip"> 
              <a href="<?= HOME_URL ?>/despesa/editar?id=<?= $tc->idTransacao ?>" style="color:blue;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                
              <span class="tooltiptext">
                Editar
              </span>
            </div>
            <!-- FINAL DO TOOLTIP  -->

            |
             <!-- INÍCIO DO TOOLTIP -->
             <div class="tooltip"> 

               <a href="<?= HOME_URL ?>/despesa/remover?id=<?= $tc->idTransacao ?>" style="color:red;"><i class="fa fa-trash-o" aria-hidden="true" onclick="return confirm('Tem certeza que deseja Remover?');"></i></a>
                
              <span class="tooltiptext">
                Excluir
              </span>
            </div>
            <!-- FINAL DO TOOLTIP  -->            
          </td>
        <?php endif; ?>


      </tr>
    <?php endforeach ?>



<!-- LISTAGEM DE RECEITAS E DESPESAS FIXAS OU PARCELADAS -->
  <?php elseif (isset($arr_dados)): ?>
    <table style="margin-top: 20px;">
      <tr>
        <th>Status</th>
        <th>Valor</th>
        <th>Descrição</th>
        <th>Categoria</th>
        <th>Data início</th>
        <th>Data final</th>
        <th>Tipo</th>
        <th>Ações</th>
      </tr>

      <?php foreach($arr_dados[1] as $dados):  ?>
      <tr>

        <!-- LISTAGEM DE RECEITAS FIXAS OU PARCELADAS  -->
        <?php if($arr_dados[0] == 'receitas'): ?>

        <td><?= ucfirst($dados->status_rec) ?></td>
        <td>R$ <?= formatoMoeda($dados->valor) ?></td>
        <td><?= ucfirst($dados->descricao) ?></td>
        <td><?= $dados->getNomeCategoria() ?></td>
        <td><?= formataDataBR($dados->data_inicio) ?></td>

        <?php if($dados->data_fim == '0000-00-00'): ?>
          <td>---------------</td>
        <?php else: ?>
          <td><?= formataDataBR($dados->data_fim) ?></td>
        <?php endif ?>

        <?php if($dados->data_fim == '0000-00-00'): ?>

          <td>Fixo</td>

        <?php else: ?>

          <td>Parcelado</td>

        <?php endif; ?>

        <td> 

        <!-- INÍCIO DO TOOLTIP -->
        <div class="tooltip"> 

          <?php if($dados->data_fim == '0000-00-00'): ?>
        
            
          <a href="<?= HOME_URL ?>/receita/editarFP?id=<?= $dados->idRec ?>&t=fixa" style="color:blue;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
          
          <?php else: ?>
  
          <a href="<?= HOME_URL ?>/receita/editarFP?id=<?= $dados->idRec ?>&t=parcelada" style="color:blue;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
          
          <?php endif ?>

          <span class="tooltiptext">
          Editar
          </span>
        </div>
        <!-- FINAL DO TOOLTIP  --> 
          
          |

          <!-- INÍCIO DO TOOLTIP -->
          <div class="tooltip"> 

            <button class="remove-RFP" value="<?= $dados->idRec ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

            <span class="tooltiptext">
            Excluir
            </span>
          </div>
          <!-- FINAL DO TOOLTIP  --> 



        <!-- LISTAGEM DE DESPESAS FIXAS OU PARCELADAS  -->
        <?php else: ?>

          <td><?= ucfirst($dados->status_desp) ?></td>
          <td>R$ <?= formatoMoeda($dados->valor) ?></td>
          <td><?= ucfirst($dados->descricao) ?></td>
          <td><?= $dados->getNomeCategoria() ?></td>
          <td><?= formataDataBR($dados->data_inicio) ?></td>

          <?php if($dados->data_fim == '0000-00-00'): ?>
            <td>---------------</td>
          <?php else: ?>
            <td><?= formataDataBR($dados->data_fim) ?></td>
          <?php endif ?>

          <?php if($dados->data_fim == '0000-00-00'): ?>

          <td>Fixo</td>

          <?php else: ?>

          <td>Parcelado</td>

          <?php endif; ?>

          <td> 


            <!-- INÍCIO DO TOOLTIP -->
        <div class="tooltip"> 

          <?php if($dados->data_fim == '0000-00-00'): ?>
            
          <a href="<?= HOME_URL ?>/despesa/editarFP?id=<?= $dados->idDesp ?>&t=fixa" style="color:blue;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

          <?php else: ?>

          <a href="<?= HOME_URL ?>/despesa/editarFP?id=<?= $dados->idDesp ?>&t=parcelada" style="color:blue;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

          <?php endif ?>

          <span class="tooltiptext">
          Editar
          </span>
        </div>
        <!-- FINAL DO TOOLTIP  --> 
            
            
            
            |
            <!-- INÍCIO DO TOOLTIP -->
          <div class="tooltip"> 

          <button class="remove-RFP" value="<?= $dados->idDesp ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

          <span class="tooltiptext">
          Excluir
          </span>
          </div>
          <!-- FINAL DO TOOLTIP  --> 


        <?php endif; ?>
      </tr>
      <?php endforeach ?>
  <?php else: ?>
    <br><br>Nenhuma transação encontrada!<br><br>
  <?php endif ?>
  
</table>


<!-- PAGINAÇÃO -->

<?php if(!isset($_GET['s']) and count($transacoes_cliente) > 0): ?>

<div class="pagination" style="margin-top: 20px;">         
  <a href="<?= HOME_URL ?>/transacoes<?= '?'.$query_get ?>">&laquo; Inicio</a>
  
  <!-- Páginas anteriores -->
  <?php for($pg_ant = $pg_atual - $max_links; $pg_ant <= $pg_atual-1; $pg_ant++): ?>   
  
    <?php if($pg_ant >= 1): ?>
      <a href="<?= HOME_URL ?>/transacoes?pg=<?= $pg_ant ?>&<?= $query_get ?>"><?= $pg_ant ?></a>
    <?php endif; ?>

  <?php endfor; ?>
  <!-- fim das páginas anteriores -->
    
  <!-- Página atual -->
  <a href="<?= HOME_URL ?>/transacoes?pg=<?= $pg_atual ?>&<?= $query_get ?>" class="active"><?= $pg_atual ?></a>

  <!-- Páginas posteriores -->
  <?php for($pg_dep = $pg_atual + 1; $pg_dep <= $pg_atual+$max_links; $pg_dep++): ?>   
  
    <?php if($pg_dep <= $num_pag): ?>
      <a href="<?= HOME_URL ?>/transacoes?pg=<?= $pg_dep ?>&<?= $query_get ?>"><?= $pg_dep ?></a>
    <?php endif; ?>

  <?php endfor; ?>
  <!-- fim das páginas posteriores -->



  <a href="<?= HOME_URL ?>/transacoes?pg=<?= $num_pag ?>&<?=$query_get ?>">Fim &raquo;</a>
</div>  

<?php endif ?>

<!-- FIM DA PAGINAÇÃO -->


<!-- </div> -->

<!-- ONDE IRÁ MOSTRAR AS MENSAGENS DE SUCESSOS OU DE ERROS -->
<?= $msg ?>
<!-- ----------------------------------------------------- -->

<script>
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}


function myFunction2() {
  document.getElementById("myDropdown2").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>

<script src="<?=ASSET_JS_URL ?>/popup-form-remove.js"></script>

