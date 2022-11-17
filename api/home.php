<?php
require '../vendor/autoload.php';
use \Marta\Home;
$obj = new Home();
$funzione = $_POST['trigger'];
unset($_POST['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($obj);
  echo $trigger;
}

function statHome($obj){ return json_encode($obj->statHome()); }
function ogtdStat($obj){ return json_encode($obj->ogtdStat()); }
function nuCronoStat($obj){ return json_encode($obj->nuCronoStat()); }
function miniGallery($obj){ return json_encode($obj->miniGallery()); }
?>
