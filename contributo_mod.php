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
            <?php print_r($contributo); ?>
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
                  <?php if(!isset($_GET['auth'])){ ?>
                  <label for="tipo" class="text-danger font-weight-bold">Seleziona il volume di cui fa parte l'articolo</label>
                  <select class="form-control form-control-sm" id="tipo" name="raccolta" required >
                    <option value="">-- seleziona un volume --</option>
                    <?php foreach ($biblio->listaAuth() as $item) { echo "<option value='".$item['id']."'>".$item['titolo'].", ".$item['autore']." (".$item['anno'].")</option>"; } ?>
                  </select>
                  <?php }else{ ?>
                  <label class="text-danger font-weight-bold">Il contributo verrà aggiunto al seguente volume</label>
                  <p><?php echo($auth['scheda']['titolo'].", ".$auth['scheda']['autore']." (".$auth['scheda']['anno'].")"); ?></p>
                  <input type="hidden" name="raccolta" value="<?php echo $_GET['auth']; ?>">
                  <?php } ?>
                </div>
              </div>
              <div class="form-row mb-3">
                <div class="col">
                  <label for="titolo" class="text-danger font-weight-bold">Titolo contributo</label>
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
                  <label for="curatore">Pagine dell'articolo</label>
                  <input type="text" class="form-control form-control-sm tab" id="pag" name="pag" placeholder="es. pagg 1-30" value="">
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-6">
                <button type="submit" class="btn btn-sm btn-marta tastischeda" name="submit" id="submit">salva dati</button>
                <a href="bibliografia.php" class="btn btn-sm btn-outline-secondary">annulla operazione</a>
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
    <script src="js/contrib_add.js" charset="utf-8"></script>
  </body>
</html>
