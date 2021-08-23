<?php require("api/php/schedaView.php"); ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <link rel="stylesheet" href="css/schedaView.css">
  </head>
  <body>
    <input type="hidden" name="schedaId" value="<?php echo $_GET['get']; ?>">
    <input type="hidden" name="nctnId" value="<?php echo $scheda['scheda']['nctn']; ?>">
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <div id="loadingDiv" class="flexDiv invisible"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main>
      <?php if (isset($_SESSION['id']) && ($_SESSION['id'] == $scheda['scheda']['cmpid'] || $_SESSION['classe'] < 3)) { ?>
      <div id="menuScheda" class="bg-dark px-3">
        <div class="btn-group" role="group">
          <div class="btn-group" role="group">
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
          <div class="btn-group" role="group">
            <button id="modifica" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-edit"></i> modifica</button>
            <div class="dropdown-menu" aria-labelledby="modifica">
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right" data-form="titoli">titolo e codici</a>
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right" data-form="og">OG - oggetto</a>
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right" data-form="lc">LC - localizzazione geografico-amministrativa</a>
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right" data-form="ub">UB - dati patrimoniali</a>
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right" data-form="gp">GP - georeferenziazione tramite punto</a>
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right" data-form="re">RE - modalità di reperimento</a>
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right" data-form="dt">DT - cronologia</a>
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right" data-form="mt">MT - dati tecnici</a>
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right" data-form="da">DA - dati analitici</a>
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right" data-form="co">CO - conservazione</a>
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right" data-form="tu">TU - condizione giuridica e vincoli</a>
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right" data-form="ad">AD - accesso ai dati</a>
              <a class="dropdown-item" href="#" title="" data-toggle="tooltip" data-placement="right" data-form="an">AN - annotazioni</a>
            </div>
          </div>
          <button id="duplicaScheda" name="duplicaScheda" type="button" class="btn btn-dark"><i class="fas fa-copy"></i> duplica</button>
          <button id="chiudiScheda" name="chiudiScheda" type="button" class="btn btn-dark"><i class="fas fa-clipboard-check"></i> chiudi</button>
          <button id="eliminaScheda" name="eliminaScheda" type="button" class="btn btn-dark"><i class="fas fa-times"></i> elimina</button>
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
            <?php if(count($scheda['lc'])==0){echo $noData;}else{ ?>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><span>PVCC - Comune:</span><span class="font-weight-bold"><?php echo $scheda['lc']['pvcc']; ?></span></li>
              <li class="list-group-item"><span>LDCN - Collocazione specifica (Denominazione):</span><span class="font-weight-bold"><?php echo $scheda['lc']['ldcn']; ?></span></li>
              <li class="list-group-item"><span>PIANO:</span><span class="font-weight-bold"><?php echo $scheda['lc']['piano']; ?></span></li>
              <li class="list-group-item"><span>SALA:</span><span class="font-weight-bold"><?php echo $scheda['lc']['sala']; ?></span></li>
              <li class="list-group-item"><span>CONTENITORE:</span><span class="font-weight-bold"><?php echo $scheda['lc']['contenitore']; ?></span></li>
              <li class="list-group-item"><span>COLONNA:</span><span class="font-weight-bold"><?php echo $scheda['lc']['colonna']; ?></span></li>
              <li class="list-group-item"><span>RIPIANO:</span><span class="font-weight-bold"><?php echo $scheda['lc']['ripiano']; ?></span></li>
            </ul>
            <?php } ?>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="ubFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">UB - DATI PATRIMONIALI</legend>
            <?php if(count($scheda['ub'])==0){echo $noData;}else{ ?>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><span>INVN - Inventario:</span><span class="font-weight-bold"><?php echo $scheda['ub']['invn']; ?></span></li>
              <li class="list-group-item"><span>STIS - Stima:</span><span class="font-weight-bold"><?php echo $scheda['ub']['stis']; ?></span></li>
              <li class="list-group-item"><span>STID - Anno stima:</span><span class="font-weight-bold"><?php echo $scheda['ub']['stid']; ?></span></li>
              <li class="list-group-item"><span>STIM - Motivo stima:</span><span class="font-weight-bold"><?php echo $scheda['ub']['stim']; ?></span></li>
            </ul>
            <?php } ?>
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
            <fieldset id="rcgFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">rcg - ricognizioni</legend>
              <?php if(count($scheda['re']['rcg'])==0){echo $noData;}else{ ?>
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
              <?php if(count($scheda['re']['dsc'])==0){echo $noData;}else{ ?>
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
              <?php if(count($scheda['re']['ain'])==0){echo $noData;}else{ ?>
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
                <li class="list-group-item"><span>DTZS - Frazione cronologica:</span><span class=""><?php echo !$scheda['dt']['dt']['dtzs'] ? $noValue : $scheda['dt']['dt']['dtzs']; ?></span></li>
              </ul>
            </fieldset>
            <fieldset id="dtsFieldset" class="mb-3">
              <legend class="text-marta font-weight-bold border-bottom">DTS - Cronologia specifica</legend>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><span>DTSI - Da:</span><span class=""><?php echo $scheda['dt']['dt']['dtsi'] ? $scheda['dt']['dt']['dtsi'] : $noValue; ?></span></li>
                <li class="list-group-item"><span>DTSF - A:</span><span class=""><?php echo $scheda['dt']['dt']['dtsf'] ? $scheda['dt']['dt']['dtsf'] : $noValue; ?></span></li>
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
            <ul class="list-group list-group-flush">
              <?php foreach ($scheda['mt'] as $mtc) {
                echo '<li class="list-group-item"><span>Materia/Tecnica:</span><span class="font-weight-bold">'.$mtc['materia'].'/'.$mtc['tecnica'].'</span></li>';
              } ?>
            </ul>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="daFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">DA - DATI ANALITICI</legend>
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
          <fieldset class="bg-light rounded border p-3 mb-3" id="coFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">CO - CONSERVAZIONE</legend>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><span>STCC - Stato di conservazione:</span><span class="font-weight-bold"><?php echo $scheda['co']['stcc']; ?></span></li>
              <li class="list-group-item"><span>STCL - Leggibilità:</span><span class="font-weight-bold"><?php echo $scheda['co']['stcl']; ?></span></li>
            </ul>
          </fieldset>
          <fieldset class="bg-light rounded border p-3 mb-3" id="tuFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">TU - CONDIZIONE GIURIDICA E VINCOLI</legend>
            <?php if(count($scheda['tu']['acq'])==0){echo $noData;}else{ ?>
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
              <?php if(count($scheda['tu']['nvc'])==0){echo $noData;}else{ ?>
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
          <fieldset class="bg-light rounded border p-3 mb-3" id="multimediaFieldset">
            <legend class="w-auto bg-marta text-white border rounded p-1">immagini - documenti - multimedia</legend>
            <?php if(count($scheda['gp'])>0){ ?>
            <div id="mappa" class="bg-marta"></div>
            <?php } ?>
            <?php if(count($bibScheda) > 0){ ?>
            <legend class="text-marta font-weight-bold border-bottom">Bibliografia correlata</legend>
            <ul class="list-group list-group-flush" id="biblioList">
              <?php foreach ($bibScheda as $i) {
                $anno = $i['anno'] ? $i['anno'].", ": '';
                $authority = "<a href='biblioView.php?get=".$i['id']."'>".$i['autore'].", ". $anno .$i['titolo']."</a>";
                echo "<li class='list-group-item'>";
                if(isset($_SESSION['id'])){
                  echo "<button type='button' class='btn btn-sm btn-danger mr-3' name='delBiblioScheda' data-scheda='".$_GET['get']."' data-biblio='".$i['id']."'>
                  <i class='fas fa-times'></i>
                  </button>";
                }
                echo "<span>";
                if ($i['contrib_id'] !== null) {
                  $pagArr = [];
                  if($i['pagine']!== null){array_push($pagArr, "pag. ".$i['pagine']);}
                  if($i['figure']!== null){array_push($pagArr, "fig. ".$i['figure']);}
                  $pag = count($pagArr) == 0 ? '' : "(".implode(', ', $pagArr).")";
                  echo "<a href='contribView.php?get=".$i['contrib_id']."'>".$i['contrib_aut'].", ".$i['contrib_tit'].", ".$pag."</a> presente in: ".$authority;
                }else {
                  echo $authority;
                }
                echo "</span></li>";
              } ?>
            </ul>
            <?php } ?>
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
