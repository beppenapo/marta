<div class="card mb-3" id="biblioCard">
  <div class="card-header bg-white font-weight-bold">
    <p class="card-title m-0 border-bottom"><i class="fa-solid fa-circle-exclamation" data-toggle="tooltip" title="L'elenco mostra le monografie e gli articoli presenti nel database"></i> Elenco record bibliografici</p>
    <div class="row">
      <div class="col-lg-8">
        <div class="btn-toolbar my-3" role="toolbar">
          <div class="input-group input-group-sm mt-2 mr-2">
            <input type="text" class="form-control filtroBiblio" name="biblioAut" placeholder="autore" value="">
          </div>
          <div class="input-group input-group-sm mt-2 mr-2">
            <input type="text" class="form-control filtroBiblio" name="biblioTitle" placeholder="titolo" value="">
          </div>
          <div class="input-group input-group-sm mt-2 mr-2">
            <select class="custom-select filtroBiblio" name="biblioTipo" id="tipoBiblio" value="">
              <option selected value="">scegli tipo</option>
              <option value="1">Monografia</option>
              <option value="2">Raccolta</option>
              <option value="0">Contributo</option>
            </select>
          </div>
          <div class="btn-group butto-group-sm mt-2 mr-2" id="searchBiblioToolbar" role="group">
            <button class="btn btn-sm btn-outline-secondary" type="button" name="searchBiblioBtn" data-toggle="tooltip" title="avvia ricerca"><i class="fa-solid fa-search" aria-hidden="true"></i></button>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="btn-toolbar justify-content-end my-3" role="toolbar">
        <div class="btn-group btn-sm mt-1" role="group">
          <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">Inserisci</button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="bibliografia_add.php" data-toggle="tooltip" data-placement="right" title="Aggiungi una monografia o una raccolta di articoli (collane, atti di convegni ...) agli authority file">authority</a>
            <a class="dropdown-item" href="contributo_add.php" data-toggle="tooltip" data-placement="right" title="aggiungi un nuovo articolo/contributo ad un volume presente nel database degli authority file">contributo</a>
          </div>
        </div>
      </div>
      </div>
    </div>

  </div>
  <div class="list-group list-group-flush" id="wrapBiblio">
    <li class="list-group-item colonne colonneBiblio">
      <span>ID</span><span>Tipo</span><span>Autore</span><span>Titolo</span><span></span>
    </li>
    <div id="scrollBiblio" class="scroller"></div>
  </div>
</div>
