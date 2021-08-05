<?php
session_start();
if (!isset($_SESSION['id'])){ header("location:login.php");}
<<<<<<< HEAD
if (isset($_SESSION['id']) && $_SESSION['classe'] == 3){ header("location:index.php");}
=======
if (isset($_SESSION['id']) && $_SESSION['classe'] == 3){ header("location:home.php");}
>>>>>>> first commit
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="css/usr.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php require('assets/mainMenu.php'); ?>
    <div id="loadingDiv" class="flexDiv"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main>
      <div class="container-fluid">
        <div class="row mb-3">
          <div class="col">
            <ul class="nav">
              <li class="nav-item">
                <a class="nav-link btn btn-dark btn-marta btn-sm border-0" href="usrAdd.php" title="aggiungi un nuovo utente"><i class="fas fa-plus"></i> nuovo utente</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="row">
          <div class="col mb-5">
            <table id="dataTable" class="table table-striped table-bordered display compact" style="width:100%">
              <thead>
                <tr>
                  <th>Utente</th>
                  <th>Email</th>
                  <th class="no-sort">Telefono</th>
                  <th>Classe</th>
                  <th class="no-sort">Attivo</th>
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
    <script src="js/usr.js" charset="utf-8"></script>
  </body>
</html>
