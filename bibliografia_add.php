<?php
session_start();
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
            <h3 class="border-bottom">Inserisci un nuovo record bibliografico</h3>
            <small class="text-danger font-weight-bold d-block">* Campi obbligatori</small>
          </div>
        </div>
        <form id="addBiblioForm" autocomplete="off">
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">SCHEDA BIBLIOGRAFIA</legend>
              <div class="row mb-3">
                <div class="col-md-3">
                  <label for="tipo" class="text-danger font-weight-bold">Tipo pubblicazione</label>
                  <select class="form-control form-control-sm" id="tipo" name="tipo" required ></select>
                </div>
                <div class="col-md-5 raccoltaWrap">
                  <label for="titolo_raccolta" class="text-danger font-weight-bold">Titolo raccolta</label>
                  <input type="text" class="form-control form-control-sm tab" id="titolo_raccolta" name="titolo_raccolta" title="specificare il titolo degli atti del convegno o della raccolta" value="">
                </div>
                <div class="col-md-4 raccoltaWrap">
                  <label for="curatore">A cura di</label>
                  <input type="text" class="form-control form-control-sm tab" id="curatore" name="curatore" value="">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col">
                  <label for="titolo" class="text-danger font-weight-bold">Titolo</label>
                  <input type="text" class="form-control form-control-sm tab" id="titolo" name="titolo" value="" placeholder="Inserisci titolo" required>
                </div>

              </div>

              <div class="row mb-3">
                <div class="col-md-5">
                  <label for="autore" class="text-danger font-weight-bold">Autore principale</label>
                  <input type="text" class="form-control form-control-sm tab" id="autore" name="autore" value="" placeholder="Cognome Nome" required>
                </div>
                <div class="col-md-7">
                  <label for="altri_autori">Altri autori</label>
                  <input type="text" class="form-control form-control-sm tab" id="altri_autori" name="altri_autori" placeholder="Cognome Nome, Cognome Nome, ..." value="">
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-5">
                  <label for="editore">Editore</label>
                  <input type="text" class="form-control form-control-sm tab" id="editore" name="editore" value="">
                </div>
                <div class="col-md-2">
                  <label for="anno">Anno</label>
                  <input type="number" step="1" min="1400" class="form-control form-control-sm tab" id="anno" name="anno" value="">
                </div>
                <div class="col-md-5">
                  <label for="luogo">Luogo</label>
                  <input type="text" class="form-control form-control-sm tab" id="luogo" name="luogo" value="">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="isbn">ISBN</label>
                  <input type="text" class="form-control form-control-sm tab" id="isbn" name="isbn" value="">
                </div>
                <div class="col-md-6">
                  <label for="url" data-toggle="tooltip" title="se il record bibliografico è disponibile on-line, indicare il link alla risorsa. Per evitare errori di battitura si consiglia di copiare il link direttametne dalla pagina della risorsa e incollarla nel campo sottostante"><i class="fas fa-info-circle"></i> Url</label>
                  <input type="url" class="form-control form-control-sm tab" id="url" name="url" value="" placeholder="inserire link completo, es: http://www.sito.com">
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-6">
                <button type="submit" class="btn btn-sm btn-marta tastischeda" name="submit" id="submit">salva dati</button>
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
    <script src="js/biblio_add.js" charset="utf-8"></script>
  </body>
</html>
