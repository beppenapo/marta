<div class="card mb-3" id="filtra_schede">
  <div class="card-header bg-white font-weight-bold">
    <p class="card-title m-0 border-bottom">Cerca schede per attributi</p>
    <div class="btn-toolbar my-3" role="toolbar">
      <div class="input-group input-group-sm col-12 col-lg-4 mt-2">
        <input type="number" value="" name="searchNctn" step="1" class="form-control" placeholder="nctn" data-toggle="tooltip" title="inserisci solo il numero di catalogo, anche non completo">
        <div class="input-group-append">
          <button class="btn btn-sm btn-outline-secondary searchNctnBtn" type="button" name="checkBtn" value="8"><i class="fas fa-search"></i></button>
        </div>
      </div>
      <div class="input-group input-group-sm col-12 col-lg-4 mt-2">
        <input type="number" value="" name="searchInv" step="1" class="form-control" placeholder="inventario" data-toggle="tooltip" title="inserisci solo il numero di inventario, anche non completo, senza prefisso, suffisso o altri caratteri alfanumerici">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary searchInvBtn" type="button" name="checkBtn" value="9"><i class="fas fa-search"></i></button>
        </div>
      </div>
      <?php if ($_SESSION['classe'] !== 3) { ?>
        <div class="btn-group btn-group-sm col-lg-4 mt-2" role="group">
          <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">stato scheda</button>
          <div class="dropdown-menu" id="statoSchede">
            <button type="button" class="dropdown-item" name="checkBtn" value="3">da chiudere</button>
            <button type="button" class="dropdown-item" name="checkBtn" value="4">da verificare</button>
            <button type="button" class="dropdown-item" name="checkBtn" value="5">da inviare</button>
            <button type="button" class="dropdown-item" name="checkBtn" value="6">revisione ICCD</button>
            <button type="button" class="dropdown-item" name="checkBtn" value="7">accettate</button>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <div class="list-group list-group-flush" id="wrapSchede">
    <li class="list-group-item colonne colonneSchede">
      <span>NCTN</span><span>INV.</span><span>TITOLO</span><span></span>
    </li>
    <div id="scrollSchede" class="scroller"></div>
  </div>
</div>
