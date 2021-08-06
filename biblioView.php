<?php
session_start();
require 'vendor/autoload.php';
use \Marta\Biblio;
$obj = new Biblio();
$biblio = $obj->getScheda($_GET['get']);
$dati = $biblio['scheda'];
$schede = $biblio['schede'];
$contributi = $biblio['contributi'];
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/main.css">
    <style media="screen"> body>main {padding-top: 60px !important;} </style>
  </head>
  <body>
    <input type="hidden" name="idScheda" value="<?php echo $_GET['get']; ?>">
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <main>
      <?php if (isset($_SESSION['id'])) { ?>
      <div id="menuScheda" class="bg-dark px-3">
        <div class="btn-group" role="group">
          <a href="contributo_add.php?auth=<?php echo $_GET['get']; ?>" class="btn btn-dark"><i class="fas fa-file-alt"></i> aggiungi contributo</a>
          <a class="btn btn-dark" href="bibliografia_mod.php?mod=<?php echo $_GET['get']; ?>"><i class="fas fa-edit"></i> modifica</a>
          <button id="eliminaScheda" name="biblioDel" type="button" class="btn btn-dark"><i class="fas fa-times"></i> elimina</button>
        </div>
      </div>
    <?php } ?>
      <div class="container-fluid mt-5">
        <h3 id="title" class="border-bottom border-dark mb-5"><?php echo $dati['titolo']; ?></h3>
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="card" id="scheda-card">
              <div class="card-header bg-marta">
                <h6 class="font-weight-bold">Dati principali</h6>
              </div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item" id="idScheda">
                  <span class="font-weight-bold d-inline-block align-top">Id:</span>
                  <span class="d-inline-block"><?php echo $dati['id']; ?></span>
                </li>
                <li class="list-group-item" id="tipo">
                  <span class="font-weight-bold d-inline-block align-top">Tipologia:</span>
                  <span class="d-inline-block"><?php echo $dati['tipo']; ?></span>
                </li>
                <li class="list-group-item" id="autore">
                  <span class="font-weight-bold d-inline-block align-top">Autore:</span>
                  <span class="d-inline-block"><?php echo $dati['autore']; ?></span>
                </li>
                <li class="list-group-item" id="altri_autori">
                  <span class="font-weight-bold d-inline-block align-top">Altri autori:</span>
                  <span class="d-inline-block"><?php echo $dati['altri_autori']; ?></span>
                </li>
                <li class="list-group-item" id="curatore">
                  <span class="font-weight-bold d-inline-block align-top">A cura di:</span>
                  <span class="d-inline-block"><?php echo $dati['curatore']; ?></span>
                </li>
                <li class="list-group-item" id="anno">
                  <span class="font-weight-bold d-inline-block align-top">Anno:</span>
                  <span class="d-inline-block"><?php echo $dati['anno']; ?></span>
                </li>
                <li class="list-group-item" id="editore">
                  <span class="font-weight-bold d-inline-block align-top">Editore:</span>
                  <span class="d-inline-block"><?php echo $dati['editore']; ?></span>
                </li>
                <li class="list-group-item" id="luogo">
                  <span class="font-weight-bold d-inline-block align-top">Luogo:</span>
                  <span class="d-inline-block"><?php echo $dati['luogo']; ?></span>
                </li>
                <li class="list-group-item" id="isbn">
                  <span class="font-weight-bold d-inline-block align-top">ISBN:</span>
                  <span class="d-inline-block"><?php echo $dati['isbn']; ?></span>
                </li>
                <li class="list-group-item" id="url">
                  <span class="font-weight-bold d-inline-block align-top">Link risorsa:</span>
                  <span class="d-inline-block"><a href="<?php echo $dati['url']; ?>" target="_blank" data-toggle="tooltip" title="il link alla risorsa aprirà una nuova scheda del browser"><?php echo $dati['url']; ?></a></span>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card mb-3" id="contributi">
              <div class="card-header bg-marta">
                <h6 class="font-weight-bold">contributi correlati<span class="badge badge-light float-right" id="totContrib"><?php echo count($contributi); ?></span></h6>
              </div>
              <ul class="list-group list-group-flush">
                <?php
                if (count($contributi) == 0) { echo '<li class="list-group-item">Non sono presenti contributi correlati</li>'; }
                foreach ($contributi as $i) {
                  echo '<li class="list-group-item"><a href="contribView.php?get='.$i['id'].'">'.$i['autore'].', '.$i['titolo'].'</a></li>';
                } ?>
              </ul>
            </div>
            <div class="card" id="schede">
              <div class="card-header bg-marta">
                <h6 class="font-weight-bold">schede correlate <span class="badge badge-light float-right" id="totSchede"><?php echo count($schede); ?></span></h6>
              </div>
              <ul class="list-group list-group-flush">
                <?php
                if (count($schede)==0) {
                  echo '<li class="list-group-item">Non sono presenti schede correlate</li>';
                }
                foreach ($schede as $i) {
                  $tipo = $i['tsk'] == 1 ? 'RA' : 'NU';
                  $testo = $i['nctn']." - ".$tipo." - ".$i['titolo'] ;
                  echo '<li class="list-group-item"><a href="schedaView.php?get='.$i['scheda'].'">'.$testo.'</a></li>';
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
    <script src="js/biblioView.js" charset="utf-8"></script>
  </body>
</html>
