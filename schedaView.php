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
      .list-group-item{background: none !important;}
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
          <div class="btn-group" role="group">
            <button id="modifica" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-edit"></i> modifica</button>
            <div class="dropdown-menu" aria-labelledby="modifica">
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right">titolo e codici</a>
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
          <h3 class="border-bottom border-dark mb-3"><?php echo $scheda['scheda']['nctn']. " - ". $scheda['scheda']['titolo']; ?></h3>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <fieldset class="bg-light rounded border p-3 mb-3" id="cdFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">cd - codici</legend>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">NCTN - Numero catalogo: <span class="font-weight-bold"><?php echo $scheda['scheda']['nctn']; ?></span></li>
              <li class="list-group-item">TSK - Tipo scheda: <span class="font-weight-bold"><?php echo $scheda['scheda']['tsk']; ?></span></li>
              <li class="list-group-item">LIR - Livello ricerca: <span class="font-weight-bold"><?php echo $scheda['scheda']['lir']; ?></span></li>
              <li class="list-group-item">NCTR - Codice Regione: <span class="font-weight-bold">16 [Puglia]</span></li>
              <li class="list-group-item">ESC - Ente schedatore: <span class="font-weight-bold">M325</span></li>
              <li class="list-group-item">ECP - Ente competente: <span class="font-weight-bold">M325</span></li>
              <li class="list-group-item">CMPN - Compilatore: <span class="font-weight-bold"><?php echo $scheda['scheda']['cmpn']; ?></span></li>
              <li class="list-group-item">CMPD - Data: <span class="font-weight-bold"><?php echo $scheda['scheda']['cmpd']; ?></span></li>
              <li class="list-group-item">FUR - Funzionario responsabile: <span class="font-weight-bold"><?php echo $scheda['scheda']['fur']; ?></span></li>
            </ul>
            <?php print_r($scheda); ?>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="ogFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">og - oggetto</legend>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="lcFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">LC - LOCALIZZAZIONE GEOGRAFICO-AMMINISTRATIVA</legend>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="ubFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">UB - DATI PATRIMONIALI</legend>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="gpFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">GP - GEOREFERENZIAZIONE TRAMITE PUNTO</legend>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="reFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">RE- MODALITÀ DI REPERIMENTO</legend>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="dtFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">DT - CRONOLOGIA</legend>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="mtFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">MT - DATI TECNICI</legend>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="daFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">DA - DATI ANALITICI</legend>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="coFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">CO - CONSERVAZIONE</legend>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="adFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">AD - ACCESSO AI DATI</legend>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="anFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">AN - ANNOTAZIONI</legend>
          </fieldset>
        </div>
        <div class="col-md-6">
          <fieldset class="bg-light rounded border p-3 mb-3" id="multimediaFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">immagini - documenti - multimedia</legend>
          </fieldset>
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
