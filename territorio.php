<?php
  session_start();
  $widthMap = isset($_SESSION['id']) ? "width:calc(100vw - 250px)":"width:100vw ";
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php 
      require('assets/meta.html');
      require('assets/loading.html');
    ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="css/territorio.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <main>
      <div id="map" style="<?php echo $widthMap; ?>">
        <div id="mapGallery" class="invisible">
          <div id="galleryHeader" class="bg-light">
            <h3 class="d-inline-block"></h3>
            <button type="button" id="closeGallery" class="close" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div id="totalItems"></div>
        </div>
        <div class="card" id="geocoder">
          <div class="card-body p-1">
            <div id="geocoderForm">
              <div class="btn-group  btn-group-sm">
                <button type="button" class="btn btn-light" id="myZoomIn" data-toggle="tooltip" title="zoom in"><i class="fa-solid fa-plus"></i></button>
                <button type="button" class="btn btn-light" id="myZoomOut" data-toggle="tooltip" title="zoom out"><i class="fa-solid fa-minus"></i></button>
                <button type="button" class="btn btn-light" id="myZoomReset" data-toggle="tooltip" title="zoom reset"><i class="fa-solid fa-crosshairs"></i></button>
              </div>
              <div class="input-group input-group-sm" id="geoInput">
                <div class="input-group-prepend">
                  <span class="input-group-text" data-toggle="tooltip" title="Cerca uno dei Comuni presenti in mappa, se il Comune non è presente in lista significa che non ha reperti schedati.<br>Tra parentesi il numero di reperti presenti sul territorio del Comune"><i class="fa solid fa-question"></i></span>
                </div>
                <input type="text" class="form-control" id="geocoderInput" placeholder="Cerca Comune" required>
              </div>
            </div>
            <div id="geocoderResult" class="list-group  list-group-flush"></div>
          </div>
        </div>
        
        <div id="legenda" class="bg-white">
          <header class="bg-light p-1 mb-2 border-bottom">Totale reperti</header>
          <div id="scaleDiv"></div>
        </div>
      </div>
    </main>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chroma-js/2.1.0/chroma.min.js"></script>
    <script src="js/wmsTile.js" charset="utf-8"></script>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/territorio.js" charset="utf-8"></script>
  </body>
</html>
