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
                  <label for="ogtd" class="text-danger font-weight-bold"><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="scorri la lista per selezionare il valore desiderato"></i> OGTD - Definizione</label>
                  <select class="form-control form-control-sm tab" data-table="og_nu" id="ogtd" name="ogtd" required>
                    <?php
                      foreach ($listeNU['ogtd'] as $key => $val) {
                        $sel = $val['id'] == $scheda['og']['ogtdid'] ? 'selected' : '';
                        echo "<option value='".$val['id']."' ".$sel.">".$val['value']."</option>";
                      }
                    ?>
                  </select>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogr" class="text-danger font-weight-bold">OGR - Disponibilità</label>
                  <select class="form-control form-control-sm tab" data-table="og_nu" id="ogr" name="ogr" required>
                    <?php
                      foreach ($listeNU['ogr'] as $key => $val) {
                        $sel = $val['id'] == $scheda['og']['ogrid'] ? 'selected' : '';
                        echo "<option value='".$val['id']."' ".$sel.">".$val['value']."</option>";
                      }
                    ?>
                  </select>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogtt">OGTT - Classificazione tipologica</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="og_nu" name="ogtt" id="ogtt" value="<?php echo $scheda['og']['ogtt']; ?>">
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogth">OGTH - Classificazione funzionale</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="og_nu" name="ogth" id="ogth" value="<?php echo $scheda['og']['ogth']; ?>">
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogtl">OGTL - Legenda tipo</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="og_nu" name="ogtl" id="ogth" value="<?php echo $scheda['og']['ogtl']; ?>">
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogto">OGTO - Nominale</label>
                  <select class="form-control form-control-sm tab" data-table="og_nu" id="ogto" name="ogto">
                    <option value="" selected>-- definizione --</option>
                    <?php foreach ($listeNU['ogto'] as $key => $val) {
                      $sel = $val['id'] == $scheda['og']['ogtoid'] ? 'selected' : '';
                      echo "<option value='".$val['id']."' ".$sel.">".$val['value']."</option>";
                    } ?>
                  </select>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogts">OGTS - Specifiche</label>
                  <select class="form-control form-control-sm tab" data-table="og_nu" id="ogts" name="ogts">
                    <option value="" selected>-- definizione --</option>
                    <?php foreach ($listeNU['ogts'] as $key => $val) {
                      $sel = $val['id'] == $scheda['og']['ogtsid'] ? 'selected' : '';
                      echo "<option value='".$val['id']."' ".$sel.">".$val['value']."</option>";
                    } ?>
                  </select>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                  <label for="ogtr">OGTR - Serie</label>
                  <select class="form-control form-control-sm tab" data-table="og_nu" id="ogtr" name="ogtr">
                    <option value="" selected>-- definizione --</option>
                    <?php foreach ($listeNU['ogtr'] as $key => $val) {
                      $sel = $val['id'] == $scheda['og']['ogtrid'] ? 'selected' : '';
                      echo "<option value='".$val['id']."' ".$sel.">".$val['value']."</option>";
                    } ?>
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
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desa" name="desa" value="<?php echo($scheda['da']['desa']); ?>" required>
                  </div>
                  <div class="col-md-4">
                    <label for="desm" class="text-danger font-weight-bold">DESM - Rovescio</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desm" name="desm" value="<?php echo($scheda['da']['desm']); ?>" required>
                  </div>
                  <div class="col-md-4">
                    <label for="desv" class="text-danger font-weight-bold">DESV - Taglio</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desv" name="desv" value="<?php echo($scheda['da']['desv']); ?>" required>
                  </div>
                </div>
                <div class="form-row mb-3">
                  <div class="col-md-4">
                    <label for="desl" class="text-danger font-weight-bold">DESL - Legenda dritto</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desl" name="desl" value="<?php echo($scheda['da']['desl']); ?>" required>
                  </div>
                  <div class="col-md-4">
                    <label for="desg" class="text-danger font-weight-bold">DESG - Legenda rovescio</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desg" name="desg" value="<?php echo($scheda['da']['desg']); ?>" required>
                  </div>
                  <div class="col-md-4">
                    <label for="desu" class="text-danger font-weight-bold">DESU - Soggetto</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desu" name="desu" value="<?php echo($scheda['da']['desu']); ?>" required>
                  </div>
                </div>
                <div class="form-row mb-3">
                  <div class="col-md-4">
                    <label for="desf" class="text-danger font-weight-bold">DESF - Alfabeto/scrittura dritto</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desf" name="desf" value="<?php echo($scheda['da']['desf']); ?>" required>
                  </div>
                  <div class="col-md-4">
                    <label for="dest" class="text-danger font-weight-bold">DEST - Alfabeto/scrittura rovescio</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="dest" name="dest" value="<?php echo($scheda['da']['dest']); ?>" required>
                  </div>
                  <div class="col-md-4">
                    <label for="deso" class="text-danger font-weight-bold">DESO - Taglio</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="deso" name="deso" value="<?php echo($scheda['da']['deso']); ?>" required>
                  </div>
                </div>
                <div class="form-row mb-3">
                  <div class="col-md-4">
                    <label for="desn" class="text-danger font-weight-bold">DESN - Lingua dritto</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desn" name="desn" value="<?php echo($scheda['da']['desn']); ?>" required>
                  </div>
                  <div class="col-md-4">
                    <label for="desr" class="text-danger font-weight-bold">DESR - Lingua rovescio</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="desr" name="desr" value="<?php echo($scheda['da']['desr']); ?>" required>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-8">
                    <label for="desd" class="text-danger font-weight-bold">DESD - Descrizione bene paramonetale</label>
                    <textarea class="form-control form-control-sm tab" data-table="da" id="desd" name="desd" rows="3" value="<?php echo($scheda['da']['desd']); ?>" required><?php echo($scheda['da']['desd']); ?></textarea>
                  </div>
                </div>
              </fieldset>
              <fieldset id="zecFieldset" class="mb-3">
                <legend class="text-marta font-weight-bold border-bottom mb-3">zec - zecca</legend>
                <div class="form-row mb-3">
                  <div class="col-md-4">
                    <label for="zec" class="">ZEC - zecca</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="da" id="zec" name="zec" value="<?php echo($scheda['da']['zec']); ?>">
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
    <script src="js/scheda-nu.js" charset="utf-8"></script>
  </body>
</html>
