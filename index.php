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
        <?php require("assets/stat.html"); ?>
      </div>
      <div class="fotoWrap"></div>
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
