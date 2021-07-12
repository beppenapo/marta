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
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <div id="loadingDiv" class="flexDiv"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main class="bg-light">
      <div class="container-fluid">
        <div class="row mb-3">
          <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card">
              <div class="card-body">
                <div class="border-bottom">schede RA/NU</div>
                <div class="display-4 text-success text-center" id="numschede"></div>
                <div class="d-flex justify-content-between">
                  <div class="progress w-50 mr-1">
                    <div class="progress-bar bg-success"  id="raBar" role="progressbar" aria-valuemin="0" aria-valuemax="100">RA</div>
                  </div>
                  <div class="progress w-50">
                    <div class="progress-bar" id="nuBar" role="progressbar" aria-valuemin="0" aria-valuemax="100">NU</div>
                  </div>
                </div>
                <div class="d-flex justify-content-between">
                  <small>tot.: <span id="totSchede"></span></small>
                  <small><span id="percSchedeOk"></span>% completate</small>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card">
              <div class="card-body">
                <div class="border-bottom">fotografie</div>
                <div class="display-4 text-danger text-center" id="numFoto"></div>
                <div class="progress">
                  <div class="progress-bar bg-danger" id="fotoBar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between">
                  <small>tot.: 80000</small>
                  <small><span id="percFoto"></span>% completate</small>
                </div>
                <div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card">
              <div class="card-body">
                <div class="border-bottom">foto stereo</div>
                <div class="display-4 text-primary text-center" id="numStereo"></div>
                <div class="progress">
                  <div class="progress-bar bg-primary" role="progressbar" id="stereoBar" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between">
                  <small>tot.: 5000</small>
                  <small><span id="stereoPerc"></span>% completate</small>
                </div>
                <div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card card-sm">
              <div class="card-body">
                <div class="border-bottom">modelli 3d</div>
                <div class="display-4 text-warning text-center" id="numModelli"></div>
                <div class="progress">
                  <div class="progress-bar bg-warning" id="3dBar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between">
                  <small>tot.: 110</small>
                  <small><span id="perc3d"></span>% completati</small>
                </div>
                <div>
                </div>
              </div>
            </div>
          </div>
        </div>
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
            <?php if ($_SESSION['classe']!==3) { ?>
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
          <?php } ?>
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
