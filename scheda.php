<?php
session_start();
if (!isset($_SESSION['id'])){ header("location:login.php");}
switch ($_GET['act']) {
  case 'add': $title = 'Aggiungi una nuova scheda '; break;
  case 'edit': $title = 'Modifica scheda '; break;
  case 'view': $title = 'Visualizza scheda '; break;
}
if (isset($_GET['tipo'])) {
  $title .= $_GET['tipo'] == 1 ? 'RA' : 'NU';
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
        <div class="row mb-4">
          <div class="col">
            <h3 class="border-bottom"><?php echo $title; ?></h3>
            <small class="text-danger font-weight-bold d-block">* Campi obbligatori</small>
            <small class="font-weight-bold d-block">* Obbligatorietà di contesto</small>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              Il numero di catalogo ICCD verrà assegnato automaticamente e ti verrà comunicato al momento del salvtaggio della scheda.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
          </div>
        </div>
        <form id="formScheda" autocomplete="off">
          <input type="hidden" name="tsk" class="tab" data-table="cd" value="<?php echo $_GET['tipo']; ?>">
          <input type="hidden" name="lir" class="tab" data-table="cd" value="2">
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">INVENTARIO MUSEO</legend>
              <div class="form-row">
                <div class="col-md-6">
                  <label for="inventario">
                    <i class="fas fa-info-circle text-muted" data-toggle="tooltip" title="Il numero di inventario, pur non essendo obbligatorio, è di fondamentale importanza per identificare velocemente il reperto all'interno del catalogo; pertanto si consiglia di inserirlo sempre laddove presetnte"></i>
                    Numero inventario
                  </label>
                  <input type="text" class="form-control tab" data-table="scheda" id="inventario" name="inventario" value="">
                </div>
                <div class="col-md-2">
                  <label for="suffix"><i class="fas fa-info-circle text-muted" data-toggle="tooltip" title="inserire eventuali specifiche relative al numero di inventario, es.: 'bis', 'A' ecc."></i> Suffisso</label>
                  <input type="text" class="form-control tab" data-table="scheda" id="suffix" name="suffix" value="">
                </div>
                <div class="col-md-4">
                  <button class="btn btn-outline-secondary" type="button" data-toggle="collapse" data-target="#inventarioTip" aria-expanded="false" aria-controls="inventarioTip" style="position:absolute; bottom:0" id="toggleInventarioTipText">nascondi guida</button>
                </div>
              </div>
              <div class="form-row collapse show" id="inventarioTip">
                <div class="col">
                  <small class="text-muted"><i class="fas fa-exclamation"></i> Il numero di inventario deve essere di tipo numerico, ovvero non deve contenere spazi, lettere o caratteri speciali i quali, se presenti, vanno inseriti nel campo "suffisso". Di seguito alcuni esempi:
                    <span class="d-block">"TA-12345" diventa "12345"</span>
                    <span class="d-block">"00012345" diventa "12345"</span>
                    <span class="d-block">"12345 A" diventa "12345" e "A" nel campo suffisso</span>
                    <span class="d-block">"12345 A-B-C" diventa "12345" e "A-B-C" nel campo suffisso</span>
                  </small>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">OG - OGGETTO</legend>
              <div class="form-row">
                <div class="col">
                  <small class="text-muted"><i class="fas fa-info-circle"></i> Se in lista non è presente una definizione attinente all'oggetto, o non è possibile dare una definizione, scegliere il valore "termini generici" dalla lista "CLS - Categoria" e "definizione assente" dalla lista "OGTD - Definizione" </small>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-4 mb-3">
                  <label for="cls1" class="text-danger font-weight-bold">CLS - Categoria</label>
                  <select class="form-control form-control-sm" id="cls1" name="cls1" required>
                    <option value="" selected disabled>--seleziona categoria--</option>
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="cls2" class="text-danger font-weight-bold">CLS - Classe</label>
                  <select class="form-control form-control-sm" id="cls2" name="cls2" required disabled></select>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="ogtdLabel" class="text-danger font-weight-bold"><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="inizia a digitare un termine o scorri la lista per selezionare il valore desiderato"></i> OGTD - Definizione</label>
                  <div class="input-group">
                    <input type="text" class="form-control form-control-sm" id="ogtdLabel" name="ogtdLabel" required disabled>
                    <div class="input-group-append">
                      <button type="button" class="btn btn-secondary btn-sm" name="resetOgtd" data-toggle="tooltip" data-placement="top" title="cancella selezione" disabled><i class="fas fa-times"></i></button>
                    </div>
                  </div>
                  <small id="ogtdAlert" class="alert alert-danger mt-1 p-1 text-center">Attenzione! Non hai selezionato alcun valore</small>
                  <input type="hidden" class="tab" data-table="og" name="ogtd" value="">
                </div>
              </div>
            </fieldset>
          </div>

          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">LC - LOCALIZZAZIONE GEOGRAFICO-AMMINISTRATIVA</legend>
              <div class="form-row">
                <div class="col">
                  <label for="ldcs" class="text-danger font-weight-bold">LDCS - collocazione specifica</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="lc" id="ldcs" name="ldcs" value="" placeholder="Es.: Piano I/ stanza 5/ scaffale 2/ scatola 26" required>
                  <small class="text-muted">Inserire la collocazione specifica all'interno del Museo. La descrizione deve procedere dal generale al particolare, dividendo le varie informazioni con una barra (‘/’) seguita da uno spazio.</small>
                </div>
              </div>

              <div class="form-row">
                <div class="col-12 col-lg-2">
                  <label for="piano" class="text-danger font-weight-bold">Piano</label>
                  <select class="form-control form-control-sm" id="piano" name="piano" required>
                    <option selected disabled>-- piano --</option>
                    <option value="-1">Deposito</option>
                    <option value="1">Primo piano</option>
                    <option value="3">Terzo piano</option>
                  </select>
                </div>
                <div class="col-12 col-lg-2">
                  <div class="lcSel" id="lcSalaDiv">
                    <label for="sala" class="text-danger font-weight-bold">Sala</label>
                    <select class="form-control form-control-sm" id="sala" name="sala" required></select>
                  </div>
                </div>
                <div class="col-12 col-lg-2">
                  <div class="lcSel" id="lcContenitoreDiv">
                    <label for="contenitore" id="contenitoreLabel" class=""></label>
                    <select class="form-control form-control-sm" id="contenitore" name="contenitore"></select>
                  </div>
                </div>
              </div>
              <div class="form-row lcSel" id="noVetrine">
                <div class="col-12">
                  <div class="alert alert-warning mt-3 mb-0">
                    <label class="">Non ci sono vetrine per il piano selezionato</label>
                  </div>
                </div>
              </div>
            </fieldset>
          </div>

          <div class="form-group">
            <fieldset class="bg-light rounded border p-3" id="laFieldset">
              <legend class="w-auto bg-marta text-white border rounded p-1">LA - ALTRE LOCALIZZAZIONI GEOGRAFICO-AMMINISTRATIVE</legend>
              <div class="form-row mb-3">
                <div class="col">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="toggleLA" name="toggleSection" data-fieldset="laFieldset">
                    <label class="custom-control-label" for="toggleLA">Compila paragrafo</label>
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-5">
                  <label for="tcl" class="font-weight-bold">TCL - Tipo di localizzazione</label>
                  <select class="form-control form-control-sm tab" data-table="la" id="tcl" name="tcl" disabled>
                    <option value="">--seleziona tipologia --</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="prvp" class="font-weight-bold">PRVP - Provincia</label>
                  <select class="form-control form-control-sm tab" data-table="la" id="prvp" name="prvp" disabled>
                    <option value="">--seleziona provincia --</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="prvc" class="font-weight-bold">PRVC - Comune</label>
                  <select class="form-control form-control-sm tab" data-table="la" id="prvc" name="prvc" disabled></select>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3" id="reFieldset">
              <legend class="w-auto bg-marta text-white border rounded p-1">RE - MODALITA' DI REPERIMENTO</legend>
              <div class="form-row mb-3">
                <div class="col">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="toggleRE" name="toggleSection" data-fieldset="reFieldset">
                    <label class="custom-control-label" for="toggleRE">Compila paragrafo</label>
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-4">
                  <label for="scan" class="font-weight-bold">SCAN - Denominazione dello scavo</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="re" id="scan" name="scan" value="" disabled>
                </div>
                <div class="col-md-4">
                  <label for="dsca" class="font-weight-bold">DSCA - Responsabile scientifico</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="re" id="dsca" name="dsca" value="" disabled>
                </div>
                <div class="col-md-4">
                  <label for="dscd" class="font-weight-bold">DSCD - Data</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="re" id="dscd" name="dscd" value="" disabled>
                </div>
              </div>
              <div class="form-row">
                <div class="col">
                  <small class="text-muted"><i class="fas fa-info-circle"></i> Per aggiungere la bibliografia di riferimento bisogna prima salvare la scheda e accedere alla pagina di gestione del reperto</small>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3" id="dtFieldset">
              <legend class="w-auto bg-marta text-white border rounded p-1">DT - CRONOLOGIA</legend>
              <div class="form-row">
                <div class="col-md-4 mb-3">
                  <h6 class="border-bottom font-weight-bold">DTZ - Cronologia generica</h6>
                  <label for="dtzg" class="font-weight-bold text-danger">DTZG - Fascia cronologica</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="dtz" id="dtzg" name="dtzg" value="" placeholder="Es.: XX, V-VII, 1400, Bronzo medio ecc." required>
                  <label for="dtzs">DTZS - Frazione cronologica</label>
                  <select class="form-control form-control-sm tab" data-table="dtz" id="dtzs" name="dtzs">
                    <option value="">--seleziona valore--</option>
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <h6 class="border-bottom font-weight-bold">DTS - Cronologia specifica</h6>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="toggleDTS" name="toggleSection" data-fieldset="dtsFieldset">
                    <label class="custom-control-label" for="toggleDTS">Compila paragrafo</label>
                  </div>
                  <div id="dtsFieldset">
                    <label for="dtsi" class="font-weight-bold">DTSI - DA</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="dts" id="dtsi" name="dtsi" value="" placeholder="Utilizzare possibilmente numeri arabi" disabled>
                    <label for="dtsf" class="font-weight-bold">DTSF - A</label>
                    <input type="text" class="form-control form-control-sm tab" data-table="dts" id="dtsf" name="dtsf" value="" placeholder="Utilizzare possibilmente numeri arabi" disabled>
                  </div>
                </div>
                <div class="col-md-4 mb-3">
                  <h6 class="border-bottom font-weight-bold">DTM - Motivazione cronologia</h6>
                  <small class="d-block m-0 text-danger"><i class="fas fa-info-circle"></i> Campo multiplo obbligatorio, devi inserire almeno un valore. Puoi aggiungere più motivazioni cliccando sui valori presenti in lista.</small>
                  <select class="form-control form-control-sm mb-3" id="dtmSel" name="dtmSel" required>
                    <option value="">--seleziona valore--</option>
                  </select>
                  <div id="dtmWrap"></div>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">MT - DATI TECNICI</legend>
              <div class="form-row mb-3">
                <div class="col">
                  <h6 class="border-bottom font-weight-bold">MTC - Materia e tecnica</h6>
                  <small class="d-block m-0 text-muted"><i class="fas fa-info-circle"></i> Campo multiplo, dopo aver selezionato la materia aggiungi una o più tecniche selezionandole dalla lista (inizia a digitare il nome della tecnica per visualizzare le opzioni disponibili) e conferma la scelta utilizzando il tasto "+".<br/>Quando il record è completo clicca sul tasto "ok".<br/>Ripeti la sequenza per aggiungere una nuova materia e associarle tecniche specifiche.<br/>Se il materiale non è presente in lista selezionare "Materiale non presente in lista"<br/>Se la tecnica non è presente in lista selezionare "Tecnica non presente in lista"</small>
                </div>
              </div>
              <div class="form-row mb-3">
                <div class="col-md-5">
                  <label for="materia"><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="inizia a digitare un termine o scorri la lista per selezionare il valore desiderato"></i> Materia</label>
                  <input type="text" class="form-control form-control-sm" id="materia" name="materia" value="" autocomplete="off">
                </div>
                <div class="col-md-6">
                  <label for="tecnica"><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="inizia a digitare un termine o scorri la lista per selezionare il valore desiderato"></i> Tecnica</label>
                  <div class="input-group">
                    <input type="text" class="form-control form-control-sm" id="tecnica" name="tecnica" value="" disabled>
                    <div class="input-group-append">
                      <button type="button" class="btn btn-sm btn-marta" name="addTecnica" disabled><i class="fas fa-plus"></i></button>
                    </div>
                  </div>
                </div>
                <div class="col-md-1">
                  <button type="button" class="btn btn-sm btn-marta w-100" name="addMtc" disabled>Ok</button>
                </div>
              </div>
              <div id="mtcWrap"></div>
              <div class="form-row mb-3">
                <div class="col">
                  <h6 class="border-bottom font-weight-bold">MIS - Misure</h6>
                  <small class="d-block m-0 text-danger"><i class="fas fa-info-circle"></i> Attenzione! Le dimensioni vanno indicate in cm. mentre il peso in gr.<br/>Inserire almeno una misura</small>
                </div>
              </div>
              <div class="form-row mb-3">
                <div class="col">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input tab" id="misr" name="misr" value="MNR" data-table="mis">
                    <label class="custom-control-label" for="misr">Misure non rilevabili</label>
                  </div>
                </div>
              </div>
              <div class="form-row mb-3">
                <div class="col-sm-4 col-md-3 col-lg-2">
                  <label for="misa">Altezza</label>
                  <input type="number" min="0" max="999" step="0.01" placeholder="Es. 123,55" class="form-control form-control-sm tab misure" id="misa" name="misa" value="" data-table="mis">
                </div>
                <div class="col-sm-4 col-md-3 col-lg-2">
                  <label for="misl">Larghezza</label>
                  <input type="number" min="0" max="999" step="0.01" placeholder="Es. 123,55" class="form-control form-control-sm tab misure" id="misl" name="misl" value="" data-table="mis">
                </div>
                <div class="col-sm-4 col-md-3 col-lg-2">
                  <label for="misp">Profondità</label>
                  <input type="number" min="0" max="999" step="0.01" placeholder="Es. 123,55" class="form-control form-control-sm tab misure" id="misp" name="misp" value="" data-table="mis">
                </div>
                <div class="col-sm-4 col-md-3 col-lg-2">
                  <label for="misd">Diametro</label>
                  <input type="number" min="0" max="999" step="0.01" placeholder="Es. 123,55" class="form-control form-control-sm tab misure" id="misd" name="misd" value="" data-table="mis">
                </div>
                <div class="col-sm-4 col-md-3 col-lg-2">
                  <label for="misn">Lunghezza</label>
                  <input type="number" min="0" max="999" step="0.01" placeholder="Es. 123,55" class="form-control form-control-sm tab misure" id="misn" name="misn" value="" data-table="mis">
                </div>
                <div class="col-sm-4 col-md-3 col-lg-2">
                  <label for="miss">Spessore</label>
                  <input type="number" min="0" max="999" step="0.01" placeholder="Es. 123,55" class="form-control form-control-sm tab misure" id="misl" name="miss" value="" data-table="mis">
                </div>
              </div>
              <div class="form-row mb-3">
                <div class="col-sm-4 col-md-3 col-lg-2">
                  <label for="misg">Peso</label>
                  <input type="number" min="0" max="999" step="0.01" placeholder="Es. 123,55" class="form-control form-control-sm tab misure" id="misg" name="misg" value="" data-table="mis">
                </div>
                <div class="col">
                  <label for="misv">Misure varie</label>
                  <textarea class="form-control form-control-sm tab misure" id="misv" name="misv" value="" rows="4" placeholder="indicare altre misure utili, specificando sia il tipo di misura, sia la parte presa in esame, sia l'unità di misura" data-table="mis"></textarea>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">DA - DATI ANALITICI</legend>
              <div class="form-row">
                <div class="col">
                  <label for="deso" class="font-weight-bold text-danger">DESO - Indicazioni sull'oggetto</label>
                  <textarea id="deso" name="deso" class="form-control form-control-sm tab" data-table="da" rows="8" required></textarea>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">CO - CONSERVAZIONE</legend>
              <div class="row">
                <div class="col-md-6">
                  <label for="stcc" class="font-weight-bold text-danger">STCC -  Stato di conservazione</label>
                  <select class="form-control form-control-sm tab" id="stcc" name="stcc" data-table="co" required>
                    <option value="">--seleziona valore--</option>
                  </select>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">TU - CONDIZIONE GIURIDICA E VINCOLI</legend>
              <div class="form-row">
                <div class="col-md-6">
                  <label for="cdgg" class="font-weight-bold text-danger">CDGG - Indicazione giuridica</label>
                  <select class="form-control form-control-sm tab" id="cdgg" name="cdgg" data-table="tu" required></select>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">AD - ACCESSO AI DATI</legend>
              <div class="row">
                <div class="col-md-5">
                  <h6 class="border-bottom font-weight-bold">ADSP - Profilo di accesso</h6>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="adsp1" name="adsp" class="custom-control-input tab" data-table="ad" value="1">
                    <label class="custom-control-label" for="adsp1">
                      livello basso di riservatezza
                      <i class="far fa-question-circle" data-toggle="tooltip" data-placement="top" title="Le informazioni contenute nella scheda possono essere liberamente consultate da chiunque.<br/>E' la situazione che si riscontra solitamente per i beni di proprietà pubblica."></i>
                    </label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="adsp2" name="adsp" class="custom-control-input" value="2">
                    <label class="custom-control-label" for="adsp2">
                      livello medio di riservatezza
                      <i class="far fa-question-circle" data-toggle="tooltip" data-placement="top" title="La scheda contiene dati riservati per motivi di privacy.<br/>E' la situazione che si riscontra in genere per i beni di proprietà privata, che possono contenere dati personali che non è opportuno divulgare."></i>
                    </label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="adsp3" name="adsp" class="custom-control-input" value="3">
                    <label class="custom-control-label" for="adsp3">
                      livello alto di riservatezza
                      <i class="far fa-question-circle" data-toggle="tooltip" data-placement="top" title="La scheda contiene dati riservati per motivi di tutela.<br/>Si tratta di situazioni eccezionali per le quali, per particolari motivi di tutela individuati dall'Ente competente, non è opportuno divulgare informazioni di dettaglio sulla localizzazione del bene."></i>
                    </label>
                  </div>
                </div>
                <div class="col-md-7">
                  <h6 class="border-bottom font-weight-bold">ADSM - Motivazione</h6>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="adsm1" name="adsm" class="custom-control-input tab" data-table="ad" value="1">
                    <label class="custom-control-label" for="adsm1">scheda contenente dati liberamente accessibili</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="adsm2" name="adsm" class="custom-control-input" value="2">
                    <label class="custom-control-label" for="adsm2">scheda contenente dati personali o di bene di proprietà privata</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="adsm3" name="adsm" class="custom-control-input" value="3">
                    <label class="custom-control-label" for="adsm3">scheda di bene a rischio o non adeguatamente sorvegliabile</label>
                  </div>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">CM - COMPILAZIONE</legend>
              <div class="row">
                <div class="col-md-4">
                  <label for="cmpd">CMPD - Data</label>
                  <input type="date" class="form-control form-control-sm tab" id="cmpd" data-table="cm" name="cmpd" min="2020-07-01" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-4">
                  <label for="cmpnString">CMPN - Nome</label>
                  <input type="text" class="form-control form-control-sm" id="cmpnString" name="cmpnString" value="<?php echo $_SESSION['utente']; ?>" disabled>
                  <input type="hidden" class="tab" data-table="cm" name="cmpn" value="<?php echo $_SESSION['id']; ?>">
                </div>
                <div class="col-md-4">
                  <label for="fur">FUR - Funzionario <span class="d-none d-lg-inline-block">responsabile</span></label>
                  <input type="text" class="form-control form-control-sm tab" data-table="cm" id="fur" name="fur" value="Di Franco Luca" required>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col">
                <button type="submit" class="btn btn-sm btn-marta" name="submit">salva dati</button>
              </div>
            </div>
          </div>
        </form>
      </div>

    </main>
    <?php require('assets/toast.html'); ?>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/scheda.js" charset="utf-8"></script>
  </body>
</html>
