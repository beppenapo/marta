<?php
require("api/php/home.php");
?>
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
    <!-- <div id="loadingDiv" class="flexDiv"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div> -->
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
        <div class="row mb-3">
          <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card">
              <div class="card-body">
                <div class="border-bottom d-flex justify-content-between">
                  <span>schede RA/NU</span>
                  <a href=""><i class="fas fa-arrow-right fa-fw" aria-hidden="true"></i></a>
                </div>
                <div class="display-4 text-success text-center" id="numschede"></div>
                <div class="d-flex justify-content-between">
                  <div class="progress w-50 mr-1">
                    <div class="progress-bar bg-success"  id="raBar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo (int)$obj->raPerc; ?>%">RA</div>
                  </div>
                  <div class="progress w-50">
                    <div class="progress-bar" id="nuBar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo (int)$obj->nuPerc; ?>%">NU</div>
                  </div>
                </div>
                <div class="d-flex justify-content-between">
                  <small>tot.: <?php echo (int)$obj->ra+(int)$obj->nu; ?></small>
                  <small><?php echo (int)$obj->raPerc+(int)$obj->nuPerc; ?>% completate</small>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card">
              <div class="card-body">
                <div class="border-bottom d-flex justify-content-between">
                  <span>fotografie</span>
                  <a href=""><i class="fas fa-arrow-right fa-fw" aria-hidden="true"></i></a>
                </div>
                <div class="display-4 text-danger text-center" id="numFoto"></div>
                <div class="progress">
                  <div class="progress-bar bg-danger" id="fotoBar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo (int)$obj->fotoPerc; ?>%"></div>
                </div>
                <div class="d-flex justify-content-between">
                  <small>tot.: <?php echo (int)$obj->totfoto; ?></small>
                  <small><?php echo (int)$obj->fotoPerc; ?>% completate</small>
                </div>
                <div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card">
              <div class="card-body">
                <div class="border-bottom d-flex justify-content-between">
                  <span>foto stereo</span>
                  <a href=""><i class="fas fa-arrow-right fa-fw" aria-hidden="true"></i></a>
                </div>
                <div class="display-4 text-primary text-center" id="numStereo"></div>
                <div class="progress">
                  <div class="progress-bar bg-primary" role="progressbar" id="stereoBar" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo (int)$obj->stereoPerc; ?>%"></div>
                </div>
                <div class="d-flex justify-content-between">
                  <small>tot.: <?php echo (int)$obj->totstereo; ?></small>
                  <small><?php echo (int)$obj->stereoPerc; ?>% completate</small>
                </div>
                <div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card card-sm">
              <div class="card-body">
                <div class="border-bottom d-flex justify-content-between">
                  <span>modelli 3d</span>
                  <a href=""><i class="fas fa-arrow-right fa-fw" aria-hidden="true"></i></a>
                </div>
                <div class="display-4 text-warning text-center" id="numModelli"></div>
                <div class="progress">
                  <div class="progress-bar bg-warning" id="3dBar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo (int)$obj->modelliPerc; ?>%"></div>
                </div>
                <div class="d-flex justify-content-between">
                  <small>tot.: <?php echo (int)$obj->tot3d; ?></small>
                  <small><?php echo (int)$obj->modelliPerc; ?>% completati</small>
                </div>
                <div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="fotoWrap">
        <?php
        
        ?>
      </div>
    </main>
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
