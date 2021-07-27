<?php require 'api/php/biblioScheda.php'; ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/scheda.css">
  </head>
  <body>
    <input type="hidden" name="idScheda" value="<?php echo $_GET['sk']; ?>">
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <!-- <div id="loadingDiv" class="flexDiv"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div> -->
    <main class="">
      <div class="container">
        <h3 id="title" class="border-bottom border-dark">Aggiungi bibliografia alla scheda "<?php echo $scheda['scheda']['titolo']; ?>"</h3>
        <small class="text-danger font-weight-bold d-block mb-5">* Campi obbligatori</small>
        <form>
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">Aggiungi bibliografia alla scheda</legend>
              <div class="form-row mb-3">
                <div class="col-12">
                  <label for="biblio" class="font-weight-bold text-danger">Seleziona un record bibliografico tra quelli presenti in lista</label>
                  <select class="form-control form-control-sm" name="biblio" id="biblio" required>
                    <option value="" selected disabled>-- seleziona record --</option>
                    <?php foreach ($biblioList as $item) { echo "<option value='".$item['id']."'>".$item['autore']. ", ".$item['anno']. ", ".$item['titolo']."</option>"; } ?>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-4">
                  <label for="pagine">Inserisci le pagine di riferimento</label>
                  <input type="text" class="form-control form-control-sm" id="pagine" name="pagine" value="" placeholder="es. 1-5, 8, 9 ecc.">
                </div>
                <div class="col-md-4">
                  <label for="figure">Inserisci le figure o le tavole di riferimento</label>
                  <input type="text" class="form-control form-control-sm" id="figure" name="figure" value="" placeholder="es. 1-5, 8, 9 ecc.">
                </div>
                <div class="col-md-4">
                  <label for="livello">Seleziona il tipo di bibliografia</label>
                  <select class="form-control form-control-sm" name="livello" id="livello">
                    <option value="">-- seleziona record --</option>
                    <?php foreach ($biblio->listaLivello() as $item) { echo "<option value='".$item['id']."'>".$item['value']."</option>"; } ?>
                  </select>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-12">
                <button type="submit" class="btn btn-sm btn-marta" name="submit">salva dati</button>
                <a href="schedaView.php?get=<?php echo $_GET['sk'];?>" class="btn btn-sm btn-secondary">annulla inserimento</a>
              </div>
            </div>
          </div>
        </form>
        <div class="row">
          <div class="col text-center">
            <hr>
            <p class="font-weight-bold"><i class="fas fa-info-circle"></i> Se il record bibliografico non è presente in lista, utilizza il pulsante per associare un nuovo authority file alla scheda</p>
            <a href="bibliografia_add.php?sk=<?php echo $_GET['sk'] ?>" class="btn btn-sm btn-primary">aggiungi bibliografia</a>
          </div>
        </div>
      </div>
    </main>
    <?php require('assets/toast.html'); ?>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/biblioScheda.js" charset="utf-8"></script>
  </body>
</html>
