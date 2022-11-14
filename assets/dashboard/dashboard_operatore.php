<div class="alert alert-info text-center">
  <h5>Ciao <?php echo $_SESSION['utente']; ?>, le tue schede presenti nel database sono <?php echo $tot['schede']; ?></h5>
</div>
<?php require($dirAssets.'check_schede_alert.php'); ?>
<div class="row">
  <div class="col-md-4"><?php require($dirAssets.'card_filtra_schede.php'); ?></div>
  <div class="col-md-8"><?php require($dirAssets.'card_biblio.php'); ?></div>
</div>
<div class="row">
  <div class="col-12"><?php require($dirAssets.'card_mappa.php'); ?></div>
</div>
<div class="row">
  <div class="col-md-8"><?php require($dirAssets.'card_utenti.php'); ?></div>
  <div class="col-md-4"><?php require($dirAssets.'card_comunicazioni.php'); ?></div>
</div>
