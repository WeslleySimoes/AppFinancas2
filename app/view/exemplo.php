
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Contas</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cadastrar</li>
  </ol>
</nav>

<div class="card border-secondary mb-3" style="border-color: #c9c9c9 !important;">
  <div class="card-header" style="font-weight: bold;background-color:#EAEDEA !important;">Cadastrar conta
</div>

  <div class="card-body text-secondary">
  <form>
  <div class="row mb-3">
   <label for="valor" class="col-sm-2 col-form-label">Valor (R$)</label>
   <div class="col-sm-10">
        <input type="text" class="form-control money" id="valor" placeholder="1.500,00">
    </div>
  </div>
  <div class="row mb-3">
   <label for="descricao" class="col-sm-2 col-form-label">Descrição</label>
   <div class="col-sm-10">
        <input type="text" class="form-control" id="descricao" placeholder="Insira um descrição...">
    </div>
  </div>
  <div class="row mb-3">
    <label for="instituicao" class="col-sm-2 col-form-label">Instituição</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="instituicao" placeholder="Insira uma instituição...">
    </div>
  </div>

  <div class="row mb-3">
    <label for="tipoConta" class="col-sm-2 col-form-label">Tipo de conta</label>
    <div class="col-sm-10">
        <label class="visually-hidden" for="inlineFormSelectPref">Preference</label>
        <select class="form-select" id="inlineFormSelectPref">
          <option value="1" selected>Corrente</option>
          <option value="2">Poupança</option>
          <option value="3">Dinheiro</option>
          <option value="4">Outro</option>
        </select>
    </div>
  </div>
  <button type="submit" class="btn btn-success">Cadastrar</button>
</form>
  </div>
</div>
