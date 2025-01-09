<div class="card mb-3" id="filtra_schede">
  <div class="card-header bg-white font-weight-bold">
    <p class="card-title m-0 border-bottom">Elenco schede</p>
    <!-- <div class="row">
      <div class="col-12"> -->
        <div class="btn-toolbar my-3" role="toolbar">
          <div class="input-group input-group-sm mr-1 mt-1">
            <select class="custom-select" name="filtroSchede" id="filter-tsk">
              <option value="" selected>tipo</option>
              <option value="1">Reperto</option>
              <option value="2">Moneta</option>
            </select>
          </div>
          <div class="input-group input-group-sm mr-1 mt-1">
            <input type="number" value="" name="filtroSchede" id="filter-nctn" step="1" class="form-control" placeholder="nctn" data-toggle="tooltip" title="inserisci solo il numero di catalogo" style="width: 90px;">
          </div>
          <div class="input-group input-group-sm mr-1 mt-1">
            <input type="number" value="" name="filtroSchede" id="filter-inventario" step="1" class="form-control" placeholder="invent." data-toggle="tooltip" title="inserisci solo il numero di inventario senza prefisso, suffisso o altri caratteri alfanumerici" style="width: 90px;">
          </div>
          <div class="input-group input-group-sm mr-1 mt-1">
            <input type="text" value="" name="filtroSchede" id="filter-titolo" class="form-control" placeholder="titolo" data-toggle="tooltip" title="inserisci il titolo">
          </div>
          <div class="input-group input-group-sm mr-1 mt-1">
            <select class="custom-select" name="filtroSchede" id="filter-ogtdid" style="width:300px">
              <option value="" selected>ogtd</option>
            </select>
          </div>
          <div class="input-group input-group-sm mr-1 mt-1">
            <select class="custom-select" name="filtroSchede" id="filter-piano">
              <option value="" selected>piano</option>
              <option value="-1">Deposito</option>
              <option value="0">Piano terra</option>
              <option value="1">Primo piano</option>
              <option value="2">Secondo piano</option>
            </select>
          </div>
          <div class="input-group input-group-sm mr-1 mt-1">
            <input type="text" value="" name="filtroSchede" id="filter-contenitore" class="form-control" placeholder="contenitore" data-toggle="tooltip" title="inserisci il numero o il nome del contenitore" style="width: 100px;">
          </div>
          <div id="searchSchedeToolbar" class="btn-group btn-group-sm mt-1 mr-1">
            <button type="button" name="cercaSchede" class="btn btn-outline-secondary">
              <i class="fa-solid fa-search"></i>
            </button>
          </div>
        </div>
      <!-- </div>
    </div> -->
  </div>
  <div class="list-group list-group-flush" id="wrapSchede">
    <li class="list-group-item colonne colonneSchede">
      <span>NCTN</span><span>INV.</span><span>TITOLO</span><span>OGTD</span><span>PIANO</span><span>SALA</span><span><i class="fa-solid fa-question-circle text-info" data-toggle="tooltip" title="per contenitore si intendono sia le cassette del deposito che le vetrine dei piani"></i> CONTENITORE</span><span></span>
    </li>
    <div id="scrollSchede" class="scroller"></div>
  </div>
  <div class="card-footer">
    <small>schede visualizzate: <span id="numSchedeCard" class="font-weight-bold"></span></small>
  </div>
</div>
