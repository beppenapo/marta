<div class="card mb-3" id="filtra_schede">
  <div class="card-header bg-white font-weight-bold">
    <p class="card-title m-0 border-bottom">Elenco schede</p>
    <div class="btn-toolbar my-3" role="toolbar">
      <div class="input-group input-group-sm m-2 col-12 col-md-4 col-lg-3">
        <input type="number" value="" name="searchNctn" step="1" class="form-control" placeholder="nctn" data-toggle="tooltip" title="inserisci solo il numero di catalogo, anche non completo">
      </div>
      <div class="input-group input-group-sm m-2 col-12 col-md-4 col-lg-3">
        <input type="number" value="" name="searchInv" step="1" class="form-control" placeholder="inventario" data-toggle="tooltip" title="inserisci solo il numero di inventario, anche non completo, senza prefisso, suffisso o altri caratteri alfanumerici">
      </div>
      <div class="input-group input-group-sm m-2 col-12 col-md-4 col-lg-3">
        <input type="text" value="" name="searchTitle" class="form-control" placeholder="titolo" data-toggle="tooltip" title="inserisci il titolo completo o anche solo qualche parola">
      </div>
      <div class="input-group input-group-sm m-2 col-12 col-md-4 col-lg-3">
        <select class="custom-select" name="ogtdSelect"></select>
      </div>
    </div>
  </div>
  <div class="list-group list-group-flush" id="wrapSchede">
    <li class="list-group-item colonne colonneSchede">
      <span>NCTN</span><span>INV.</span><span>TITOLO</span><span>OGTD</span><span>PIANO</span><span>SALA</span><span>CASSETTA</span><span></span>
    </li>
    <div id="scrollSchede" class="scroller"></div>
  </div>
  <div class="card-footer">
    <small>schede visualizzate: <span id="numSchedeCard" class="font-weight-bold"></span></small>
  </div>
</div>
