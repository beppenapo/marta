<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/gallery.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if(isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <input type="hidden" name="item" value="<?php echo $_GET['item']; ?>">
    <main class="bg-white">
      <div class="container"></div>
    </main>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/gallery.js" charset="utf-8"></script>
  </body>
</html>
