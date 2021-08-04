<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/main.css">
  </head>
  <body>
    <input type="hidden" name="idScheda" value="<?php echo $_GET['get']; ?>">
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <div id="loadingDiv" class="flexDiv"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main>
      <?php if (isset($_SESSION['id'])) { ?>
      <div id="menuScheda" class="bg-dark px-3">
        <div class="btn-group" role="group">
          <button id="aggiungi" type="button" class="btn btn-dark"aria-haspopup="true" aria-expanded="false"><i class="fas fa-plus"></i> aggiungi contributo</button>
          <a id="modifica" class="btn btn-dark" href="bibliografia_mod.php?mod=<?php echo $_GET['get']; ?>"><i class="fas fa-edit"></i> modifica</a>
          <button id="eliminaScheda" name="biblioDel" type="button" class="btn btn-dark"><i class="fas fa-times"></i> elimina</button>
        </div>
      </div>
    <?php } ?>
      <div class="container-fluid mt-5">
        <h3 id="title" class="border-bottom border-dark mb-5"></h3>
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="card" id="scheda-card">
              <ul class="list-group list-group-flush">
                <li class="list-group-item" id="idScheda">
                  <span class="font-weight-bold d-inline-block align-top">Id:</span>
                  <span class="d-inline-block"></span>
                </li>
                <li class="list-group-item" id="tipo">
                  <span class="font-weight-bold d-inline-block align-top">Tipologia:</span>
                  <span class="d-inline-block"></span>
                </li>
                <li class="list-group-item" id="autore">
                  <span class="font-weight-bold d-inline-block align-top">Autore:</span>
                  <span class="d-inline-block"></span>
                </li>
                <li class="list-group-item" id="altri_autori">
                  <span class="font-weight-bold d-inline-block align-top">Altri autori:</span>
                  <span class="d-inline-block"></span>
                </li>
                <li class="list-group-item" id="curatore">
                  <span class="font-weight-bold d-inline-block align-top">A cura di:</span>
                  <span class="d-inline-block"></span>
                </li>
                <li class="list-group-item" id="anno">
                  <span class="font-weight-bold d-inline-block align-top">Anno:</span>
                  <span class="d-inline-block"></span>
                </li>
                <li class="list-group-item" id="editore">
                  <span class="font-weight-bold d-inline-block align-top">Editore:</span>
                  <span class="d-inline-block"></span>
                </li>
                <li class="list-group-item" id="luogo">
                  <span class="font-weight-bold d-inline-block align-top">Luogo:</span>
                  <span class="d-inline-block"></span>
                </li>
                <li class="list-group-item" id="isbn">
                  <span class="font-weight-bold d-inline-block align-top">ISBN:</span>
                  <span class="d-inline-block"></span>
                </li>
                <li class="list-group-item" id="url">
                  <span class="font-weight-bold d-inline-block align-top">Link risorsa:</span>
                  <span class="d-inline-block"><a href="" target="_blank" data-toggle="tooltip" title="il link alla risorsa aprirà una nuova scheda del browser"></a></span>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card mb-3" id="contributi">
              <div class="card-header bg-white">
                <h6 class="font-weight-bold">articoli correlati<span class="badge badge-warning float-right" id="totContrib"></span></h6>
              </div>
              <div class="card-body"></div>
              <ul class="list-group list-group-flush"></ul>
            </div>
            <div class="card" id="schede">
              <div class="card-header bg-white">
                <h6 class="font-weight-bold">schede correlate <span class="badge badge-warning float-right" id="totSchede"></span></h6>
              </div>
              <div class="card-body"></div>
              <ul class="list-group list-group-flush"></ul>
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
