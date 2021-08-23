<?php
session_start();
switch ($_GET['t']) {
  case 1: $title = "Carica modello 3d"; break;
  case 2: $title = "Carica documento"; break;
  case 3: $title = "Carica foto"; break;
  case 4: $title = "Carica file grafico"; break;
  case 5: $title = "Carica video"; break;
  case 6: $title = "Carica audio"; break;
}
?>
