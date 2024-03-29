<?php
session_start();
if (!isset($_SESSION['id'])){ header("location:login.php");}
require 'vendor/autoload.php';
use \Marta\Biblio;
$obj = new Biblio();
$contributo = $obj->getContrib($_GET['mod']);
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
    <main>
      <div class="container">
        <div class="row mb-4">
          <div class="col">
            <h3 class="border-bottom">Modifica contributo</h3>
            <small class="text-danger font-weight-bold d-block">* Campi obbligatori</small>
          </div>
        </div>
          <form id="addBiblioForm" autocomplete="off">
            <?php
              echo "<input type='hidden' name='contributo' value='".$_GET['mod']."'>";
             ?>
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">SCHEDA ARTICOLO</legend>
              <div class="form-row mb-3">
                <div class="col">
                  <label for="tipo" class="text-danger font-weight-bold">Seleziona il volume di cui fa parte l'articolo</label>
                  <select class="form-control form-control-sm" id="tipo" name="raccolta" required >
                    <option value="">-- seleziona un volume --</option>
                    <?php foreach ($obj->listaAuth() as $item) {
                      $selected = $contributo['id'] == $item['id'] ? 'selected' : '';
                      echo "<option value='".$item['id']."' ".$selected.">".$item['titolo'].", ".$item['autore']." (".$item['anno'].")</option>"; }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-row mb-3">
                <div class="col">
                  <label for="titolo" class="text-danger font-weight-bold">Titolo contributo</label>
                  <input type="text" class="form-control form-control-sm tab" id="titolo" name="titolo" value="<?php echo $contributo['contrib_tit'] ?>" placeholder="Inserisci titolo" required>
                </div>
              </div>
              <div class="form-row mb-3">
                <div class="col-md-4">
                  <label for="autore" class="text-danger font-weight-bold">Autore</label>
                  <input type="text" class="form-control form-control-sm tab" id="autore" name="autore" value="<?php echo $contributo['contrib_aut'] ?>" placeholder="Cognome Nome" required>
                </div>
                <div class="col-md-4">
                  <label for="altri_autori">Altri autori</label>
                  <input type="text" class="form-control form-control-sm tab" id="altri_autori" name="altri_autori" placeholder="Cognome Nome, Cognome Nome, ..." value="<?php echo $contributo['contrib_alt'] ?>">
                </div>
                <div class="col-md-4">
                  <label for="curatore">Pagine dell'articolo</label>
                  <input type="text" class="form-control form-control-sm tab" id="pag" name="pag" placeholder="es. pagg 1-30" value="<?php echo $contributo['contrib_pagine'] ?>">
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-6">
                <button type="submit" class="btn btn-sm btn-marta tastischeda" name="submit" id="submit">salva dati</button>
                <a href="contribView.php?get=<?php echo $_GET['mod']; ?>" class="btn btn-sm btn-outline-secondary">annulla operazione</a>
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
    <script src="js/contrib_mod.js" charset="utf-8"></script>
  </body>
</html>
