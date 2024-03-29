<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style media="screen"> body>main {padding-top: 60px !important;} </style>
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <div id="loadingDiv" class="flexDiv"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main>
      <?php if (isset($_SESSION['id'])) { ?>
      <div id="menuScheda" class="bg-dark px-3">
        <div class="btn-group" role="group">
          <a id="add_auth" class="btn btn-dark" href="bibliografia_add.php" data-toggle="tooltip" data-placement="bottom" title="aggiungi una scheda bibliografica agli authority file"><i class="fas fa-folder-open"></i> crea authority file</a>
          <a id="add_contr" class="btn btn-dark" href="contributo_add.php" data-toggle="tooltip" data-placement="bottom" title="aggiungi un nuovo articolo/contributo ad un volume presente nel database degli authority file"><i class="fas fa-file-alt"></i> aggiungi contributo</a>
        </div>
      </div>
    <?php } ?>
      <div class="container-fluid mt-5">
        <h3 class="border-bottom border-dark mb-3">Elenco risorse bibliografiche</h3>
        <div class="row">
          <div class="col mb-5">
            <table id="dataTable" class="table table-sm table-striped table-bordered display compact" style="width:100%">
              <thead>
                <tr>
                  <th class="no-sort">id</th>
                  <th>Tipo</th>
                  <th>Autore</th>
                  <th>Titolo</th>
                  <th class="no-sort">Schede</th>
                  <th class="no-sort"></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" charset="utf-8"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js" charset="utf-8"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js" charset="utf-8"></script>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/bibliografia.js" charset="utf-8"></script>
  </body>
</html>
