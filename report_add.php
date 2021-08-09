<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['classe'] > 1){ header("location:403.php");}
require 'vendor/autoload.php';
use \Marta\User;

$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/scheda.css">
    <style media="screen">
      [name=report]{width:100%;position:relative;height:60vh !important;}
    </style>
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php require('assets/mainMenu.php'); ?>
    <main>
      <div class="container">
        <div class="row mb-4">
          <div class="col">
            <h3 class="border-bottom">Crea report</h3>
          </div>
        </div>
        <form id="addReportForm" autocomplete="off">
          <input type="hidden" name="utente" value="<?php echo $_SESSION['id']; ?>">
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <div class="form-row mb-3">
                <div class="col-md-4">
                  <label for="data" class="font-weight-bold text-danger">data, se diversa da oggi</label>
                  <input type="date" id="report" name="data" value="<?php echo $today; ?>" min="2020-01-01" max="<?php echo $today; ?>" class="form-control form-control-sm" required>
                </div>
              </div>
              <div class="form-row mb-3">
                <div class="col">
                  <label for="report" class="font-weight-bold text-danger">scrivi testo</label>
                  <textarea class="form-control form-control-sm" id="report" name="report" required></textarea>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-6">
                <button type="submit" class="btn btn-sm btn-marta tastischeda" name="submit" id="submit">salva dati</button>
                <a href="report.php" class="btn btn-sm btn-outline-secondary">annulla operazione</a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </main>
    <?php require('assets/toast.html'); ?>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/report_add.js" charset="utf-8"></script>
  </body>
</html>
