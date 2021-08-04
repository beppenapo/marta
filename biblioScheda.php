<?php require 'api/php/biblioScheda.php'; ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/scheda.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <main class="">
      <div class="container">
        <h3 id="title" class="border-bottom border-dark">Aggiungi bibliografia alla scheda "<?php echo $scheda['scheda']['titolo']; ?>"</h3>
        <small class="text-danger font-weight-bold d-block mb-5">* Campi obbligatori</small>
        <form>
          <input type="hidden" name="scheda" value="<?php echo $_GET['sk']; ?>">
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">Aggiungi bibliografia alla scheda</legend>
              <div class="form-row mb-3">
                <div class="col-md-6">
                  <label for="biblio" class="font-weight-bold text-danger">Seleziona authority file</label>
                  <select class="form-control form-control-sm" name="biblio" id="biblio" required>
                    <option value="" selected disabled>-- seleziona record --</option>
                    <?php foreach ($biblioList as $item) { echo "<option value='".$item['id']."' data-tipo='".$item['tipo_id']."'>".$item['autore']. ", ".$item['anno']. ", ".$item['titolo']."</option>"; } ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <div id="contribList">
                    <label for="contributo">Seleziona contributo raccolta</label>
                    <select class="form-control form-control-sm" name="contributo" id="contributo">
                      <option value="">-- seleziona record --</option>
                      <?php foreach ($contribList as $item) { echo "<option value='".$item['id']."'>".$item['autore']. ", ".$item['titolo']."</option>"; } ?>
                    </select>
                  </div>
                  <div id="noContribList" class="text-danger text-center">
                    <p>Il record bibliografico selezionato non presenta contributi associati!</p>
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-4">
                  <label for="livello" class="font-weight-bold text-danger">Seleziona il tipo di bibliografia</label>
                  <select class="form-control form-control-sm" name="livello" id="livello" required>
                    <option value="">-- seleziona record --</option>
                    <?php foreach ($biblio->listaLivello() as $item) { echo "<option value='".$item['id']."'>".$item['value']."</option>"; } ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="pagine">Inserisci le pagine di riferimento</label>
                  <input type="text" class="form-control form-control-sm" id="pagine" name="pagine" value="" placeholder="es. 1-5, 8, 9 ecc.">
                </div>
                <div class="col-md-4">
                  <label for="figure">Inserisci le figure o le tavole di riferimento</label>
                  <input type="text" class="form-control form-control-sm" id="figure" name="figure" value="" placeholder="es. 1-5, 8, 9 ecc.">
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
            <p class="font-weight-bold"><i class="fas fa-info-circle"></i> Se il record bibliografico non è presente in lista <a href="bibliografia_add.php">aggiungi un nuovo authority file</a>, o seleziona una raccolta già presente nell'<a href="bibliografia.php">archivio bibliografico</a> per aggiungere un nuovo contributo</p>
          </div>
        </div>
      </div>

      <div role="alert alert-dismissible fade show" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false">
        <div class="toast-header">
          <span id="headerTxt" class="mr-auto">Risultato query</span>
          <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="toast-body text-white text-center">
          <div class="toast-body-msg mb-5"></div>
          <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-light btn-sm" name="continua">aggiungi ancora</button>
            <button type="button" class="btn btn-light btn-sm" data-dismiss="toast" name="viewRec"></button>
          </div>
        </div>
      </div>
    </main>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/biblioScheda.js" charset="utf-8"></script>
  </body>
</html>
