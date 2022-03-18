<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="css/home.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <div id="loadingDiv" class="flexDiv"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main class="bg-light">
      <div id="countDownWrap" class="mb-5 py-3 bg-white border-top border-bottom">
        <div class="info text-marta"><h1>IL MUSEO MArTA 3.0</h1></div>
        <div class="">On line tra ...</div>
        <div id="countdown"></div>
        <div class="info">
          <p>Progetto per la catalogazione, digitalizzazione 2D-3D e realizzazione di un archivio digitale per il patrimonio museale del Museo Archeologico Nazionale di Taranto</p>
        </div>
      </div>
      <div class="container-fluid">
        <?php if (isset($_SESSION['id'])) { ?>
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
              <div class="card" id="utenti">
                <div class="card-header bg-white font-weight-bold">
                  <p class="card-title m-0">Utenti</p>
                </div>
                <div class="card-body">
                  <table id="dataTable" class="table table-striped table-bordered">
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

    <div id="elDiv" class="modal fade" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body bg-success text-white">
            <h1 class="d-5">Ciao patatina!<br>Sei stata bravissima!!!!</h1>
          </div>
        </div>
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
    <script src="js/home.js" charset="utf-8"></script>
    <script src="js/countdown.js" charset="utf-8"></script>
  </body>
</html>
