<div class="card mb-3" id="filtra_schede">
  <div class="card-header bg-white font-weight-bold">
    <p class="card-title m-0 border-bottom">Elenco schede</p>
    <div class="btn-toolbar my-3" role="toolbar">
      <div class="input-group input-group-sm p-0 pr-1 pb-1 col-6 col-lg-1">
        <input type="number" value="" name="filterBtn" id="filter-nctn" step="1" class="form-control" placeholder="nctn" data-toggle="tooltip" title="inserisci solo il numero di catalogo, anche non completo">
      </div>
      <div class="input-group input-group-sm p-0 pr-1 pb-1 col-6 col-lg-1">
        <input type="number" value="" name="filterBtn" id="filter-inv" step="1" class="form-control" placeholder="invent." data-toggle="tooltip" title="inserisci solo il numero di inventario, anche non completo, senza prefisso, suffisso o altri caratteri alfanumerici">
      </div>
      <div class="input-group input-group-sm p-0 pr-1 pb-1 col-6 col-lg-3">
        <input type="text" value="" name="filterBtn" id="filter-titolo" class="form-control" placeholder="titolo" data-toggle="tooltip" title="inserisci il titolo completo o anche solo qualche parola">
      </div>
      <div class="input-group input-group-sm p-0 pr-1 pb-1 col-6 col-lg-2">
        <select class="custom-select" name="filterBtn" id="filter-ogtd"></select>
      </div>
      <div class="input-group input-group-sm p-0 pr-1 pb-1 col-6 col-lg-1">
        <select class="custom-select" name="filterBtn" id="filter-piano"></select>
      </div>
      <div class="input-group input-group-sm p-0 pr-1 pb-1 col-6 col-lg-2">
        <input type="text" value="" name="filterBtn" id="filter-cassetta" class="form-control" placeholder="contenitore" data-toggle="tooltip" title="inserisci il titolo completo o anche solo qualche parola">
      </div>
      <div class="input-group input-group-sm p-0 col-12 col-lg-1">
        <button type="button" name="cerca" class="form-control btn btn-sm btn-marta"><i class="fa-solid fa-search"></i></button>
        <!-- <button type="button" name="reset" class="form-control btn btn-sm btn-outline-secondary"><i class="fa-solid fa-reload"></i></button> -->
      </div>
    </div>
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
