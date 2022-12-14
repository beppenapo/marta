<div class="row">
  <div class="col">
    <div class="alert alert-info text-center">
      <h5>Ciao <?php echo $_SESSION['utente']; ?>, le <?php if($_SESSION['classe'] == 3){echo "tue";} ?> schede presenti nel database sono <?php echo $tot['schede']; ?></h5>
    </div>
  </div>
</div>
<?php if ($checkRes > 0) { ?>
  <div class="alert alert-danger">
    <h6 class="text-center">Attenzione! <?php echo $checkRes; ?> non risultano complete. Clicca sui pulsanti per cercarle più facilmente</h6>
    <div class="text-center"><?php echo $checkBtn; ?></div>
  </div>
<?php } else { ?>
  <div class="alert alert-success"><h6 class="text-center">Ottimo! Le <?php if($_SESSION['classe'] == 3){echo "tue";} ?> schede risultano complete</h6></div>
<?php } ?>
