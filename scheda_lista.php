<?php
session_start();
if (!isset($_SESSION['id'])){ header("location:login.php");}
require_once('funzioni.php');
$title = 'Gestione schede ';
if (isset($_GET['tipo'])) {
  $tipo = filtraInt($_GET['tipo']);
  $tipoSigla = $tipo == 1 ? 'RA' : 'NU';
  $title .= $tipoSigla;
}

?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/scheda.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php require('assets/mainMenu.php'); ?>
    <div id="loadingDiv" class="flexDiv invisible"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main>
      <div class="container">

    	<br /><br />
        <div class="row mb-4">
          <div class="col">
              <a href="scheda.php?tipo=<?php echo $tipo; ?>&act=add" target="_self" title="Nuova Scheda <?php echo $tipoSigla; ?>"><button type="button" class="btn btn-sm btn-marta">Nuova Scheda <?php echo $tipoSigla; ?></button></a>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col">
            <h3 class="border-bottom"><?php echo $title; ?></h3>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col">
          	<label for="txtSearch">Cerca </label>
          	<input type="text" class="form-control form-control-sm tab" id="txtSearch" name="txtSearch" onKeyUp="listaScheda(<?php echo $tipo; ?>);" value="" />
          </div>
        </div>
        <div class="row lista_header">
          <div class="col">
            DENOMINAZIONE GENERICA<span class="lista_compilatore">COMPILATORE</span>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col divListaScheda" id="divListaScheda"></div>
        </div>

      </div>

    </main>
    <?php require('assets/toast.html'); ?>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script>
      let tipoScheda = <?php echo $tipo; ?>;
      let action = "";
    </script>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/scheda.js" charset="utf-8"></script>
    <script>window.onload = function(){listaScheda(tipoScheda)};</script>
  </body>
</html>
