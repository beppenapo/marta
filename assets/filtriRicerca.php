<div class="row">
  <div class="col">
    <h5>Dati principali</h5>
    <nav>
      <div class="btn-toolbar mb-3" role="toolbar">
        <div class="btn-group btn-group-sm btn-group-toggle mr-2" role="group" data-toggle="buttons">
          <label class="btn btn-outline-secondary">
            <input type="radio" class="filtro" name="tipo" value="1" id="ra"><span>RA</span>
          </label>
          <label class="btn btn-outline-secondary">
            <input type="radio" class="filtro" name="tipo" value="2" id="nu"><span>NU</span>
          </label>
        </div>
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro" name="operatore">
            <option value="">--schedatore--</option>
            <?php echo join('',$opt); ?>
          </select>
        </div>
        <!-- <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro" name="statoScheda">
            <option value="" selected>--stato scheda--</option>
            <option value="aperta">in lavorazione</option>
            <option value="verificata">da verificare</option>
            <option value="inviata">da inviare</option>
            <option value="accettata">in attesa di accettazione ICCD</option>
            <option value="chiusa">iter completo, scheda chiusa</option>
          </select>
        </div> -->
        <div class="input-group input-group-sm mr-2">
          <div class="input-group-prepend" data-toggle="tooltip" data-placement="top" title="non è necessario inserire il numero di catalogo completo, il sistema cercherà tutti i numeri di catalogo che contengono il valore inserito nel campo. Per una ricerca accurata si consiglia di inserire almeno 4 numeri">
            <span class="input-group-text">
              <i class="fa-solid fa-circle-question"></i>
            </span>
          </div>
          <input type="text" class="form-control filtro" name="catalogo" placeholder="numero catalogo">
        </div>
        <div class="input-group input-group-sm">
          <div class="input-group-prepend" data-toggle="tooltip" data-placement="top" title="non è necessario inserire il numero di inventario completo, il sistema cercherà tutti i numeri di inventario che contengono il valore inserito nel campo. Per una ricerca accurata si consiglia di inserire almeno 4 caratteri">
            <span class="input-group-text">
              <i class="fa-solid fa-circle-question"></i>
            </span>
          </div>
          <input type="text" class="form-control filtro" name="inventario" placeholder="inventario museo">
        </div>
      </div>
    </nav>
  </div>
</div>
<!-- <div class="row">
  <div class="col">
    <label>caratteristiche reperto</label>
    <nav>
      <div class="btn-toolbar mb-3" role="toolbar">
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro" name="cls">
            <option value="" selected>--CLS - categoria--</option>
          </select>
        </div>
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro" name="ogtd">
            <option value="" selected>--OGTD - definizione--</option>
          </select>
        </div>
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro" name="materia">
            <option value="" selected>--materia--</option>
          </select>
        </div>
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro" name="tecnica">
            <option value="" selected>--tecnica--</option>
          </select>
        </div>
        <div class="input-group input-group-sm">
          <input type="text" class="form-control filtro" name="titolo" placeholder="titolo">
        </div>
      </div>
    </nav>
  </div>
</div> -->
<!-- <div class="row">
  <div class="col">
    <label>cronologia</label>
    <nav>
      <div class="btn-toolbar mb-3" role="toolbar">
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro" name="dtzgi">
            <option value="" selected>--Cronologia iniziale--</option>
          </select>
        </div>
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro" name="dtzgf">
            <option value="" selected>--Cronologia finale--</option>
          </select>
        </div>
      </div>
    </nav>
  </div>
</div> -->
<!-- <div class="row">
  <div class="col">
    <label>ubicazione interna</label>
    <nav>
      <div class="btn-toolbar mb-3" role="toolbar">
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro" name="piano">
            <option value="" selected disabled>--piano--</option>
            <option value="-1">Deposito</option>
            <option value="0">Piano terra</option>
            <option value="1">Primo piano</option>
            <option value="3">Terzo piano</option>
          </select>
        </div>
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro" name="sala">
            <option value="" selected>--sala--</option>
          </select>
        </div>
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro" name="contenitore">
            <option value="" selected>--scaffale/vetrina--</option>
          </select>
        </div>
        <div class="input-group input-group-sm">
          <input type="text" class="form-control filtro" name="cassetta" placeholder="cassetta">
        </div>
      </div>
    </nav>
  </div>
</div> -->
<!-- <div class="row">
  <div class="col">
    <label>localizzazione geografica</label>
    <nav>
      <div class="btn-toolbar mb-3" role="toolbar">
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro" name="comune">
            <option value="" selected>--comune--</option>
          </select>
        </div>
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro" name="via">
            <option value="" selected>--via--</option>
          </select>
        </div>
      </div>
    </nav>
  </div>
</div> -->
<div class="row">
  <div class="col">
    <button type="button" class="btn btn-sm btn-marta" name="cerca">cerca</button>
    <button type="button" class="btn btn-sm btn-marta invisible" name="clear">annulla filtri</button>
  </div>
</div>
