<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style media="screen">
      label.btn-outline-secondary{background-color: #fff;}
      label.btn-outline-secondary:hover{background-color: #6c757d;}
    </style>
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <?php require("assets/loading.html"); ?>
    <main class="">
      <div class="container-fluid">
        <h3 class="border-bottom border-dark mb-3">Archivio schede</h3>
        <div class="bg-light p-4 rounded border mb-4">
          <div id="filtriRicerca">
          <div class="row">
            <div class="col">
              <label>dati principali</label>
              <nav>
                <div class="btn-toolbar mb-3" role="toolbar">
                  <div class="btn-group btn-group-sm btn-group-toggle mr-2" role="group" data-toggle="buttons">
                    <label class="btn btn-outline-secondary">
                      <input type="radio" class="filtro" name="tsk" value="1" id="ra"> RA
                    </label>
                    <label class="btn btn-outline-secondary">
                      <input type="radio" class="filtro" name="tsk" value="2" id="nu"> NU
                    </label>
                  </div>
                  <div class="input-group input-group-sm mr-2" role="group">
                    <select class="form-control filtro" name="schedatore">
                      <option value="" selected>--schedatore--</option>
                      <option value="pippo">pippo</option>
                      <option value="pluto">pluto</option>
                      <option value="paperino">paperino</option>
                    </select>
                  </div>
                  <div class="input-group input-group-sm mr-2" role="group">
                    <select class="form-control filtro" name="statoScheda">
                      <option value="" selected>--stato scheda--</option>
                      <option value="chiusa">in lavorazione</option>
                      <option value="verificata">da verificare</option>
                      <option value="inviata">da inviare</option>
                      <option value="accettata">in attesa di accettazione ICCD</option>
                      <option value="chiusa">iter completo, scheda chiuda</option>
                    </select>
                  </div>
                  <div class="input-group input-group-sm mr-2">
                    <input type="text" class="form-control filtro" name="catalogo" placeholder="numero catalogo">
                  </div>
                  <div class="input-group input-group-sm">
                    <input type="text" class="form-control filtro" name="inventario" placeholder="inventario museo">
                  </div>
                </div>
              </nav>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <label>caratteristiche reperto</label>
              <nav>
                <div class="btn-toolbar mb-3" role="toolbar">
                  <div class="input-group input-group-sm mr-2" role="group">
                    <select class="form-control filtro" name="cls">
                      <option value="" selected>--CLS - categoria--</option>
                    </select>
                  </div>
                  <div class="input-group input-group-sm mr-2" role="group">
                    <select class="form-control filtro" name="ogtd">
                      <option value="" selected>--OGTD - definizione--</option>
                    </select>
                  </div>
                  <div class="input-group input-group-sm mr-2" role="group">
                    <select class="form-control filtro" name="materia">
                      <option value="" selected>--materia--</option>
                    </select>
                  </div>
                  <div class="input-group input-group-sm mr-2" role="group">
                    <select class="form-control filtro" name="tecnica">
                      <option value="" selected>--tecnica--</option>
                    </select>
                  </div>
                  <div class="input-group input-group-sm">
                    <input type="text" class="form-control filtro" name="titolo" placeholder="titolo">
                  </div>
                </div>
              </nav>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <label>cronologia</label>
              <nav>
                <div class="btn-toolbar mb-3" role="toolbar">
                  <div class="input-group input-group-sm mr-2" role="group">
                    <select class="form-control filtro" name="dtzgi">
                      <option value="" selected>--Cronologia iniziale--</option>
                    </select>
                  </div>
                  <div class="input-group input-group-sm mr-2" role="group">
                    <select class="form-control filtro" name="dtzgf">
                      <option value="" selected>--Cronologia finale--</option>
                    </select>
                  </div>
                </div>
              </nav>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <label>ubicazione interna</label>
              <nav>
                <div class="btn-toolbar mb-3" role="toolbar">
                  <div class="input-group input-group-sm mr-2" role="group">
                    <select class="form-control filtro" name="piano">
                      <option value="" selected disabled>--piano--</option>
                      <option value="-1">Deposito</option>
                      <option value="0">Piano terra</option>
                      <option value="1">Primo piano</option>
                      <option value="3">Terzo piano</option>
                    </select>
                  </div>
                  <div class="input-group input-group-sm mr-2" role="group">
                    <select class="form-control filtro" name="sala">
                      <option value="" selected>--sala--</option>
                    </select>
                  </div>
                  <div class="input-group input-group-sm mr-2" role="group">
                    <select class="form-control filtro" name="contenitore">
                      <option value="" selected>--scaffale/vetrina--</option>
                    </select>
                  </div>
                  <div class="input-group input-group-sm">
                    <input type="text" class="form-control filtro" name="cassetta" placeholder="cassetta">
                  </div>
                </div>
              </nav>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <label>localizzazione geografica</label>
              <nav>
                <div class="btn-toolbar mb-3" role="toolbar">
                  <div class="input-group input-group-sm mr-2" role="group">
                    <select class="form-control filtro" name="comune">
                      <option value="" selected>--comune--</option>
                    </select>
                  </div>
                  <div class="input-group input-group-sm mr-2" role="group">
                    <select class="form-control filtro" name="via">
                      <option value="" selected>--via--</option>
                    </select>
                  </div>
                </div>
              </nav>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <button type="button" class="btn btn-sm btn-marta" name="cerca">cerca</button>
              <button type="button" class="btn btn-sm btn-marta" name="button">annulla filtri</button>
            </div>
          </div>
          </div>
          <div class="row">
            <div class="col">
              <div id="filtri" class="my-3 d-flex justify-content-center"></div>
            </div>
          </div>
        </div>

        <div class="row" id="tableWrap">
          <div class="col mb-5">
            <table id="dataTable" class="table table-sm table-striped table-bordered display compact" style="width:100%">
              <caption>La tabella mostra le schede chiuse</caption>
              <thead>
                <tr>
                  <th>NCTN</th>
                  <th>Inventario</th>
                  <th>Tipo</th>
                  <th>Stato</th>
                  <th>Titolo</th>
                  <th>OGTD</th>
                  <th>Cronologia</th>
                  <th>Piano</th>
                  <th>Sala</th>
                  <th>Vetrina/Scaffale</th>
                  <th>Cassetta</th>
                  <th>Comune</th>
                  <th>Via</th>
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
