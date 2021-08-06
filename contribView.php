<?php require 'api/php/contribView.php'; ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/main.css">
    <style media="screen">
    body>main {padding-top: 60px !important;}
    </style>
  </head>
  <body>
    <input type="hidden" name="idContrib" value="<?php echo $_GET['get']; ?>">
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <main>
      <?php if (isset($_SESSION['id'])) { ?>
      <div id="menuScheda" class="bg-dark px-3">
        <div class="btn-group" role="group">
          <a id="modifica" class="btn btn-dark" href="contributo_mod.php?mod=<?php echo $_GET['get']; ?>"><i class="fas fa-edit"></i> modifica</a>
          <button id="eliminaScheda" name="biblioDel" type="button" class="btn btn-dark"><i class="fas fa-times"></i> elimina</button>
        </div>
      </div>
    <?php } ?>
      <div class="container-fluid mt-5">
        <h3 id="title" class="border-bottom border-dark mb-5"><?php echo($contributo['contrib_tit']); ?></h3>
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="card" id="scheda-card">
              <div class="card-header bg-marta">
                <h6 class="font-weight-bold">Dati principali</h6>
              </div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <span class="font-weight-bold d-inline-block align-top">Id:</span>
                  <span class="d-inline-block"><?php echo $contributo['contrib_id']; ?></span>
                </li>
                <li class="list-group-item">
                  <span class="font-weight-bold d-inline-block align-top">Titolo:</span>
                  <span class="d-inline-block"><?php echo $contributo['contrib_tit']; ?></span>
                </li>
                <li class="list-group-item">
                  <span class="font-weight-bold d-inline-block align-top">Autore:</span>
                  <span class="d-inline-block"><?php echo $contributo['contrib_aut']; ?></span>
                </li>
                <li class="list-group-item">
                  <span class="font-weight-bold d-inline-block align-top">Altri autori:</span>
                  <span class="d-inline-block"><?php echo $contributo['contrib_alt']; ?></span>
                </li>
                <li class="list-group-item">
                  <span class="font-weight-bold d-inline-block align-top">Pagine contributo:</span>
                  <span class="d-inline-block"><?php echo $contributo['contrib_pagine']; ?></span>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card mb-3" id="contributi">
              <div class="card-header bg-marta">
                <h6 class="font-weight-bold">Presente nel volume</h6>
              </div>
              <div class="card-body">
                <a href="biblioView.php?get=<?php echo $contributo['id']; ?>"><?php echo $contributo['autore'].", ".$contributo['titolo']; ?></a>
              </div>
            </div>
            <div class="card" id="schede">
              <div class="card-header bg-marta">
                <h6 class="font-weight-bold">schede correlate <span class="badge badge-light float-right"><?php echo count($schede); ?></span></h6>
              </div>
              <ul class="list-group list-group-flush">
                <?php foreach ($schede as $scheda) {
                  $tipo = $scheda['tsk'] == 1 ? 'RA' : 'NU';
                  $testo = $scheda['nctn'].' - '.$tipo.' - '.$scheda['titolo'];
                  echo "<li class='list-group-item'><a href='schedaView.php?get=".$scheda['scheda']."'>".$testo."</a></li>";
                } ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </main>
    <?php require('assets/toast.html'); ?>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/contribView.js" charset="utf-8"></script>
  </body>
</html>
