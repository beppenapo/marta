<?php
require("api/php/scheda.php");
if (!isset($_SESSION['id'])){ header("location:login.php");}
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
            <h3 class="border-bottom">Stai inserendo una nuova scheda di Reperto Archeologico (RA)</h3>
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
                  <label for="l3" class="text-danger font-weight-bold"><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Categoria - livello III"></i> CLS - Categoria</label>
                  <select class="form-control form-control-sm tab" data-table="og_ra" id="l3" name="l3" required>
                    <option value="" selected disabled>-- definizione --</option>
                    <?php foreach ($listeRA['l3'] as $key => $val) {
                      if(isset($_POST['s'])){$sel = $val['id'] == $scheda['og']['cls3id'] ? 'selected' : '';}
                      echo "<option value='".$val['id']."' ".$sel.">".$val['value']."</option>";
                    } ?>
                  </select>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="l4" class="text-danger font-weight-bold"><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Termine o locuzione che individua il bene oggetto della scheda in base alla connotazione funzionale e morfologica."></i> OGTD - Definizione</label>
                  <select class="form-control form-control-sm tab" data-table="og_ra" id="l4" name="l4" required>
                    <?php foreach ($l4List as $key => $val) {
                      $sel = $val['id'] == $scheda['og']['cls4id'] ? 'selected' : '';
                      echo "<option value='".$val['id']."' ".$sel.">".$val['value']."</option>";
                    } ?>
                  </select>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="l5"><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Termine che specifica forma, funzione, parte o produzione (se si tratta di ceramica)."></i> OGTD - specifiche</label>
                  <select class="form-control form-control-sm tab" data-table="og_ra" id="l5" name="l5" <?php echo $ogtdDis; ?>>
                    <option value="">-- definizione --</option>
                    <?php
                        foreach ($l5List as $key => $val) {
                          $sel = $val['id'] == $scheda['og']['cls5id'] ? 'selected' : '';
                          echo "<option value='".$val['id']."' ".$sel.">".$val['value']."</option>";
                        }
                    ?>
                  </select>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogtt"><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Specifiche relative alla tipologia del bene catalogato.<br>Esempio<br>OGTD: Anfora<br>OGTT: Dressel 20"></i> OGTT - Tipologia</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="og_ra" name="ogtt" id="ogtt" value="">
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
                  <div class="col">
                    <label for="deso" class="text-danger font-weight-bold">DESO - Indicazioni sull'oggetto</label>
                    <textarea class="form-control form-control-sm tab" data-table="da" id="deso" name="deso" rows="8" value="" maxlength="1000" required></textarea>
                    <small class="d-block">caratteri disponibili: <span id="countDesoChar">1000</span></small>
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
                <button type="submit" class="btn btn-sm btn-marta tastischeda" name="submit" id="submit" disabled>salva dati</button>
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
    <script src="js/scheda-ra.js" charset="utf-8"></script>
  </body>
</html>
