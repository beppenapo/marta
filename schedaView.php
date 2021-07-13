<?php
require("api/php/schedaView.php");
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/scheda.css">
    <style media="screen">
      body>main{padding-top: 60px !important;}
      #menuScheda button{padding: 0.63rem .75rem;}
    </style>
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <div id="loadingDiv" class="flexDiv invisible"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main>
      <?php if (isset($_SESSION['id'])) { ?>
      <div id="menuScheda" class="bg-dark px-3">
        <div class="btn-group" role="group">
          <div class="btn-group" role="group">
            <button id="aggiungi" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-plus"></i> aggiungi</button>
            <div class="dropdown-menu" aria-labelledby="aggiungi">
              <a class="dropdown-item" href="#" title="aggiungi fonte bibliografica<br><br>Ricorda che per chiudere la scheda devi aggiungere almeno 1 riferimento bibliografico" data-toggle="tooltip" data-placement="right">bibliografia</a>
              <a class="dropdown-item" href="#" title="aggiungi fotografia<br><br>Ricorda che per chiudere la scheda devi aggiungere almeno 2 foto" data-toggle="tooltip" data-placement="right">foto</a>
              <a class="dropdown-item" href="#" title="aggiungi documentazione grafica come tavole, disegni ecc." data-toggle="tooltip" data-placement="right">grafica</a>
              <a class="dropdown-item" href="#" title="aggiungi fonte video" data-toggle="tooltip" data-placement="right">video</a>
              <a class="dropdown-item" href="#" title="aggiungi fonte audio" data-toggle="tooltip" data-placement="right">audio</a>
              <a class="dropdown-item" href="#" title="aggiungi fonti e documenti" data-toggle="tooltip" data-placement="right">fonti e documenti</a>
              <a class="dropdown-item" href="#" title="aggiungi modelli 3d" data-toggle="tooltip" data-placement="right">3d</a>
            </div>
          </div>
          <button id="duplica" name="duplica" type="button" class="btn btn-dark"><i class="fas fa-copy"></i> duplica</button>
          <button id="chiudi" name="chiudi" type="button" class="btn btn-dark"><i class="fas fa-clipboard-check"></i> chiudi</button>
          <button id="elimina" name="elimina" type="button" class="btn btn-dark"><i class="fas fa-times"></i> elimina</button>
        </div>
      </div>
    <?php } ?>
    <div class="container-fluid mt-5">
      <div class="row">
        <div class="col">
          <h3 class="border-bottom border-dark mb-3"><?php echo $scheda['scheda']['titolo']; ?></h3>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <?php print_r($scheda); ?>
        </div>
      </div>
    </div>
    </main>
    <?php require('assets/toast.html'); ?>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/schedaView.js" charset="utf-8"></script>
  </body>
</html>
