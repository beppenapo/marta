<div id="addComunicazioneDiv" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form name="addComunicazioneForm">
      <input type="hidden" name="idComunicazione" value="">
      <div class="modal-content">
        <div class="modal-body">
          <div class="form-row">
            <div class="col">
              <label for="testo">Scrivi la tua nota</label>
              <textarea name="testo" id="testo" class="form-control form-control-sm" rows="8" cols="80" required></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">annulla</button>
          <button type="submit" class="btn btn-primary btn-sm" name="saveComunicazione">salva</button>
        </div>
      </div>
    </form>
  </div>
</div>
