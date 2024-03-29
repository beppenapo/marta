<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['classe'] > 1){ header("location:403.php");}
?>
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
      <div id="menuReport" class="bg-dark px-3">
        <div class="btn-group" role="group">
          <a id="add_report" class="btn btn-dark" href="report_add.php" data-toggle="tooltip" data-placement="bottom" title="Crea report.<br />I report saranno visibili a tutti gli amministratori"><i class="fas fa-tasks"></i> crea report</a>
        </div>
      </div>
    <?php } ?>
      <div class="container-fluid mt-5">
        <h3 class="border-bottom border-dark mb-3">Report disponibili</h3>
        <div class="row">
          <div class="col mb-5">
            <table id="dataTable" class="table table-sm table-striped table-bordered display compact" style="width:100%">
              <thead>
                <tr>
                  <th class="no-sort">lista report</th>
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
    <script src="js/report.js" charset="utf-8"></script>
  </body>
</html>
