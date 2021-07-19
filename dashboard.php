<?php
  session_start();
  if (!isset($_SESSION['id'])){ header("location:login.php");}
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
  </head>
  <body>
    <input type="hidden" name="classe" value="<?php echo $_SESSION['classe']; ?>">
    <input type="hidden" name="utente" value="<?php echo $_SESSION['id']; ?>">
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <div id="loadingDiv" class="flexDiv"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main class="bg-light">
      <div class="container-fluid">
        <!-- <?php require("assets/stat.html"); ?> -->
      </div>
      <div class="container-fluid">
        <div class="row mb-3">
          <div class="col-lg-4">
            <div class="card" id="comunicazioni">
              <div class="card-header bg-white font-weight-bold">
                <p class="card-title m-0">Comunicazioni progetto</p>
              </div>
              <div class="list-group liste"></div>
              <div class="card-footer">
                <button type="button" class="btn btn-sm btn-outline-marta" name="addComunicazioneBtn">aggiungi comunicazione</button>
              </div>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="card" id="schede">
              <div class="card-header bg-white font-weight-bold">
                <p class="card-title m-0">Stato schede</p>
              </div>
              <div class="card-body">
                <table id="dataTableScheda" class="dataTable table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>NCTN</th>
                      <th>Titolo</th>
                      <th class="no-sort">Chiusa</th>
                      <th class="no-sort">Verificata</th>
                      <th class="no-sort">Inviata</th>
                      <th class="no-sort">Accettata</th>
                      <th class="no-sort">Data</th>
                      <?php if($_SESSION['classe'] !==3){ echo "<th>Compilatore</th>"; } ?>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <?php if ($_SESSION['classe']!==3) { ?>
        <div class="row mb-3">
          <div class="col-lg-8">
            <div class="card" id="utenti">
              <div class="card-header bg-white font-weight-bold">
                <p class="card-title m-0">Utenti</p>
              </div>
              <div class="card-body">
                <table id="dataTable" class="dataTable table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Utente</th>
                      <th>Email</th>
                      <th class="no-sort">Telefono</th>
                      <th class="no-sort"></th>
                      <th class="no-sort"></th>
                      <th class="no-sort"></th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
              <div class="card-footer">
                <a href="usrAdd.php" class="btn btn-sm btn-outline-marta"><i class="fas fa-plus"></i> nuovo utente</a>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
    </main>

    <div id="addComunicazioneDiv" class="modal fade" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form name="addComunicazioneForm">
          <input type="hidden" name="idComunicazione" value="">
          <div class="modal-content">
            <div class="modal-body">
              <div class="form-row">
                <div class="col">
                  <label for="testo">Testo comunicazione</label>
                  <textarea name="testo" id="testo" class="form-control form-control-sm" rows="8" cols="80" required></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">annulla</button>
              <button type="submit" class="btn btn-primary btn-sm" name="saveComunicazione">salva testo</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="toast toastAddComunicazione bg-success text-white font-weight-bold hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000"><div class="toast-body"></div></div>

    <div class="toast toastDelComunicazione hide" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
      <div class="toast-header">
        <strong class="mr-auto">Cancella comunicazione</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="toast-body">
        <p>Sei sicuro di voler eliminare la comunicazione?</p>
        <p>Ricorda che i dati non sono recuperabili.</p>
        <div class="d-block w-100 mx-auto mt-3 border-top pt-3">
          <button type="button" class="btn btn-sm btn-danger" name="delNotesBtn" value="">conferma eliminazione</button>
          <button type="button" class="btn btn-sm btn-secondary" name="annullaNotesBtn" data-dismiss="toast" aria-label="Close">annulla azione</button>
        </div>
      </div>
    </div>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" charset="utf-8"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js" charset="utf-8"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js" charset="utf-8"></script>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/dashboard.js" charset="utf-8"></script>
  </body>
</html>
