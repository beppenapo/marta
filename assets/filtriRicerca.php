<h3>Ricerca per valori</h3>
<div class="row">
  <div class="col">
    <h5>Dati principali</h5>
    <nav>
      <div class="btn-toolbar mb-3" role="toolbar">
        <div class="input-group input-group-sm mr-2">
          <div class="input-group-prepend" data-toggle="tooltip" data-placement="top" title="non è necessario inserire il numero di catalogo completo, il sistema cercherà tutti i numeri di catalogo che contengono il valore inserito nel campo. Per una ricerca accurata si consiglia di inserire almeno 4 numeri">
            <span class="input-group-text">
              <i class="fa-solid fa-circle-question"></i>
            </span>
          </div>
          <input type="text" class="form-control filtro" name="catalogo" placeholder="numero catalogo">
        </div>
        <div class="input-group input-group-sm mr-2">
          <div class="input-group-prepend" data-toggle="tooltip" data-placement="top" title="non è necessario inserire il numero di inventario completo, il sistema cercherà tutti i numeri di inventario che contengono il valore inserito nel campo. Per una ricerca accurata si consiglia di inserire almeno 4 caratteri">
            <span class="input-group-text">
              <i class="fa-solid fa-circle-question"></i>
            </span>
          </div>
          <input type="text" class="form-control filtro" name="inventario" placeholder="inventario museo">
        </div>
        <div class="input-group input-group-sm">
          <div class="input-group-prepend" data-toggle="tooltip" data-placement="top" title="non è necessario inserire il titolo completo">
            <span class="input-group-text">
              <i class="fa-solid fa-circle-question"></i>
            </span>
          </div>
          <input type="text" class="form-control filtro" name="titolo" placeholder="titolo">
        </div>
      </div>
    </nav>
  </div>
</div>
<div class="row">
  <div class="col">
    <h5>Caratteristiche reperto</h5>
    <nav>
      <div class="btn-toolbar mb-3" role="toolbar">
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro filtraTsk" name="ogtd">
            <option value="" selected>--OGTD--</option>
            <?php foreach ($filtriScheda['ogtd'] as $ogtd) {  echo"<option data-tsk='".$ogtd['tsk']."' value='".$ogtd['ogtd']."'>".$ogtd['ogtd']."</option>"; } ?>
          </select>
        </div>
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro filtraTsk" name="materia">
            <option value="" selected>--materia--</option>
            <?php foreach ($filtriScheda['materia'] as $materia) {  echo"<option data-tsk='".$materia['tsk']."' value='".$materia['materia']."'>".$materia['materia']."</option>"; } ?>
          </select>
        </div>
        <div class="input-group input-group-sm mr-2" role="group">
          <select class="form-control filtro" name="tecnica">
            <option value="" selected>--tecnica--</option>
            <?php foreach ($filtriScheda['tecnica'] as $tecnica) {  echo"<option value='".$tecnica['tecnica']."'>".$tecnica['tecnica']."</option>"; } ?>
          </select>
        </div>
      </div>
    </nav>
  </div>
</div>
<div class="row">
  <div class="col">
    <button type="button" class="btn btn-sm btn-marta" name="cerca">cerca</button>
    <button type="button" class="btn btn-sm btn-marta invisible" name="clear">annulla filtri</button>
  </div>
</div>
