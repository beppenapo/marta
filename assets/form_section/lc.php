<div class="form-group">
  <fieldset class="bg-light rounded border p-3">
    <legend class="w-auto bg-marta text-white border rounded p-1">LC - LOCALIZZAZIONE GEOGRAFICO-AMMINISTRATIVA</legend>
    <fieldset id="lcFieldset" class="mb-3">
      <legend class="text-marta font-weight-bold border-bottom">LDCS - collocazione specifica</legend>
      <div class="form-row">
        <div class="col-md-4 col-lg-2">
          <label for="piano" class="text-danger font-weight-bold">Piano</label>
          <select class="form-control form-control-sm tab" data-table="lc" id="piano" name="piano" required>
            <option value="" selected disabled>-- piano --</option>
            <?php
            foreach ($listeComuni['piani'] as $key => $val) {
              if(isset($_POST['s'])){ $sel = $val['id'] == $scheda['lc']['piano'] ? 'selected' : '';}
              echo "<option value='".$val['id']."' ".$sel.">".$val['value']."</option>";
            }
            ?>
          </select>
        </div>
        <div class="col-md-4 col-lg-2">
          <label for="sala" class="text-danger font-weight-bold">Sala</label>
          <select class="form-control form-control-sm" data-table="lc" id="sala" name="sala" required>
            <?php
            if(!isset($_POST['s'])){echo "<option value='' selected disabled>--sala--</option>";}
            foreach ($salaList as $key => $val) {
              if(isset($_POST['s'])){ $sel = $val['sala'] == $scheda['lc']['sala'] ? 'selected' : '';}
              $sala = !$val['descrizione'] ? $val['sala'] : $val['descrizione'];
              echo "<option value='".$val['id']."' ".$sel.">".$sala."</option>";
            }
            ?>
          </select>
        </div>
        
        <!-- vetrine / scaffali -->
        <div class="col-md-4 col-lg-2">
          <div class="" id="lcContenitoreDiv">
            <label for="contenitore" id="contenitoreLabel" class="<?php echo $labelContenitoreClass; ?>">
              <?php if(isset($_POST['s'])){echo $labelContenitore;}?>
            </label>
            <select class="form-control form-control-sm" data-table="lc" id="contenitore" name="contenitore">
              <?php echo implode("",$selContenitore); ?>
            </select>
          </div>
        </div>

        <div class="col-md-4 col-lg-2">
          <div class="<?php echo $hideColonna; ?>" id="lcColonnaDiv">
            <label for="colonna" id="colonnaLabel" class="text-danger font-weight-bold">Colonna</label>
            <select class="form-control form-control-sm" data-table="lc" id="colonna" name="colonna">
              <?php if(!empty($selColonna)){echo implode("",$selColonna);} ?>
            </select>
          </div>
        </div>

        <div class="col-md-4 col-lg-2">
          <div class="<?php echo $hideRipiano; ?>" id="lcRipianoDiv">
            <label for="ripiano" id="ripianoLabel" class="text-danger font-weight-bold"><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="" aria-hidden="true" data-original-title='in alcuni scaffali vengono chiamati "plateau" o "cassetto"'></i> Ripiano</label>
            <select class="form-control form-control-sm" data-table="lc" id="ripiano" name="ripiano">
              <?php if(!empty($selRipiano)){echo implode("",$selRipiano);} ?>
            </select>
          </div>
        </div>

        <div class="col-md-4 col-lg-2">
          <div class="<?php echo $hideCassetta; ?>" id="lcCassettaDiv">
            <label for="cassetta" id="cassettaLabel" class="">Cassetta</label>
            <select class="form-control form-control-sm" data-table="lc" id="cassetta" name="cassetta">
              <?php if(!empty($selCassette)){echo implode("",$selCassette);} ?>
            </select>
          </div>
        </div>
      </div>
      <div class="form-row <?php echo $lcNoVetrine; ?>" id="noVetrine">
        <div class="col-12">
          <div class="alert alert-warning mt-3 mb-0">
            <label class="">Non sono presenti vetrine per la sala selezionata, il reperto verrà considerato come "fuori vetrina"</label>
          </div>
        </div>
      </div>
      <div class="form-row <?php echo $lcNoCassette; ?>" id="noCassette">
        <div class="col-12">
          <div class="alert alert-warning mt-3 mb-0">
            <label class="">Non sono presenti cassette per il ripiano selezionato, il reperto verrà considerato come "fuori cassetta"</label>
          </div>
        </div>
      </div>
      <div class="form-row <?php echo $lcFuoriVetrina; ?>" id="fuoriVetrina">
        <div class="col-12">
          <div class="alert alert-info mt-3 mb-0">
            <label class="">Reperto fuori vetrina</label>
          </div>
        </div>
      </div>
      <div class="form-row <?php echo $lcFuoriCassetta; ?>" id="fuoriVetrina">
        <div class="col-12">
          <div class="alert alert-info mt-3 mb-0">
            <label class="">Reperto fuori cassetta</label>
          </div>
        </div>
      </div>
    </fieldset>
  </fieldset>
</div>
