<?php
session_start();
if (!isset($_SESSION['id'])){ header("location:login.php");}
require 'vendor/autoload.php';
use \Marta\Biblio;
$biblio = new Biblio();
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
              <div class="form-row mb-3">
                <div class="col-md-3">
                  <label for="tipo" class="text-danger font-weight-bold">Tipo pubblicazione</label>
                  <select class="form-control form-control-sm" id="tipo" name="tipo" required >
                    <option value="">-- seleziona tipologia --</option>
                    <?php foreach ($biblio->listaTipo() as $item) { echo "<option value='".$item['id']."'>".$item['value']."</option>"; } ?>
                  </select>
                </div>
                <div class="col-md-9">
                  <label for="titolo" class="text-danger font-weight-bold">Titolo</label>
                  <input type="text" class="form-control form-control-sm tab" id="titolo" name="titolo" value="" placeholder="Inserisci titolo" required>
                </div>
              </div>
              <div class="form-row mb-3">
                <div class="col-md-4">
                  <label for="autore" class="text-danger font-weight-bold">Autore</label>
                  <input type="text" class="form-control form-control-sm tab" id="autore" name="autore" value="" placeholder="Cognome Nome" required>
                </div>
                <div class="col-md-4">
                  <label for="altri_autori">Altri autori</label>
                  <input type="text" class="form-control form-control-sm tab" id="altri_autori" name="altri_autori" placeholder="Cognome Nome, Cognome Nome, ..." value="">
                </div>
                <div class="col-md-4">
                  <label for="curatore">A cura di</label>
                  <input type="text" class="form-control form-control-sm tab" id="curatore" name="curatore" value="">
                </div>
              </div>
              <div class="form-row mb-3">
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
              <div class="form-row mb-3">
                <div class="col-md-6">
                  <label for="isbn">ISBN</label>
                  <input type="text" class="form-control form-control-sm tab" id="isbn" name="isbn" value="">
                </div>
                <div class="col-md-6">
                  <label for="url" data-toggle="tooltip" title="se il record bibliografico è disponibile on-line, indicare il link alla risorsa. Per evitare errori di battitura si consiglia di copiare il link direttametne dalla pagina della risorsa e incollarla nel campo sottostante"><i class="fas fa-info-circle"></i> Url</label>
                  <input type="url" class="form-control form-control-sm tab" id="url" name="url" value="" placeholder="inserire link completo, es: http://www.sito.com">
                </div>
              </div>
              <?php if(isset($_GET['sk'])){ ?>
              <legend class="text-marta font-weight-bold border-bottom mb-3">Dati reperto</legend>
              <div class="form-row">
                <input type="hidden" name="scheda" value="<?php echo $_GET['sk']; ?>">
                <div class="col-md-4">
                  <label for="pagine">Inserisci le pagine di riferimento</label>
                  <input type="text" class="form-control form-control-sm" id="pagine" name="pagine" value="" placeholder="es. 1-5, 8, 9 ecc.">
                </div>
                <div class="col-md-4">
                  <label for="figure">Inserisci le figure o le tavole di riferimento</label>
                  <input type="text" class="form-control form-control-sm" id="figure" name="figure" value="" placeholder="es. 1-5, 8, 9 ecc.">
                </div>
                <div class="col-md-4">
                  <label for="livello" class="font-weight-bold text-danger">Seleziona il tipo di bibliografia</label>
                  <select class="form-control form-control-sm" name="livello" id="livello" required>
                    <option value="">-- seleziona record --</option>
                    <?php foreach ($biblio->listaLivello() as $item) { echo "<option value='".$item['id']."'>".$item['value']."</option>"; } ?>
                  </select>
                </div>
              </div>
              <?php } ?>
            </fieldset>
          </div>
          <div class="form-group">
            <div class="form-row">
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
