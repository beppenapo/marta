<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
  <meta http-equiv="cache-control" content="no-cache">
  <meta http-equiv="pragma" content="no-cache">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <title>MarTa 3.0</title>
  <link rel="icon" href="favicon.ico" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preload" href="css/font/GothamRounded-Book.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="css/piante.css">
  </head>
  <body>
    <?php 
      require('assets/headerMenu.php'); 
      require('assets/loading.html');
      if(isset($_SESSION['id'])) {require('assets/mainMenu.php');} 
    ?>
    <main class="bg-light">
      <div id="mainTitle" class="my-2 py-2 bg-marta text-white">
        <div>
          <h2>Esplora il museo!</h2>
          <p>Naviga nelle stanze del Museo grazie alle piante interattive.<br>Scegli il piano e spostati al suo interno spostando il mouse mentre tieni premuto il tasto destro</p>
        </div>
      </div>
      <div class="container-fluid">
        <div class="row mb-3">
          <div class="col-md-3">
            <div id="introMuseo" class="card">
              <div class="card-body">
                <h4>Descrizione museo</h4>
                <p>I reperti schedati in totale sono <span id="totReperti"></span>, i reperti esposti e visibili sui 3 piani del Museo sono <span id="totEsposto"></span>, mentre il deposito contiene <span id="totDeposito"></span> reperti</p>




                <div class="accordion accordion-flush mt-3" id="accordionFlushExample">
                  <div class="accordion-item">
                    <h6 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        Piano terra
                      </button>
                    </h6>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                      <div class="accordion-body">
                        <ul class="list-group list-group-flush">
                          <li class="list-group-item">aree: <span id="saleP0"></span></li>
                          <li class="list-group-item">reperti: <span id="repertiP0"></span></li>
                        </ul>
                        <p class="mt-3">I reperti visibili al piano terra sono distribuiti nelle 3 aree di "ingresso", "hall" e "chiostro", tutti fuori vetrina</p>
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h6 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                        Primo piano
                      </button>
                    </h6>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                      <div class="accordion-body">
                        <ul class="list-group list-group-flush">
                          <li class="list-group-item">sale: <span id="saleP1"></span></li>
                          <li class="list-group-item">vetrine: <span id="vetrineP1"></span></li>
                          <li class="list-group-item">totale reperti: <span id="repertiP1"></span></li>
                          <li class="list-group-item">reperti fuori vetrina: <span id="fvP1"></span></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h6 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                        Secondo piano
                      </button>
                    </h6>
                    <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                      <div class="accordion-body">
                        <ul class="list-group list-group-flush">
                          <li class="list-group-item">sale: <span id="saleP2"></span></li>
                          <li class="list-group-item">vetrine: <span id="vetrineP2"></span></li>
                          <li class="list-group-item">totale reperti: <span id="repertiP2"></span></li>
                          <li class="list-group-item">reperti fuori vetrina: <span id="fvP2"></span></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h6 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                        Deposito
                      </button>
                    </h6>
                    <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                      <div class="accordion-body">
                        <ul class="list-group list-group-flush">
                          <li class="list-group-item">stanze: <span id="stanze"></span></li>
                          <li class="list-group-item">scaffali: <span id="scaffali"></span></li>
                          <li class="list-group-item">monetieri: <span id="monetieri"></span></li>
                          <li class="list-group-item">casseforti: <span id="casseforti"></span></li>
                          <li class="list-group-item">cassette: <span id="cassette"></span></li>
                          <li class="list-group-item">totale reperti: <span id="repertiP-1"></span></li>
                          <li class="list-group-item">reperti monetieri: <span id="repertiMonetieri"></span></li>
                          <li class="list-group-item">reperti casseforti: <span id="repertiCasseforti"></span></li>
                          <li class="list-group-item">reperti fuori cassetta: <span id="fuoriCassetta"></span></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="w-100 h-100 bg-white rounded border position-relative">
              <div id="guidaNavigazione">
                <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#navTip">come usare le mappe</button>
                <div id="navTip" class="collapse bg-light rounded border">
                  <div class="text-end p-2">
                    <button type="button" class="btn-close" aria-label="Close" data-bs-toggle="collapse" data-bs-target="#navTip"></button>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">Clicca su uno dei pulsanti corrispondenti ai piani per caricare la mappa del piano e le schede di tutti i reperti presenti sul piano selezionato</li>
                    <li class="list-group-item">Clicca su una sala per visualizzare i reperti "fuori vetrina" o "fuori cassetta" se stiamo navigando nel deposito</li>
                    <li class="list-group-item">Clicca su una vetrina per visualizzare i reperti contenuti al suo interno o un scaffale se siamo all'interno del deposito (lo scaffale è indicato con un numero preceduto dalla lettera "S", es "S1", "S2" ...)</li>
                    <li class="list-group-item">Nel deposito è possible cliccare anche sulla singola colonna che fa parte di uno scaffale. Le colonne sono indicate da un numero.</li>
                    <li class="list-group-item text-danger">Il sistema conserva in memoria l'ultima scelta effettuata, questo vuol dire che se cambi pagina e ritorni qui, la pagina partirà mostrando i risultati dell'ultima ricerca</li>
                  </ul>
                </div>
              </div>
              <div class="form-group text-center mt-3">
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                  <input type="radio" class="btn-check" name="piani" id="deposito" value="-1" >
                  <label class="btn btn-outline-secondary" for="deposito">deposito</label>

                  <input type="radio" class="btn-check" name="piani" id="terra" value="0">
                  <label class="btn btn-outline-secondary" for="terra">piano terra</label>

                  <input type="radio" class="btn-check" name="piani" id="primo" value="1">
                  <label class="btn btn-outline-secondary" for="primo">primo piano</label>

                  <input type="radio" class="btn-check" name="piani" id="secondo" value="2">
                  <label class="btn btn-outline-secondary" for="secondo">secondo piano</label>
                </div>
              </div>
              <div id="svgWrap"></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div id="totalItems">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb" id="breadcrumb"></ol>
              </nav>
              <h2></h2>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div id="wrapItems"></div>
          </div>
        </div>
      </div>
    </main>
    <?php require('assets/footer.html'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/70854e9b30.js" crossorigin="anonymous" ></script>

    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/svg-pan-zoom.js" charset="utf-8"></script>
    <script src="js/esplora.js" charset="utf-8"></script>
  </body>
</html>
