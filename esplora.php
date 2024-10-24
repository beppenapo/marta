<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/piante.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if(isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
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
                <p>I reperti esposti e visibili sui 3 piani del Museo, sono <span id="totEsposto"></span>, mentre il deposito contiene <span id="totDeposito"></span> reperti non visibili al pubblico.</p>

                <h6>Piano terra</h6>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item border-top">aree: <span id="saleP0"></span></li>
                  <li class="list-group-item border-bottom">reperti: <span id="repertiP0"></span></li>
                </ul>
                <p class="mt-3">I reperti visibili al piano terra sono distribuiti nelle 3 aree di "ingresso", "hall" e "chiostro", tutti fuori vetrina</p>

                <h6>Primo piano</h6>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item border-top">sale: <span id="saleP1"></span></li>
                  <li class="list-group-item">vetrine: <span id="vetrineP1"></span></li>
                  <li class="list-group-item">totale reperti: <span id="repertiP1"></span></li>
                  <li class="list-group-item border-bottom">reperti fuori vetrina: <span id="fvP1"></span></li>
                </ul>

                <h6>Secondo piano</h6>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item border-top">sale: <span id="saleP2"></span></li>
                  <li class="list-group-item">vetrine: <span id="vetrineP2"></span></li>
                  <li class="list-group-item">totale reperti: <span id="repertiP2"></span></li>
                  <li class="list-group-item border-bottom">reperti fuori vetrina: <span id="fvP2"></span></li>
                </ul>

                <h6>Deposito</h6>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item border-top">stanze: <span id="stanze"></span></li>
                  <li class="list-group-item">scaffali: <span id="scaffali"></span></li>
                  <li class="list-group-item">monetieri: <span id="monetieri"></span></li>
                  <li class="list-group-item">casseforti: <span id="casseforti"></span></li>
                  <li class="list-group-item">totale reperti: <span id="repertiP-1"></span></li>
                  <li class="list-group-item">reperti monetieri: <span id="repertiMonetieri"></span></li>
                  <li class="list-group-item border-bottom">reperti casseforti: <span id="repertiCasseforti"></span></li>
                </ul>
                <?php if(!isset($_SESSION['id'])){?>
                  <div class="alert alert-danger p-3 mt-3 text-center">
                    <p class="mb-0"><strong>I reperti presenti nei depositi non verranno mostrati nella gallery.</strong></p>
                    <small>Per visualizzare i reperti presenti nei depositi è necessario possedere un account da ricercatore. Per informazioni scrivere ad una delle seguenti mail:</small>
                    <a href="mailto:sara.airo@cultura.gov.it" class="alert-link">sara.airo@cultura.gov.it</a><br>
                    <a href="mailto:antonio.donnici@cultura.gov.it" class="alert-link">antonio.donnici@cultura.gov.it</a> 
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="w-100 h-100 bg-white rounded border">
              <div class="form-group text-center">
                <label class="d-block">Seleziona il piano da visualizzare</label>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                  <?php if(isset($_SESSION['id'])){ ?>
                  <label class="btn btn-outline-secondary">
                    <input type="radio" name="piani" id="deposito" value="-1"> <span>deposito</span>
                  </label>
                  <?php } ?>
                  <label class="btn btn-outline-secondary">
                    <input type="radio" name="piani" id="terra" value="0"> <span>piano terra</span>
                  </label>
                  <label class="btn btn-outline-secondary">
                    <input type="radio" name="piani" id="primo" value="1"> <span>primo piano</span>
                  </label>
                  <label class="btn btn-outline-secondary">
                    <input type="radio" name="piani" id="secondo" value="2"> <span>secondo piano</span>
                  </label>
                </div>
              </div>
              <div id="svgWrap"></div>
            </div>
          </div>
        </div>
        <div id="totalItems"><h2></h2></div>
        <div class="row">
          <div class="col">
            <div id="wrapItems"></div>
          </div>
        </div>
      </div>
    </main>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/svg-pan-zoom.js" charset="utf-8"></script>
    <script src="js/esplora.js" charset="utf-8"></script>
  </body>
</html>
