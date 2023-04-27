<?php require("api/php/schedaView.php"); ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/3dhop/3dhop.css"/>
    <link rel="stylesheet" href="css/schedaView.css">
  </head>
  <body>
    <?php
      $logged = $_SESSION['id'] ? true : false;
      echo "<input type='hidden' name='logged' value='".$logged."' />";
    ?>
    <input type="hidden" name="schedaId" value="<?php echo $_GET['get']; ?>">
    <input type="hidden" name="nctnId" value="<?php echo $scheda['scheda']['nctn']; ?>">
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <div id="loadingDiv" class="flexDiv invisible"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main>

      <?php if (isset($_SESSION['id']) && ($_SESSION['id'] == $scheda['scheda']['cmpid'] || $_SESSION['classe'] < 3)) { ?>
      <div id="menuScheda" class="bg-dark px-3">
        <div class="btn-group" role="group">
          <div class="btn-group <?php echo $modifica; ?>" role="group">
            <button id="aggiungi" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-plus"></i> aggiungi</button>
            <div class="dropdown-menu" aria-labelledby="aggiungi">
              <a class="dropdown-item" href="biblioScheda.php?sk=<?php echo $_GET['get']; ?>" title="aggiungi fonte bibliografica<br><br>Ricorda che per chiudere la scheda devi aggiungere almeno 1 riferimento bibliografico" data-toggle="tooltip" data-placement="right">bibliografia</a>
              <a class="dropdown-item" href="uploadFile.php?sk=<?php echo $_GET['get']; ?>&t=3" title="aggiungi fotografia<br><br>Ricorda che per chiudere la scheda devi aggiungere almeno 2 foto" data-toggle="tooltip" data-placement="right">foto</a>
              <a class="dropdown-item" href="#" title="aggiungi documentazione grafica come tavole, disegni ecc." data-toggle="tooltip" data-placement="right">grafica</a>
              <a class="dropdown-item" href="#" title="aggiungi fonte video" data-toggle="tooltip" data-placement="right">video</a>
              <a class="dropdown-item" href="#" title="aggiungi fonte audio" data-toggle="tooltip" data-placement="right">audio</a>
              <a class="dropdown-item" href="#" title="aggiungi fonti e documenti" data-toggle="tooltip" data-placement="right">fonti e documenti</a>
              <a class="dropdown-item" href="#" title="aggiungi modelli 3d" data-toggle="tooltip" data-placement="right">3d</a>
            </div>
          </div>
          <button name="modificaScheda" type="button" class="btn btn-dark" title="modifica scheda" data-toggle="tooltip" data-placement="bottom"><i class="fas fa-edit"></i> modifica</button>
          <button name="duplicaScheda" type="button" class="btn btn-dark" title="duplica scheda" data-toggle="tooltip" data-placement="bottom"><i class="fa-solid fa-clone"></i> duplica</button>
          <button name="cambiaStato" value="chiusa" type="button" class="btn btn-dark <?php echo $chiudi; ?>"><i class="fas fa-clipboard-check"></i> chiudi</button>
          <button name="cambiaStato" value="riapri" type="button" class="btn btn-dark <?php echo $riapri; ?>"><i class="fas fa-clipboard-check"></i> riapri</button>
          <button name="cambiaStato" value="verificata" type="button" class="btn btn-dark <?php echo $verifica; ?>"><i class="fas fa-clipboard-check"></i> verificata</button>
          <button name="cambiaStato" value="inviata" type="button" class="btn btn-dark <?php echo $invia; ?>"><i class="fas fa-clipboard-check"></i> inviata</button>
          <button name="cambiaStato" value="accettata" type="button" class="btn btn-dark <?php echo $accettata; ?>"><i class="fas fa-clipboard-check"></i> accettata</button>
          <button id="eliminaScheda" name="eliminaScheda" type="button" class="btn btn-dark <?php echo $modifica; ?>"><i class="fas fa-times"></i> elimina</button>
        </div>
      </div>
    <?php } ?>
    <div class="container-fluid mt-5">
      <div class="row">
        <div class="col text-center text-uppercase">
          <h3 class="border-bottom border-dark mb-3"><?php echo $titolo; ?></h3>
        </div>
      </div>
      <?php if(isset($_SESSION['id'])){?>
      <div class="row mt-3 mb-5">
        <div class="col text-center">
          <h5>Stato avanzamento scheda</h5>
          <small class="text-muted">Per chiudere la scheda devi inserire almeno 1 riferimento bibliografico, 2 immagini e 1 riferimento geografico</small>
          <div class="progress">
            <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <p id="msgStato" class="font-weight-bold mt-2"></p>
        </div>
      </div>
      <?php } ?>
      <div class="row">
        <div class="col-md-6">
          <fieldset class="bg-light rounded border p-3 mb-3" id="cdFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">cd - codici</legend>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">
                <span>NCTN - Numero catalogo:</span>
                <span><?php echo $scheda['scheda']['nctn']; ?></span>
              </li>
              <li class="list-group-item">
                <span>Num.Inv. MarTA:</span>
                <span><?php echo $inventario; ?></span>
              </li>
              <li class="list-group-item">
                <span>TSK - Tipo scheda:</span>
                <span class="font-weight-bold"><?php echo $scheda['scheda']['tsk']; ?></span>
                <input type="hidden" name="tsk" value="<?php echo $scheda['scheda']['tskid'] ?>">
              </li>
              <li class="list-group-item">
                <span>LIR - Livello ricerca:</span>
                <span><?php echo $scheda['scheda']['lir']; ?></span>
              </li>
              <li class="list-group-item">
                <span>NCTR - Codice Regione:</span>
                <span>16 [Puglia]</span>
              </li>
              <li class="list-group-item">
                <span>ESC - Ente schedatore:</span>
                <span>M325</span>
              </li>
              <li class="list-group-item">
                <span>ECP - Ente competente:</span>
                <span>M325</span>
              </li>
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
          <fieldset class="bg-light rounded border p-3 mb-3" id="geolocFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">geolocalizzazione generica</legend>
            <?php if(count((array)$scheda['geoloc'])==0){echo $noData;}else{ ?>
            <input type="hidden" name="id_comune" value="<?php echo $scheda['geoloc']['id_comune']; ?>">
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><span>Comune:</span><span class="font-weight-bold"><?php echo $scheda['geoloc']['comune']; ?></span></li>
              <li class="list-group-item"><span>Via:</span><span class="font-weight-bold"><?php echo $scheda['geoloc']['via']; ?></span></li>
              <li class="list-group-item"><span>Note:</span><span class="font-weight-bold"><?php echo $scheda['geoloc']['geonote']; ?></span></li>
            </ul>
            <?php } ?>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="gpFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">GP - GEOREFERENZIAZIONE TRAMITE PUNTO</legend>
            <?php if(count((array)$scheda['gp'])==0){echo $noData;}else{ ?>
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
          <fieldset class="bg-light rounded border p-3 mb-3" id="lcFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">LC - LOCALIZZAZIONE GEOGRAFICO-AMMINISTRATIVA</legend>
            <?php if(count((array)$scheda['lc'])==0){echo $noData;}else{ ?>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><span>PVCC - Comune:</span><span class="font-weight-bold"><?php echo $scheda['lc']['pvcc']; ?></span></li>
              <li class="list-group-item"><span>LDCN - Collocazione specifica (Denominazione):</span><span class="font-weight-bold"><?php echo $scheda['lc']['ldcn']; ?></span></li>
              <li class="list-group-item"><span>PIANO:</span><span class="font-weight-bold"><?php echo $scheda['lc']['piano'] == -1 ? 'Deposito': $scheda['lc']['piano']; ?></span></li>
              <li class="list-group-item"><span>SALA:</span><span class="font-weight-bold"><?php echo $scheda['lc']['sala']; ?></span></li>
              <?php if ($scheda['lc']['piano'] == -1) {?>
                <li class="list-group-item"><span>SCAFFALE:</span><span class="font-weight-bold"><?php echo $scheda['lc']['contenitore']; ?></span></li>
                <li class="list-group-item"><span>COLONNA:</span><span class="font-weight-bold"><?php echo $scheda['lc']['colonna']; ?></span></li>
                <li class="list-group-item"><span>RIPIANO:</span><span class="font-weight-bold"><?php echo $scheda['lc']['ripiano']; ?></span></li>
                <li class="list-group-item"><span>CASSETTA:</span><span class="font-weight-bold"><?php echo $scheda['lc']['cassetta']; ?></span></li>
              <?php }else{ ?>
                <li class="list-group-item"><span>VETRINA:</span><span class="font-weight-bold"><?php echo $scheda['lc']['contenitore']; ?></span></li>
              <?php } ?>
            </ul>
          <?php } ?>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="ubFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">UB - DATI PATRIMONIALI</legend>
            <?php if(count((array)$scheda['ub'])==0){echo $noData;}else{ ?>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><span>INVN - Inventario:</span><span class="font-weight-bold"><?php echo $scheda['ub']['invn']; ?></span></li>
                <li class="list-group-item"><span>STIS - Stima:</span><span class="font-weight-bold"><?php echo $scheda['ub']['stis']; ?></span></li>
                <li class="list-group-item"><span>STID - Anno stima:</span><span class="font-weight-bold"><?php echo $scheda['ub']['stid']; ?></span></li>
                <li class="list-group-item"><span>STIM - Motivo stima:</span><span class="font-weight-bold"><?php echo $scheda['ub']['stim']; ?></span></li>
              </ul>
            <?php } ?>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="reFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">RE- MODALITÀ DI REPERIMENTO</legend>
            <fieldset id="rcgFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">rcg - ricognizioni</legend>
              <?php if(count((array)$scheda['re']['rcg'])==0){echo $noData;}else{ ?>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><span>NUCN - Codice ICCD:</span><span class="font-weight-bold"><?php echo $scheda['re']['rcg']['nucn']; ?></span></li>
                <li class="list-group-item"><span>RCGA - Responsabile scientifico:</span><span class="font-weight-bold"><?php echo $scheda['re']['rcg']['rcga']; ?></span></li>
                <li class="list-group-item"><span>RCGD - Data:</span><span class="font-weight-bold"><?php echo $scheda['re']['rcg']['rcgd']; ?></span></li>
                <li class="list-group-item"><span>RCGZ - Specifiche:</span><span class="font-weight-bold"><?php echo $scheda['re']['rcg']['rcgz']; ?></span></li>
              </ul>
              <?php } ?>
            </fieldset>
            <fieldset id="dscFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">dsc - dati scavo</legend>
              <?php if(count((array)$scheda['re']['dsc'])==0){echo $noData;}else{ ?>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><span>NUCN - Codice ICCD:</span><span class="font-weight-bold"><?php echo $scheda['re']['dsc']['nucn']; ?></span></li>
                <li class="list-group-item"><span>SCAN - Denominazione dello scavo:</span><span class="font-weight-bold"><?php echo $scheda['re']['dsc']['scan']; ?></span></li>
                <li class="list-group-item"><span>DSCA - Responsabile scientifico:</span><span class="font-weight-bold"><?php echo $scheda['re']['dsc']['dsca']; ?></span></li>
                <li class="list-group-item"><span>DSCD - Data:</span><span class="font-weight-bold"><?php echo $scheda['re']['dsc']['dscd']; ?></span></li>
                <li class="list-group-item"><span>DSCN - Specifiche:</span><span class="font-weight-bold"><?php echo $scheda['re']['dsc']['dscn']; ?></span></li>
              </ul>
              <?php } ?>
            </fieldset>
            <fieldset id="ainFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">ain - altre indagini</legend>
              <?php if(count((array)$scheda['re']['ain'])==0){echo $noData;}else{ ?>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><span>AINT - Tipo:</span><span class="font-weight-bold"><?php echo $scheda['re']['ain']['aint']; ?></span></li>
                <li class="list-group-item"><span>AIND - Data:</span><span class="font-weight-bold"><?php echo $scheda['re']['ain']['aind']; ?></span></li>
                <li class="list-group-item"><span>AINS - Note:</span><span class="font-weight-bold"><?php echo $scheda['re']['ain']['ains']; ?></span></li>
              </ul>
              <?php } ?>
            </fieldset>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="dtFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">DT - CRONOLOGIA</legend>
            <fieldset id="dtzFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">DTZ - Cronologia generica</legend>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><span>DTZG - Fascia cronologica:</span><span class="font-weight-bold">
                  <?php
                    echo $scheda['dt']['dt']['ciid'] == $scheda['dt']['dt']['cfid'] ? $scheda['dt']['dt']['cf'] : $scheda['dt']['dt']['ci']." / ".$scheda['dt']['dt']['cf'];
                  ?>
                </span></li>
                <li class="list-group-item"><span>DTZS - Frazione cronologica:</span><span class=""><?php echo $scheda['dt']['dt']['dtzs'] ? $scheda['dt']['dt']['dtzs'] : ''; ?></span></li>
              </ul>
            </fieldset>
            <fieldset id="dtsFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">DTS - Cronologia specifica</legend>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><span>DTSI - Da:</span><span class=""><?php echo $scheda['dt']['dt']['dtsi'] ? $scheda['dt']['dt']['dtsi'] : ''; ?></span></li>
                <li class="list-group-item"><span>DTSF - A:</span><span class=""><?php echo $scheda['dt']['dt']['dtsf'] ? $scheda['dt']['dt']['dtsf'] : ''; ?></span></li>
              </ul>
            </fieldset>
            <fieldset id="dtmFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">DTM - Motivazione cronologia</legend>
              <ul class="list-group list-group-flush">
                <?php foreach ($scheda['dt']['dtm'] as $dtm) {
                  echo '<li class="list-group-item"><span>Motivazione:</span><span class="font-weight-bold">'.$dtm['dtm'].'</span></li>';
                } ?>
              </ul>
            </fieldset>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="mtFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">MT - DATI TECNICI</legend>
            <fieldset id="mtcFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">mtc - materia e tecnica</legend>
              <ul class="list-group list-group-flush">
                <?php foreach ($scheda['mt']['mtc'] as $mtc) {
                  echo '<li class="list-group-item"><span>Materia/Tecnica:</span><span class="font-weight-bold">'.$mtc['materia'].'/'.$mtc['tecnica'].'</span></li>';
                } ?>
              </ul>
            </fieldset>
            <fieldset id="misFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">mis - misure</legend>
              <?php if($scheda['mt']['mis']['misr']){ echo $noData;}else{ $m = $scheda['mt']['mis']; ?>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"><span>misa - altezza:</span><span class="font-weight-bold">
                    <?php echo $m['misa'] ? $m['misa']." cm" : '' ; ?>
                  </span></li>
                  <li class="list-group-item"><span>misl - larghezza:</span><span class="font-weight-bold">
                    <?php echo $m['misl'] ? $m['misl']." cm" : ''; ?>
                  </span></li>
                  <li class="list-group-item"><span>misp - profondità:</span><span class="font-weight-bold">
                    <?php echo $m['misp'] ? $m['misp']." cm" : ''; ?>
                  </span></li>
                  <li class="list-group-item"><span>misd - diametro:</span><span class="font-weight-bold">
                    <?php echo $m['misd'] ? $m['misd']." cm" : ''; ?>
                  </span></li>
                  <li class="list-group-item"><span>misn - lunghezza:</span><span class="font-weight-bold">
                    <?php echo $m['misn'] ? $m['misn']." cm" : ''; ?>
                  </span></li>
                  <li class="list-group-item"><span>miss - spessore:</span><span class="font-weight-bold">
                    <?php echo $m['miss'] ? $m['miss']." cm" : ''; ?>
                  </span></li>
                  <li class="list-group-item"><span>misg - peso:</span><span class="font-weight-bold">
                    <?php echo $m['misg'] ? $m['misg']." gr" : ''; ?>
                  </span></li>
                  <li class="list-group-item"><span>misv - misure varie:</span><span class="font-weight-bold">
                    <?php echo $m['misv'] ? nl2br($m['misv']) : ''; ?>
                  </span></li>
                </ul>
              <?php } ?>
            </fieldset>
            <fieldset id="munsellFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">Munsell</legend>
              <?php if ($scheda['mt']['munsell'] !== null){ ?>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"><span>Munsell:</span><span class="font-weight-bold">
                    <?php echo $scheda['mt']['munsell']['gruppo']." ".$scheda['mt']['munsell']['code']." ".$scheda['mt']['munsell']['color']; ?>
                  </span></li>
              <?php }else {
                echo $noData;
              } ?>
            </fieldset>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="daFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">DA - DATI ANALITICI</legend>
            <fieldset id="desFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">DES - DESCRIZIONE</legend>
              <ul class="list-group list-group-flush">
                <?php
                if($scheda['scheda']['tskid']==1){
                  echo '<li class="list-group-item"><span>DESO - Indicazioni sull\'oggetto:</span><span class="font-weight-bold">'.$scheda['da']['deso'].'</span></li>';
                }else{
                  ?>
                  <li class="list-group-item"><span>DESA - Dritto:</span><span class="font-weight-bold"><?php echo $scheda['da']['desa']; ?></span></li>
                  <li class="list-group-item"><span>DESM - Rovescio:</span><span class="font-weight-bold"><?php echo $scheda['da']['desm']; ?></span></li>
                  <li class="list-group-item"><span>DESV - Taglio:</span><span class="font-weight-bold"><?php echo $scheda['da']['desv']; ?></span></li>
                  <li class="list-group-item"><span>DESL - Legenda dritto:</span><span class="font-weight-bold"><?php echo $scheda['da']['desl']; ?></span></li>
                  <li class="list-group-item"><span>DESG - Legenda rovescio:</span><span class="font-weight-bold"><?php echo $scheda['da']['desg']; ?></span></li>
                  <li class="list-group-item"><span>DESU - Soggetto:</span><span class="font-weight-bold"><?php echo $scheda['da']['desu']; ?></span></li>
                  <li class="list-group-item"><span>DESF - Alfabeto/scrittura dritto:</span><span class="font-weight-bold"><?php echo $scheda['da']['desf']; ?></span></li>
                  <li class="list-group-item"><span>DEST - Alfabeto/scrittura rovescio:</span><span class="font-weight-bold"><?php echo $scheda['da']['dest']; ?></span></li>
                  <li class="list-group-item"><span>DESO - Taglio:</span><span class="font-weight-bold"><?php echo $scheda['da']['deso']; ?></span></li>
                  <li class="list-group-item"><span>DESN - Lingua dritto:</span><span class="font-weight-bold"><?php echo $scheda['da']['desn']; ?></span></li>
                  <li class="list-group-item"><span>DESR - Lingua rovescio:</span><span class="font-weight-bold"><?php echo $scheda['da']['desr']; ?></span></li>
                  <li class="list-group-item"><span>DESD - Descrizione bene paramonetale:</span><span class="font-weight-bold"><?php echo $scheda['da']['desd']; ?></span></li>
                <?php } ?>
              </ul>
            </fieldset>
            <?php if($scheda['scheda']['tskid']==2){ ?>
            <fieldset id="zecFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">ZEC - ZECCA</legend>
              <ul class="list-group list-group-flush">
                  <li class="list-group-item"><span>ZEC - Zecca:</span><span class="font-weight-bold"><?php echo $scheda['da']['zec']; ?></span></li>
              </ul>
            </fieldset>
          <?php } ?>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="coFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">CO - CONSERVAZIONE</legend>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><span>STCC - Stato di conservazione:</span><span class="font-weight-bold"><?php echo $scheda['co']['stcc']; ?></span></li>
              <li class="list-group-item"><span>STCL - Leggibilità:</span><span class="font-weight-bold"><?php echo $scheda['co']['stcl']; ?></span></li>
            </ul>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="tuFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">TU - CONDIZIONE GIURIDICA E VINCOLI</legend>
            <?php if(count((array)$scheda['tu']['acq'])==0){echo $noData;}else{ ?>
            <fieldset id="acqFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">ACQ - ACQUISIZIONE</legend>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><span>ACQT - Tipo acquisizione:</span><span class="font-weight-bold"><?php echo $scheda['tu']['acq']['acqt']; ?></span></li>
                <li class="list-group-item"><span>ACQN - Nome:</span><span class="font-weight-bold"><?php echo $scheda['tu']['acq']['acqn']; ?></span></li>
                <li class="list-group-item"><span>ACQD - Data:</span><span class="font-weight-bold"><?php echo $scheda['tu']['acq']['acqd']; ?></span></li>
                <li class="list-group-item"><span>ACQL - Luogo:</span><span class="font-weight-bold"><?php echo $scheda['tu']['acq']['acql']; ?></span></li>
              </ul>
            </fieldset>
            <?php } ?>
            <fieldset id="cdgFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">CDG - CONDIZIONE GIURIDICA</legend>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><span>CDGG - Indicazione giuridica: </span><span class="font-weight-bold"><?php echo $scheda['tu']['cdg']['cdgg']; ?></span></li>
              </ul>
            </fieldset>
            <fieldset id="nvcFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">NVC - PROVVEDIMENTI DI TUTELA</legend>
              <?php if(count((array)$scheda['tu']['nvc'])==0){echo $noData;}else{ ?>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><span></span><span class="font-weight-bold"><?php echo $scheda['tu']['cdg']['cdgg']; ?></span></li>
              </ul>
              <?php } ?>
            </fieldset>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="adFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">AD - ACCESSO AI DATI</legend>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><span>ADSP - Profilo di accesso: </span><span class="font-weight-bold"><?php echo $scheda['ad']['adsp']; ?></span></li>
              <li class="list-group-item"><span>ADSM - Motivazione: </span><span class="font-weight-bold"><?php echo $scheda['ad']['adsm']; ?></span></li>
            </ul>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="anFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">AN - ANNOTAZIONI</legend>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><span>OSS - Osservazioni: </span><span class="font-weight-bold"><?php echo $scheda['an']['oss'] ? $scheda['an']['oss'] : 'dato non inserito'; ?></span></li>
            </ul>
          </fieldset>
        </div>
        <div class="col-md-6">
          <fieldset class="bg-light rounded border p-3 mb-3" id="biblioFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">Bibliografia correlata</legend>
            <?php if(count((array)$bibScheda) > 0){ ?>
            <ul class="list-group list-group-flush" id="biblioList">
              <?php foreach ($bibScheda as $i) {
                $anno = $i['anno'] ? $i['anno'].", ": '';
                $pagArr = [];
                if($i['pagine']!== null){array_push($pagArr, "pag. ".$i['pagine']);}
                if($i['figure']!== null){array_push($pagArr, "fig. ".$i['figure']);}
                $pag = count((array)$pagArr) == 0 ? '' : "(".implode(', ', $pagArr).")";
                echo "<li class='list-group-item biblioList'>";
                if(isset($_SESSION['id'])){
                  echo "<button type='button' class='btn btn-sm btn-danger mr-3' name='delBiblioScheda' data-scheda='".$_GET['get']."' data-biblio='".$i['id']."'>
                  <i class='fas fa-times'></i>
                  </button>";
                }
                echo "<span>";
                if ($i['contrib_id'] !== null) {
                  echo "<a href='contribView.php?get=".$i['contrib_id']."'>".$i['contrib_aut'].", ".$i['contrib_tit'].", ".$pag."</a> presente in: <a href='biblioView.php?get=".$i['id']."'>".$i['autore'].", ". $anno .$i['titolo']."</a>";
                }else {
                  echo "<a href='biblioView.php?get=".$i['id']."'>".$i['autore'].", ". $anno .$i['titolo'].", ".$pag."</a>";
                }
                echo "</span></li>";
              } ?>
            </ul>
          <?php } ?>
          <?php
          if(count((array)$biblioFake) > 0){
            echo '<ul class="list-group list-group-flush" id="biblioList">';
            foreach ($biblioFake as $fake) {
              echo "<li class='list-group-item biblioList'>".$fake['riferimento']."</li>";
            }
            echo '</ul>';
          }
          ?>
          <?php if (count((array)$biblioFake) == 0 && count((array)$bibScheda) == 0) {
            echo "<h5>Nessuna bibliografia disponibile</h5>";
          } ?>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="imageFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">Foto e immagini</legend>
            <div class="fotoWrap elContainer"></div>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="mappaFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">Mappa</legend>
            <div class="map" id="map">
              <div id="cardInfoMappa">
                <div class="card" style="width: 18rem;">
                  <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                  </div>
                </div>
              </div>
              <div id="alertWrap">
                <div class="alert alert-danger">
                  <h6>Luogo di ritrovamento sconosciuto</h6>
                </div>
              </div>
            </div>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="3dFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">Modello 3d</legend>
            <h5>Nessun modello 3d disponibile</h5>
            <div id="3dhop" class="tdhop" onmousedown="if (event.preventDefault) event.preventDefault()">
              <div id="toolbar" class="btn-group" role="group">
                <button type="button" id="home" class="btn btn-light" data-toggle="tooltip" title="reset zoom"><i class="fa-sharp fa-solid fa-house"></i></button>
                <button type="button" id="zoomin" class="btn btn-light" data-toggle="tooltip" title="zoom in"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
                <button type="button" id="zoomout" class="btn btn-light" data-toggle="tooltip" title="zoom out"><i class="fa-solid fa-magnifying-glass-minus"></i></button>
                <button type="button" id="light_on" class="btn btn-light" data-toggle="tooltip" title="enable light control"><i class="fa-solid fa-lightbulb"></i></button>
                <button type="button" id="full_on" class="btn btn-light" data-toggle="tooltip" title="full screen"><i class="fa-solid fa-expand"></i></button>
              </div>
              <canvas id="draw-canvas" />
            </div>
          </fieldset>
          <!-- <fieldset class="bg-light rounded border p-3 mb-3" id="multimediaFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">Audio e video</legend>
          </fieldset> -->
        </div>
      </div>
    </div>
    </main>
    <div id="fotoModal">
      <div id="fotoOrigDiv" class="container-fluid">
        <div class="row">
          <div class="col p-2">
            <div class="nav modalMenu">
              <a href="#" class="animated nav-link" id="closeModal" title="chiudi immagine" data-toggle="tooltip" data-placement="bottom" data-modal="#fotoModal"><i class="bi bi-x-lg"></i></a>
              <a href="" class="animated nav-link" id="downloadImg" title="salva immagine" data-toggle="tooltip" data-placement="bottom" download><i class="bi bi-cloud-arrow-down-fill"></i></a>
              <?php if(isset($_SESSION['id']) && $_SESSION['classe'] == 1){ ?>
                <a href="" class="animated nav-link" id="delImg" title="elimina immagine" data-toggle="tooltip" data-placement="bottom" download><i class="bi bi-trash-fill"></i></a>
              <?php } ?>
            </div>
            <div id="divImgOrig"></div>
          </div>
        </div>
      </div>
    </div>
    <?php require('assets/toast.html'); ?>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <!-- 3dhop start -->
    <script type="text/javascript" src="js/3dhop/spidergl.js"></script>
    <script type="text/javascript" src="js/3dhop/presenter.js"></script>
    <script type="text/javascript" src="js/3dhop/nexus.js"></script>
    <script type="text/javascript" src="js/3dhop/ply.js"></script>
    <script type="text/javascript" src="js/3dhop/trackball_turntable.js"></script>
    <script type="text/javascript" src="js/3dhop/trackball_turntable_pan.js"></script>
    <script type="text/javascript" src="js/3dhop/trackball_pantilt.js"></script>
    <script type="text/javascript" src="js/3dhop/trackball_sphere.js"></script>
    <script type="text/javascript" src="js/3dhop/init.js"></script>
    <!-- 3dhop end   -->
    <script src="js/wmsTile.js" charset="utf-8"></script>
    <script src="js/pureknob.js" charset="utf-8"></script>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/schedaView.js" charset="utf-8"></script>
  </body>
</html>
