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
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6">
            <div id="svgWrap"></div>
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
