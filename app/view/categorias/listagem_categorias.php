<h3 style="margin-bottom: 20px; color: #263D52;">Categorias</h3>
<?php if(count($categoriasAtivas) < 10): ?>
  <?php endif; ?>
  
<div>
  <div class="dropdown" style="margin: 10px 0;">
    <a href="<?= HOME_URL ?>/categorias/cadastrar" class="dropbtn" style="text-decoration:none;">Nova Categoria</a>
  </div>

  <div class="dropdown">
    <button onclick="myFunction2()" class="dropbtn">Todas as categorias</button>
      <div id="myDropdown2" class="dropdown-content">
      <a href="<?= HOME_URL ?>/categorias">Todas as categorias</a>
      <a href="<?= HOME_URL ?>/categorias?tipo=despesa">Categorias de Despesas</a>
      <a href="<?= HOME_URL ?>/categorias?tipo=receita">Categorias de Receitas</a>
    </div>

  <div class="dropdown" style="margin: 10px 0;">
  <a href="<?= HOME_URL ?>/categorias/arquivadas" class="dropbtn" style="text-decoration:none;">Arquivadas</a>
</div>
</div>

<div><?= $msg.'<br>' ?></div>

<table>
    <tr>
        <th>Nome</th>
        <th style="text-align: center;">Cor</th>
        <th>Tipo</th>
        <th>Ações</th>
    </tr>
    <?php foreach( $categoriasAtivas as  $categoria ): ?>
        <tr>
            <td><?= $categoria->nome ?></td>
            <td style="display: flex; justify-content: center;"><span style="display:inline-block; width: 25px; height: 25px; background-color:<?= $categoria->cor_cate ?>; border-radius: 50%;"></span></td>
            <td><?= ucfirst($categoria->tipo) ?></td>
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
        </tr>
    <?php endforeach; ?>
</table>

<script>
  function myFunction2() {
  document.getElementById("myDropdown2").classList.toggle("show");
}
</script>