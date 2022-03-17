<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="css/main.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <?php require("assets/loading.html"); ?>
    <input type="hidden" name="filtroOperatore" value="<?php echo $_POST['operatore'] ?>">
    <main class="">
      <div class="container-fluid">
        <h3 class="border-bottom border-dark mb-3">Archivio schede</h3>
        <div class="row">
          <div class="col">
            <div id="filtri" class="my-3 d-flex justify-content-center"></div>
          </div>
        </div>
        <div class="row">
          <div class="col mb-5">
            <table id="dataTable" class="table table-sm table-striped table-bordered display compact" style="width:100%">
              <caption>La tabella mostra le schede chiuse</caption>
              <thead>
                <tr>
                  <th>NCTN</th>
                  <th>Tipo</th>
                  <th>Titolo</th>
                  <th>OGTD</th>
                  <th>Materia</th>
                  <th>Cronologia</th>
                  <th>Piano</th>
                  <th>Sala</th>
                  <th class="no-sort all"></th>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot></tfoot>
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
    <script src="js/schede.js" charset="utf-8"></script>
  </body>
</html>
