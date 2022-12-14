<?php
  session_start();
  if (!isset($_SESSION['id'])){ header("location:login.php");}
  require 'vendor/autoload.php';
  use \Marta\Dashboard;
  $obj = new Dashboard();
  $dirAssets = 'assets/dashboard/';
  $schedatoriList = $obj->schedatori();
  $checkSchede = $obj->checkSchede();
  $checkRes = $checkSchede['biblio']['count'] + $checkSchede['geo']['count'] + $checkSchede['img']['count'];
  if ($checkRes > 0) {
    $checkBiblioBtn = $checkSchede['biblio']['count'] > 0 ? '<button type="button" class="btn btn-outline-danger m-2 checkBtn" name="checkBtn" value="1"><span>'.$checkSchede['biblio']['count'].'</span> senza bibliografia</button>' : "";
    $checkImgBtn = $checkSchede['img']['count'] > 0 ? '<button type="button" class="btn btn-outline-danger m-2" name="checkBtn" value="2"><span>'.$checkSchede['img']['count'].'</span> senza immagini</button>' : "";
    $checkGeoBtn = $checkSchede['geo']['count'] > 0 ? '<button type="button" class="btn btn-outline-danger m-2" name="checkBtn" value="3"><span>'.$checkSchede['geo']['count'].'</span> senza geolocalizzazione</button>' : "";
    $checkBtn = $checkBiblioBtn.$checkImgBtn.$checkGeoBtn;
  }
  $tot = $obj->schede_operatore();
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
  </head>
  <body>
    <input type="hidden" name="classe" value="<?php echo $_SESSION['classe']; ?>">
    <input type="hidden" name="utente" value="<?php echo $_SESSION['id']; ?>">
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <div id="loadingDiv" class="flexDiv"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main class="bg-light">
      <div class="container-fluid">
        <?php if($_SESSION['classe']!==3){require($dirAssets.'dashboard_amministratore.php');}?>
        <?php if($_SESSION['classe'] ==3){require($dirAssets.'dashboard_operatore.php'); } ?>
      </div>
    </main>
    <?php require($dirAssets.'card_comunicazioni_modifica.php'); ?>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" charset="utf-8"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js" charset="utf-8"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js" charset="utf-8"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js" charset="utf-8"></script>
    <script src="js/wmsTile.js" charset="utf-8"></script>

    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/dashboard.js" charset="utf-8"></script>
  </body>
</html>
