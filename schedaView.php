<?php require("api/php/schedaView.php"); ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <link rel="stylesheet" href="css/schedaView.css">
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
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right">og - oggetto</a>
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
              <li class="list-group-item"><span>NCTN - Numero catalogo:</span><span class="font-weight-bold"><?php echo $scheda['scheda']['nctn']; ?></span></li>
              <li class="list-group-item"><span>Num.Inv. MarTA:</span><span class="font-weight-bold"><?php echo $scheda['scheda']['inv']; ?></span></li>
              <li class="list-group-item"><span>TSK - Tipo scheda:</span><span class="font-weight-bold"><?php echo $scheda['scheda']['tsk']; ?></span></li>
              <li class="list-group-item"><span>LIR - Livello ricerca:</span><span class="font-weight-bold"><?php echo $scheda['scheda']['lir']; ?></span></li>
              <li class="list-group-item"><span>NCTR - Codice Regione:</span><span class="font-weight-bold">16 [Puglia]</span></li>
              <li class="list-group-item"><span>ESC - Ente schedatore:</span><span class="font-weight-bold">M325</span></li>
              <li class="list-group-item"><span>ECP - Ente competente:</span><span class="font-weight-bold">M325</span></li>
              <li class="list-group-item"><span>CMPN - Compilatore:</span><span class="font-weight-bold"><?php echo $scheda['scheda']['cmpn']; ?></span></li>
              <li class="list-group-item"><span>CMPD - Data:</span><span class="font-weight-bold"><?php echo $scheda['scheda']['cmpd']; ?></span></li>
              <li class="list-group-item"><span>FUR - Funzionario:</span><span class="font-weight-bold"><?php echo $scheda['scheda']['fur']; ?></span></li>
            </ul>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="ogFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">og - oggetto</legend>
            <ul class="list-group list-group-flush">
            <?php if ($scheda['scheda']['tskid']==1) { ?>
              <li class="list-group-item"><span>CLS - Categoria liv.1:</span><span class="font-weight-bold"><?php echo $scheda['og']['cls1']; ?></span></li>
              <li class="list-group-item"><span>CLS - Categoria liv.2:</span><span class="font-weight-bold"><?php echo $scheda['og']['cls2']; ?></span></li>
              <li class="list-group-item"><span>CLS - Categoria liv.3:</span><span class="font-weight-bold"><?php echo $scheda['og']['cls3']; ?></span></li>
              <li class="list-group-item"><span>OGTD - Definizione:</span><span class="font-weight-bold"><?php echo $scheda['og']['cls4']; ?></span></li>
              <li class="list-group-item"><span>OGTD - Specifiche:</span><span class="font-weight-bold"><?php echo $scheda['og']['cls5']; ?></span></li>
              <li class="list-group-item"><span>OGTT - Tipologia:</span><span class="font-weight-bold"><?php echo $scheda['og']['ogtt']; ?></span></li>
            <?php }else { ?>
              <li class="list-group-item"><span>OGTD - Definizione:</span><span class="font-weight-bold"><?php echo $scheda['og']['ogtd']; ?></span></li>
              <li class="list-group-item"><span>OGR - Disponibilità</span><span class="font-weight-bold"><?php echo $scheda['og']['ogr']; ?></span></li>
              <li class="list-group-item"><span>OGTT - Tipologia:</span><span class="font-weight-bold"><?php echo $scheda['og']['ogtt']; ?></span></li>
              <li class="list-group-item"><span>OGTH - Funzione:</span><span class="font-weight-bold"><?php echo $scheda['og']['ogth']; ?></span></li>
              <li class="list-group-item"><span>OGTL - Legenda tipo:</span><span class="font-weight-bold"><?php echo $scheda['og']['ogtl']; ?></span></li>
              <li class="list-group-item"><span>OGTO - Nominale:</span><span class="font-weight-bold"><?php echo $scheda['og']['ogto']; ?></span></li>
              <li class="list-group-item"><span>OGTS - Specifiche:</span><span class="font-weight-bold"><?php echo $scheda['og']['ogts']; ?></span></li>
              <li class="list-group-item"><span>OGTR - Serie:</span><span class="font-weight-bold"><?php echo $scheda['og']['ogtr']; ?></span></li>
            <?php } ?>
            </ul>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="lcFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">LC - LOCALIZZAZIONE GEOGRAFICO-AMMINISTRATIVA</legend>
            <?php print_r($scheda['gp']); ?>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="ubFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">UB - DATI PATRIMONIALI</legend>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="gpFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">GP - GEOREFERENZIAZIONE TRAMITE PUNTO</legend>
            <input type="hidden" name="mapInit" value="<?php echo count($scheda['gp']); ?>">
            <?php if(count($scheda['gp'])==0){echo $noData;}else{ ?>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><span>GPL - Tipo di localizzazione:</span><span class="font-weight-bold"><?php echo $scheda['gp']['gpl']; ?></span></li>
              <li class="list-group-item"><span>GPP - Sistema di riferimento:</span><span class="font-weight-bold"><?php echo $scheda['gp']['gpp']." (EPSG:".$scheda['gp']['epsg'].")"; ?></span></li>
              <li class="list-group-item"><span>GPDPX - Coordinata X:</span><span class="font-weight-bold"><?php echo $scheda['gp']['gpdpx']; ?></span></li>
              <li class="list-group-item"><span>GPDPY - Coordinata Y:</span><span class="font-weight-bold"><?php echo $scheda['gp']['gpdpy']; ?></span></li>
              <li class="list-group-item"><span>GPM - Metodo:</span><span class="font-weight-bold"><?php echo $scheda['gp']['gpm']; ?></span></li>
              <li class="list-group-item"><span>GPT - Tecnica:</span><span class="font-weight-bold"><?php echo $scheda['gp']['gpt']; ?></span></li>
              <li class="list-group-item"><span>GPBT - Data:</span><span class="font-weight-bold"><?php echo $scheda['gp']['gpbt']; ?></span></li>
              <li class="list-group-item"><span>GPBB - Descrizione sintetica:</span><span class="font-weight-bold"><?php echo $scheda['gp']['gpbb']; ?></span></li>
            </ul>
            <input type="hidden" name="gpdpx" value="<?php echo $scheda['gp']['gpdpx'] ?>">
            <input type="hidden" name="gpdpy" value="<?php echo $scheda['gp']['gpdpy'] ?>">
            <input type="hidden" name="epsg" value="<?php echo $scheda['gp']['epsg'] ?>">
          <?php } ?>
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
            <div id="mappa" class="bg-marta"></div>
          </fieldset>
        </div>
      </div>
    </div>
    </main>
    <?php require('assets/toast.html'); ?>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/schedaView.js" charset="utf-8"></script>
  </body>
</html>
