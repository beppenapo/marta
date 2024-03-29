<?php
session_start();
require 'vendor/autoload.php';
use \Marta\Scheda;
$obj = new Scheda();
$filtriScheda = $obj->filtriScheda();
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="css/schede.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <?php require("assets/loading.html"); ?>
    <main class="">
      <div class="container-fluid">
        <h3 class="border-bottom border-dark mb-3">Archivio schede</h3>
        <div class="row">
          <div class="col-4 text-right">
            <img src="img/searchIcon.png" class="img-fluid" alt="">
          </div>
          <div class="col-5">
            <h3 class="">Cerca schede</h3>
            Cerca all'interno dell'archivio utilizzando i seguenti filtri di ricerca.<br />
            Il risultato dovrà soddisfare tutti i criteri di ricerca inseriti pertanto è importante utilizzare i filtri con criterio altrimenti il risultato potrebbe essere impreciso.<br />
            I record trovati possono essere ulteriormente filtrati utilizzando il campo di ricerca aggiuntivo che comparirà in alto a destra della tabella.
          </div>
        </div>
        <div class="bg-light p-4 rounded border mb-4">
          <div id="filtriRicerca">
            <div class="row">
              <div class="col">
                <?php require("assets/filtriRicerca.php"); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <input type="hidden" name="sessId" value="<?php echo $_SESSION['id']; ?>">
              <input type="hidden" name="sessUsr" value="<?php echo $_SESSION['utente']; ?>">
              <div id="filtriWrap" class="my-3">
                <div id="filtriTitle">Filtri attivi</div>
                <div id="filtri" class=" d-flex justify-content-start"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="row" id="tableWrap">
          <div class="col mb-5">
            <table id="dataTable" class="table table-sm table-striped table-bordered display compact" style="width:100%">
              <caption class="font-weight-bold">Se non sono stati selezionati dei filtri la tabella mostra di default le tue schede</caption>
              <thead>
                <tr>
                  <th>NCTN</th>
                  <th>Inventario</th>
                  <!-- <th>Tipo</th> -->
                  <th>Stato</th>
                  <th>Titolo</th>
                  <th>OGTD</th>
                  <!-- <th>Materia</th> -->
                  <!-- <th>Tecnica</th> -->
                  <th>Cronologia</th>
                  <th>Piano</th>
                  <th>Sala</th>
                  <th>Vetrina/Scaffale</th>
                  <th>Cassetta</th>
                  <!-- <th>Comune</th> -->
                  <!-- <th>Via</th> -->
                  <th>Operatore</th>
                  <th class="no-sort all"></th>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot></tfoot>
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
    <script src="js/schede.js" charset="utf-8"></script>
  </body>
</html>
