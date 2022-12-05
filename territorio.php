<?php
  session_start();
  $widthMap = isset($_SESSION['id']) ? "width:calc(100vw - 250px)":"width:100vw ";
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
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
        <div class="card" id="geocoder">
          <div class="card-body p-1">
            <form name="geocoderForm">
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text" data-toggle="tooltip" title="per una ricerca più precisa, oltre alla via, indicare anche il Comune.<br />La ricerca sfrutta il servizio messo a disposizione da Open StreetMap"><i class="fa solid fa-question"></i></span>
                </div>
                <input type="text" class="form-control" name="geocoderInput" placeholder="Cerca indirizzo" required>
                <div class="input-group-append">
                  <button class="btn btn-secondary" type="submit" name="geocoderBtn" data-toggle="tooltip"><i class="fa-solid fa-search"></i></button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div id="geocoderResult" class="list-group"></div>
        <div class="card" id="comuniList">
          <div class="card-header">
            <p class="m-1">Elenco Comuni</p>
          </div>
          <div class="list-group list-group-flush"></div>
        </div>
      </div>
    </main>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js" charset="utf-8"></script>
    <script src="js/wmsTile.js" charset="utf-8"></script>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/territorio.js" charset="utf-8"></script>
  </body>
</html>
