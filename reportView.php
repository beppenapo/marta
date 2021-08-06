<?php
session_start();
require 'vendor/autoload.php';
use \Marta\User;
$obj = new User();
$report = $obj->listaReport($_GET['get']);
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/main.css">
    <style media="screen">
    body>main {padding-top: 60px !important;}
    </style>
  </head>
  <body>
    <input type="hidden" name="idReport" value="<?php echo $_GET['get']; ?>">
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <main>
      <?php if (isset($_SESSION['id']) && ($_SESSION['id'] = $report[0]['usr'])) { ?>
      <div id="menuReport" class="bg-dark px-3">
        <div class="btn-group" role="group">
          <a id="modifica" class="btn btn-dark" href="report_mod.php?mod=<?php echo $_GET['get']; ?>"><i class="fas fa-edit"></i> modifica</a>
          <button id="reportDel" name="reportDel" type="button" class="btn btn-dark"><i class="fas fa-times"></i> elimina</button>
        </div>
      </div>
    <?php } ?>
      <div class="container-fluid mt-5">
        <h3 id="title" class="border-bottom border-dark mb-5"><?php echo "Report scritto il ".$report[0]['data'].", da ".$report[0]['utente']; ?></h3>
        <div class="row">
          <div class="col">
            <p><?php echo nl2br($report[0]['report']); ?></p>
          </div>
        </div>
      </div>
    </main>
    <?php require('assets/toast.html'); ?>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/reportView.js" charset="utf-8"></script>
  </body>
</html>
