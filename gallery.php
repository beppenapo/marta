<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/gallery.css">
  </head>
  <body>
    <?php 
      require('assets/loading.html'); 
      require('assets/headerMenu.php'); 
    ?>
    <?php if(isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <input type="hidden" name="item" value="<?php echo $_GET['item']; ?>">
    <main class="bg-white">
      <div id="filterBar">
        <a href="gallery.php?item=reperti" class="btn btn-sm btn-marta text-white">reperti</a>
        <a href="gallery.php?item=monete" class="btn btn-sm btn-marta text-white">monete</a>
        <a href="gallery.php?item=immagini" class="btn btn-sm btn-marta text-white">fotografie</a>
        <a href="gallery.php?item=stereo" class="btn btn-sm btn-marta text-white"><span class="d-none d-lg-inline">foto</span> stereo</a>
        <a href="gallery.php?item=modelli" class="btn btn-sm btn-marta text-white"><span class="d-none d-lg-inline">modelli</span> 3d</a>
      </div>
      <div id="wrapItems"></div>
    </main>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/gallery.js" charset="utf-8"></script>
  </body>
</html>
