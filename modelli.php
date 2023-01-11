<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/modelli.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if(isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <main class="bg-light">
      <div id="mainTitle" class="my-2 py-2 bg-marta text-white">
        <div>
          <h2>3d Gallery!</h2>
          <p>Galleria di tutti i modelli tridimensionali realizzati per il MArTA.<br />Scegli tra le immagini presenti per attivare il visualizzatore 3d e godere dei reperti sin nei minimi dettagli</p>
        </div>
      </div>
      <div class="bg-white border-top border-bottom p-3">
        <div id="minigallery" class="container">
        </div>
      </div>
      <div id="3dhop" class="container"></div>
    </main>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/sfoglia.js" charset="utf-8"></script>
  </body>
</html>
