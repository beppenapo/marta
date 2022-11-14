<?php if ($checkRes > 0) { ?>
  <div class="alert alert-danger">
    <h6 class="text-center">Attenzione! Alcune schede non risultano complete. Clicca sui pulsanti per cercarle più facilmente</h6>
    <div class="text-center"><?php echo $checkBtn; ?></div>
  </div>
<?php } else { ?>
  <div class="alert alert-success"><h6 class="text-center">Ottimo! Le tue schede risultano complete</h6></div>
<?php } ?>
