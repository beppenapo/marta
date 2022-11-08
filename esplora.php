<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/piante.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <main class="bg-light">
      <div id="carousel" class="carousel slide carousel-fade" data-ride="carousel">
        <div class="carousel-inner"></div>
      </div>
      <div id="mainTitle" class="my-2 py-2 bg-marta text-white text-center">
        <h2>Esplora il museo!</h2>
      </div>
      <div class="container-fluid">
        <div class="row">
          <div class="col">
            <p>Naviga nelle stanze del Museo grazie alle piante interattive. Scegli il piano e spostati al suo interno spostando il mouse mentre tieni premuto il tasto destro</p>
          </div>
        </div>
        <div class="row">
          <div class="col text-center">
            <div class="form-group">
              <label class="d-block">Seleziona il piano da visualizzare</label>
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-outline-secondary">
                  <input type="radio" name="piani" id="deposito" value="-1"> <span>deposito</span>
                </label>
                <label class="btn btn-outline-secondary">
                  <input type="radio" name="piani" id="terra" value="0" disabled> <span>piano terra</span>
                </label>
                <label class="btn btn-outline-secondary active">
                  <input type="radio" name="piani" id="primo" value="1" checked> <span>primo piano</span>
                </label>
                <label class="btn btn-outline-secondary">
                  <input type="radio" name="piani" id="secondo" value="2"> <span>secondo piano</span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div id="svgWrap" class="text-center"></div>
          </div>
        </div>
        <div class="row my-3">
          <div class="col text-center">
            <h1 id="resTitle" class="text-uppercase"></h1>
            <div id="resBody">
              <h4 id="resSubTitle" class="text-muted"></h4>
              <small class="text-muted" id="fuoriVetrinaTxt">Alcuni reperti potrebbero essere esposti "fuori vetrina"</small>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div id="findWrap"></div>
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
