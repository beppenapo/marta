<?php
require("api/php/scheda.php");
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/scheda.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php require('assets/mainMenu.php'); ?>
    <div id="loadingDiv" class="flexDiv invisible"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main>
      <div class="container">
        <div class="row mb-4">
          <div class="col">
            <h3 class="border-bottom">Stai inserendo una nuova scheda Numismatica (NU)</h3>
            <small class="text-danger font-weight-bold d-block">* Campo obbligatorio</small>
            <small class="font-weight-bold d-block">* Obbligatorietà di contesto</small>
            <small class="d-block">* Campo facoltativo</small>
          </div>
        </div>
        <form id="formScheda" autocomplete="off">
          <?php
            require_once($formFolder.'cd.html');
            require_once($formFolder.'titolo_scheda.html');
          ?>

          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">OG - OGGETTO</legend>

              <div class="form-row">
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogtd" class="text-danger font-weight-bold"><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="scorri la lista per selezionare il valore desiderato"></i> OGTD - Definizione</label>
                  <select class="form-control form-control-sm tab" data-table="og_nu" id="ogtd" name="ogtd" required>
                    <option value="" selected disabled>-- definizione --</option>
                    <?php echo join("",$liste['ogtd']); ?>
                  </select>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogr" class="text-danger font-weight-bold">OGR - Disponibilità</label>
                  <select class="form-control form-control-sm tab" data-table="og_nu" id="ogr" name="ogr" required>
                    <option value="" selected disabled>-- disponibilità --</option>
                    <?php echo join("",$liste['ogr']); ?>
                  </select>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogtt">OGTT - Classificazione tipologica</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="og_nu" name="ogtt" id="ogtt" value="">
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogth">OGTH - Classificazione funzionale</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="og_nu" name="ogth" id="ogth" value="">
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogtl">OGTL - Legenda tipo</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="og_nu" name="ogtl" id="ogth" value="">
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogto">OGTO - Nominale</label>
                  <select class="form-control form-control-sm tab" data-table="og_nu" id="ogto" name="ogto">
                    <option value="" selected disabled>-- nominale --</option>
                    <?php echo join("",$liste['ogto']); ?>
                  </select>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogts">OGTS - Specifiche</label>
                  <select class="form-control form-control-sm tab" data-table="og_nu" id="ogts" name="ogts">
                    <option value="" selected disabled>-- specifiche --</option>
                    <?php echo join("",$liste['ogts']); ?>
                  </select>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogtr">OGTR - Serie</label>
                  <select class="form-control form-control-sm tab" data-table="og_nu" id="ogtr" name="ogtr">
                    <option value="" selected disabled>-- serie --</option>
                    <?php echo join("",$liste['ogtr']); ?>
                  </select>
                </div>
              </div>
            </fieldset>
          </div>

          <?php
            require_once($formFolder.'lc.html');
            // require_once($formFolder.'la.html');
            require_once($formFolder.'ub.html');
            require_once($formFolder.'gp.html');
            require_once($formFolder.'re.html');
            require_once($formFolder.'dt.html');
            require_once($formFolder.'mt.html');
          ?>


          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">DA - DATI ANALITICI</legend>
              <fieldset id="desFieldset" class="mb-3">
                <legend class="text-marta font-weight-bold border-bottom mb-3">des - descrizione</legend>
                <div class="form-row mb-3">
                  <div class="col-md-4">
                    <label for="desa" class="text-danger font-weight-bold">DESA - Dritto</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desa" name="desa" value="" required>
                  </div>
                  <div class="col-md-4">
                    <label for="desm" class="text-danger font-weight-bold">DESM - Rovescio</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desm" name="desm" value="" required>
                  </div>
                  <div class="col-md-4">
                    <label for="desv" class="text-danger font-weight-bold">DESV - Taglio</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desv" name="desv" value="" required>
                  </div>
                </div>
                <div class="form-row mb-3">
                  <div class="col-md-4">
                    <label for="desl" class="text-danger font-weight-bold">DESL - Legenda dritto</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desl" name="desl" value="" required>
                  </div>
                  <div class="col-md-4">
                    <label for="desg" class="text-danger font-weight-bold">DESG - Legenda rovescio</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desg" name="desg" value="" required>
                  </div>
                  <div class="col-md-4">
                    <label for="desu" class="text-danger font-weight-bold">DESU - Soggetto</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desu" name="desu" value="" required>
                  </div>
                </div>
                <div class="form-row mb-3">
                  <div class="col-md-4">
                    <label for="desf" class="text-danger font-weight-bold">DESF - Alfabeto/scrittura dritto</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desf" name="desf" value="" required>
                  </div>
                  <div class="col-md-4">
                    <label for="dest" class="text-danger font-weight-bold">DEST - Alfabeto/scrittura rovescio</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="dest" name="dest" value="" required>
                  </div>
                </div>
                <div class="form-row mb-3">
                  <div class="col-md-4">
                    <label for="desn" class="text-danger font-weight-bold">DESN - Lingua dritto</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desn" name="desn" value="" required>
                  </div>
                  <div class="col-md-4">
                    <label for="desr" class="text-danger font-weight-bold">DESR - Lingua rovescio</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desr" name="desr" value="" required>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-8">
                    <label for="desd" class="text-danger font-weight-bold">DESD - Descrizione bene paramonetale</label>
                    <textarea class="form-control form-control-sm tab" data-table="da" id="desd" name="desd" rows="3" required></textarea>
                  </div>
                </div>
              </fieldset>
            </fieldset>
          </div>
          <?php
          require($formFolder."co.html");
          require($formFolder."tu.html");
          require($formFolder."ad.html");
          require($formFolder."an.html");
          ?>
          <div class="form-group">
            <div class="row">
              <div class="col-md-4 col-lg-3">
                <button type="submit" class="btn btn-sm btn-marta tastischeda" name="submit" id="submit">salva dati</button>
                <a href="dashboard.php" class="btn btn-sm btn-outline-secondary">annulla</a>
              </div>
              <div class="col-md-8 col-lg-9">
                <div id="errorDiv">
                  <h6 class="text-danger font-weight-bold" >Prima di salvare correggi i seguenti errori</h6>
                  <ul class="list-group list-group-flush"></ul>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>

    </main>
    <?php require('assets/toast.html'); ?>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/scheda-nu.js" charset="utf-8"></script>
  </body>
</html>
