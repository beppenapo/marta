<?php
require("api/php/scheda.php");
if (!isset($_SESSION['id'])){ header("location:login.php");}
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <link rel="stylesheet" href="css/scheda.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php require('assets/mainMenu.php'); ?>
    <div id="loadingDiv" class="flexDiv invisible"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main>
      <div class="my-5 bg-white shadow">
        <div class="container">
          <div class="row  align-items-center g-5 py-5">
            <div class="col-lg-4">
              <img src="img/spiderman.png" class="d-block mx-auto img-fluid" loading="lazy">
            </div>
            <div class="col-lg-8 text-center">
              <h1 class="font-weight-bold text-info mb-3">Da un grande potere derivano grandi responsabilità</h1>
              <p class="lead">Stai duplicando il reperto <span class="font-weight-bold"><?php echo $scheda['scheda']['titolo'];?></span><br>Da questa pagina, se necessario, puoi modificare i dati originali per adattarli al nuovo reperto<br>Per gestire la bibliografia e i file accessori devi prima salvare la scheda e poi accedere alla pagina di modifica.</p>
              <div class="alert alert-danger" role="alert">
                <p class="lead">La scheda "duplicata" deve necessariamente avere un nuovo numero di catalogo, ricordati di inserirlo manualmente altrimenti verrà assegnato automaticamente dal sistema.</p>
                <p class="lead p-0 m-0">Il sistema, inoltre, aggiorna il titolo anteponendo al dato originale la data, completa di ore, minuti e secondi rilevati al momento della modifica. Ovviamente il titolo può essere modificato manualmente.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <form id="formScheda" data-action="cloneScheda" autocomplete="off">
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
                    <?php if (!isset($_POST['s'])) {
                      echo '<option value="" selected disabled>-- definizione --</option>';
                    } ?>
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
                  <select class="form-control form-control-sm tab" data-table="og_ra" id="l5" name="l5" <?php echo $ogtdDisabled; ?>>
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
                  <input type="text" class="form-control form-control-sm tab" data-table="og_ra" name="ogtt" id="ogtt" value="<?php echo $scheda['og']['ogtt']; ?>">
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
                    <textarea class="form-control form-control-sm tab" data-table="da" id="deso" name="deso" rows="8" value="" maxlength="1000" required><?php echo $scheda['da']['deso']; ?></textarea>
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
                <button type="submit" class="btn btn-sm btn-marta tastischeda" name="submit" id="submit">salva dati</button>
                <a href="schedaView.php?get=<?php echo $_POST['s']; ?>" class="btn btn-sm btn-outline-secondary">annulla</a>
              </div>
              <div class="col-md-8 col-lg-9">
                <div id="errorDiv">
                  <h6 class="text-danger font-weight-bold" >Prima di salvare correggi i seguenti errori</h6>
                  <ul class="list-group list-group-flush"></ul>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" name="scheda" data-table="scheda" value="<?php echo $_POST['s']; ?>">
        </form>
      </div>
    </main>
    <?php require('assets/toast.html'); ?>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <script src="js/leaflet-bing-layer.js" charset="utf-8"></script>
    <script src="js/wmsTile.js" charset="utf-8"></script>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/scheda-ra.js" charset="utf-8"></script>
  </body>
</html>
