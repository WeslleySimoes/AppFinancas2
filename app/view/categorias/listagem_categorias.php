<div><?= $msg ?></div>


<h3 style="margin-bottom: 20px; color: #263D52;">
  <?php if(isset($_GET['status'])): ?>  
    <?php if($_GET['status'] == 'arquivado'): ?>
      Categorias Arquivadas
    <?php else: ?>
      Categorias Ativas
    <?php endif; ?>
  <?php else: ?>
    Categorias Ativas
  <?php endif; ?>
</h3>

<?php if(count($categoriasAtivas) < 10): ?>
  <?php endif; ?>
  
<div>
  <div class="dropdown" style="margin: 10px 0;">
    <a href="<?= HOME_URL ?>/categorias/cadastrar" class="dropbtn" style="text-decoration:none;">Nova Categoria</a>
  </div>

  <div class="dropdown">
    <button onclick="myFunction3()" class="dropbtn">Status</button>
      <div id="myDropdown3" class="dropdown-content">
        <a href="<?= HOME_URL ?>/categorias">Ativas</a>
        <a href="<?= HOME_URL ?>/categorias?status=arquivado">Arquivadas</a>
      </div>
  </div>

  <div class="dropdown">
    <button onclick="myFunction2()" class="dropbtn">Todas as categorias</button>
    <div id="myDropdown2" class="dropdown-content">
    <a href="<?= HOME_URL ?>/categorias<?= isset($_GET['status']) ? '?status='.$_GET['status'] : '' ?>">Todas as categorias</a>
    <a href="<?= HOME_URL ?>/categorias<?= isset($_GET['status']) ? '?status='.$_GET['status'].'&tipo=receita' : '?tipo=receita' ?>">Categorias de receitas</a>
    <a href="<?= HOME_URL ?>/categorias<?= isset($_GET['status']) ? '?status='.$_GET['status'].'&tipo=despesa' : '?tipo=despesa' ?>">Categorias de despesas</a>
  </div>
</div>


<?php if(!empty($categoriasAtivas)): ?>
<table style="margin-top: 15px;">
  <tr>
    <th>Nome</th>
    <th style="text-align: center;">Cor</th>
    <th>Tipo</th>
    <th style="text-align: center;">Ações</th>
  </tr>
    <?php foreach( $categoriasAtivas as  $categoria ): ?>
        <tr>
            <td><?= $categoria->nome ?></td>
            <td style="display: flex; justify-content: center;"><span style="display:inline-block; width: 25px; height: 25px; background-color:<?= $categoria->cor_cate ?>; border-radius: 50%;"></span></td>
            <td><?= ucfirst($categoria->tipo) ?></td>

            <?php if(isset($_GET['status']) AND $_GET['status'] == 'arquivado'): ?>
              <td style="width: 50px;">
                  <!-- INÍCIO DO TOOLTIP -->
                  <div class="tooltip"> 
                      <a href="<?= HOME_URL ?>/transacoes?categoria=<?=$categoria->idCategoria ?>" style="color:grey;"><i class="fa fa-file-archive-o" aria-hidden="true"></i></a>
  
                      <span class="tooltiptext">
                      Transações
                      </span>
                  </div>
                  |
                  <!-- INÍCIO DO TOOLTIP -->
                  <div class="tooltip"> <a href="<?= HOME_URL ?>/categorias/desarquivar?id=<?=$categoria->idCategoria ?>" style="color:blue;"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                    <span class="tooltiptext">
                      Desarquivar
                    </span>
                  </div>
                  <!-- FINAL DO TOOLTIP  -->
                  <!-- | -->
                  <!-- INÍCIO DO TOOLTIP -->
                  <!-- <div class="tooltip"> 
                    <a href="<?= HOME_URL ?>/categorias/remover?id=<?=$categoria->idCategoria ?>" style="color:red;"><i class="fa fa-trash-o" aria-hidden="true" onclick="return confirm('Tem certeza que deseja arquivar?');"></i></a>
      
                    <span class="tooltiptext">
                      Remover
                    </span>
                  </div> -->
                  <!-- FINAL DO TOOLTIP  -->
              </td>
            <?php else: ?> 
              <td>
                  <!-- INÍCIO DO TOOLTIP -->
                  <div class="tooltip"> 
                      <a href="<?= HOME_URL ?>/transacoes?categoria=<?=$categoria->idCategoria ?>" style="color:grey;"><i class="fa fa-file-archive-o" aria-hidden="true"></i></a>
  
                      <span class="tooltiptext">
                      Transações
                      </span>
                  </div>
                  |
                  <!-- INÍCIO DO TOOLTIP -->
                  <div class="tooltip"> <a href="<?= HOME_URL ?>/categorias/editar?id=<?=$categoria->idCategoria ?>" style="color:blue;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    <span class="tooltiptext">
                      Editar
                    </span>
                  </div>
                  <!-- FINAL DO TOOLTIP  -->
                  |
                  <!-- INÍCIO DO TOOLTIP -->
                  <div class="tooltip"> 
                    <a href="<?= HOME_URL ?>/categorias/arquivar?id=<?=$categoria->idCategoria ?>" style="color:#bc5e00;"><i class="fa fa-archive" aria-hidden="true" onclick="return confirm('Tem certeza que deseja arquivar?');"></i></a>
      
                    <span class="tooltiptext">
                      Arquivar
                    </span>
                  </div>
                  <!-- FINAL DO TOOLTIP  -->
              </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
</table>
<!-- PAGINAÇÃO -->

<?php if( count($categoriasAtivas) > 0): ?>

<div class="pagination" style="margin-top: 20px;">         
  <a href="<?= HOME_URL ?>/categorias<?= '?'.$query_get ?>">&laquo; Inicio</a>
  
  <!-- Páginas anteriores -->
  <?php for($pg_ant = $pg_atual - $max_links; $pg_ant <= $pg_atual-1; $pg_ant++): ?>   
  
    <?php if($pg_ant >= 1): ?>
      <a href="<?= HOME_URL ?>/categorias?pg=<?= $pg_ant ?>&<?= $query_get ?>"><?= $pg_ant ?></a>
    <?php endif; ?>

  <?php endfor; ?>
  <!-- fim das páginas anteriores -->
    
  <!-- Página atual -->
  <a href="<?= HOME_URL ?>/categorias?pg=<?= $pg_atual ?>&<?= $query_get ?>" class="active"><?= $pg_atual ?></a>

  <!-- Páginas posteriores -->
  <?php for($pg_dep = $pg_atual + 1; $pg_dep <= $pg_atual+$max_links; $pg_dep++): ?>   
  
    <?php if($pg_dep <= $num_pag): ?>
      <a href="<?= HOME_URL ?>/categorias?pg=<?= $pg_dep ?>&<?= $query_get ?>"><?= $pg_dep ?></a>
    <?php endif; ?>

  <?php endfor; ?>
  <!-- fim das páginas posteriores -->

  <a href="<?= HOME_URL ?>/categorias?pg=<?= $num_pag ?>&<?=$query_get ?>">Fim &raquo;</a>
</div>  

<?php endif ?>

<!-- FIM DA PAGINAÇÃO -->
    <?php else: ?>

      <div style="padding: 20px 0;">Nenhuma categoria econtrada!</div>

    <?php endif; ?>

<script>
  function myFunction2() {
  document.getElementById("myDropdown2").classList.toggle("show");
}

function myFunction3() {
  document.getElementById("myDropdown3").classList.toggle("show");
}
</script>