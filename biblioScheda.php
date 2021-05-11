<?php
session_start();
if (!isset($_SESSION['id'])){ header("location:login.php");}
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
    <main class="">
      <div class="container">
        <h3 id="title" class="border-bottom border-dark mb-5"></h3>
        <div class="row">
          <div class="col-md-6">
            <div class="card" id="scheda-card">
              <ul class="list-group list-group-flush">
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
                <li class="list-group-item" id="titolo_raccolta">
                  <span class="font-weight-bold d-inline-block align-top">Presente in:</span>
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
            <div class="card" id="schede">
              <div class="card-header bg-white">
                <h6 class="font-weight-bold">schede correlate</h6>
              </div>
              <ul class="list-group list-group-flush"></ul>
            </div>
          </div>
        </div>
        <nav class="navbar navbar-expand navbar-light bg-light mb-5">
          <a class="btn btn-sm btn-marta mr-2" href="biblioMod.php?mod=<?php echo $_GET['get']; ?>">modifica</a>
          <button type="button" class="btn btn-sm btn-danger" name="biblioDel">elimina</button>
        </nav>
      </div>
    </main>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/biblioScheda.js" charset="utf-8"></script>
  </body>
</html>
