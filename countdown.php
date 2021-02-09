<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/countdown.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <main>
      <div class="container-fluid">
        <div class="info"><h1>IL MUSEO MArTA 3.0</h1></div>
        <div class="">On line tra ...</div>
        <div id="countdown"></div>
        <div class="info">
          <p>Progetto per la catalogazione, digitalizzazione 2D-3D e realizzazione di un archivio digitale per il patrimonio museale del Museo Archeologico Nazionale di Taranto</p>
        </div>
      </div>
    </main>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/countdown.js" charset="utf-8"></script>
    <script src="js/test.js" charset="utf-8"></script>
  </body>
</html>
