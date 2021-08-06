<?php
session_start();
if (!isset($_SESSION['id'])){ header("location:login.php");}
require 'vendor/autoload.php';
use \Marta\Biblio;
$biblio = new Biblio();
$scheda = $biblio->getScheda($_GET['mod']);
$scheda = $scheda['scheda'];
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
            <h3 class="border-bottom">Modifica record bibliografico</h3>
            <small class="text-danger font-weight-bold d-block">* Campi obbligatori</small>
          </div>
        </div>
        <form id="modBiblioForm" autocomplete="off">
          <input type="hidden" name="idScheda" value="<?php echo $_GET['mod']; ?>">
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">SCHEDA BIBLIOGRAFIA</legend>
              <div class="row mb-3">
                <div class="col-md-3">
                  <label for="tipo" class="text-danger font-weight-bold">BIBF - Tipo pubblicazione</label>
                  <select class="form-control form-control-sm" id="tipo" name="tipo" required >
                    <option value="">-- seleziona tipologia --</option>
                    <?php foreach ($biblio->listaTipo() as $item) {
                      $selected = $scheda['tipoid'] == $item['id'] ? 'selected' : '';
                      echo "<option value='".$item['id']."' ".$selected.">".$item['value']."</option>";
                    } ?>
                  </select>
                </div>
                <div class="col-md-9">
                  <label for="titolo" class="text-danger font-weight-bold">BIBG - Titolo</label>
                  <input type="text" class="form-control form-control-sm tab" id="titolo" name="titolo" value="<?php echo $scheda['titolo']; ?>" placeholder="Inserisci titolo" required>
                </div>
              </div>
              <div class="form-row mb-3">
                <div class="col-md-4">
                  <label for="autore" class="text-danger font-weight-bold">BIBA - Autore</label>
                  <input type="text" class="form-control form-control-sm tab" id="autore" name="autore" value="<?php echo $scheda['autore']; ?>" placeholder="Cognome Nome, Cognome Nome ..." required>
                </div>
                <div class="col-md-4">
                  <label for="altri_autori">BIBA - Altri autori</label>
                  <input type="text" class="form-control form-control-sm tab" id="altri_autori" name="altri_autori" placeholder="Cognome Nome, Cognome Nome, ..." value="<?php echo $scheda['altri_autori']; ?>">
                </div>
                <div class="col-md-4">
                  <label for="curatore">BIBC - Curatori</label>
                  <input type="text" class="form-control form-control-sm tab" id="curatore" name="curatore" value="<?php echo $scheda['curatore']; ?>" placeholder="Cognome Nome, Cognome Nome, ...">
                </div>
              </div>
              <div class="form-row mb-3">
                <div class="col-md-4">
                  <label for="editore">BIBZ - Editore</label>
                  <input type="text" class="form-control form-control-sm tab" id="editore" name="editore" value="<?php echo $scheda['editore']; ?>" placeholder="nome editore">
                </div>
                <div class="col-md-3">
                  <label for="anno">BIBD - Anno di edizione</label>
                  <input type="number" step="1" min="1400" class="form-control form-control-sm tab" id="anno" name="anno" value="<?php echo $scheda['anno']; ?>" placeholder="es. 2020">
                </div>
                <div class="col-md-5">
                  <label for="luogo">BIBL - Luogo di edizione</label>
                  <input type="text" class="form-control form-control-sm tab" id="luogo" name="luogo" value="<?php echo $scheda['luogo']; ?>" placeholder="indicare il luogo di edizione">
                </div>
              </div>
              <div class="form-row mb-3">
                <div class="col-md-6">
                  <label for="isbn" data-toggle="tooltip" title="L'ISBN è formato da un codice di 13 o 10 cifre, suddivise in 5 parti dai trattini di divisione. Possono anche essere inseriti codici di 9 cifre anteponendo uno 0 alla sequenza.<br />Esempi validi:<br />ISBN 978-0-596-52068-7<br />ISBN-13: 978-0-596-52068-7<br />978 0 596 52068 7<br />9780596520687<br />ISBN-10 0-596-52068-9<br />0-596-52068-9"><i class="fas fa-info-circle"></i> ISBN</label>
                  <input type="text" class="form-control form-control-sm tab" id="isbn" name="isbn" value="<?php echo $scheda['isbn']; ?>" pattern="^(?:ISBN(?:-1[03])?:? )?(?=[0-9X]{10}$|(?=(?:[0-9]+[- ]){3})[- 0-9X]{13}$|97[89][0-9]{10}$|(?=(?:[0-9]+[- ]){4})[- 0-9]{17}$)(?:97[89][- ]?)?[0-9]{1,5}[- ]?[0-9]+[- ]?[0-9]+[- ]?[0-9X]$">
                </div>
                <div class="col-md-6">
                  <label for="url" data-toggle="tooltip" title="se il record bibliografico è disponibile on-line, indicare il link alla risorsa. Per evitare errori di battitura si consiglia di copiare il link direttametne dalla pagina della risorsa e incollarla nel campo sottostante"><i class="fas fa-info-circle"></i> BIBW - Indirizzo web (Url)</label>
                  <input type="url" class="form-control form-control-sm tab" id="url" name="url" value="<?php echo $scheda['url']; ?>" placeholder="inserire link completo, es: http://www.sito.com">
                </div>
              </div>

            </fieldset>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-6">
                <button type="submit" class="btn btn-sm btn-marta tastischeda" name="submit" id="submit">salva dati</button>
                <a href="biblioView.php?get=<?php echo $_GET['mod']; ?>" class="btn btn-sm btn-outline-secondary">annulla modifica</a>
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
    <script src="js/biblio_mod.js" charset="utf-8"></script>
  </body>
</html>
