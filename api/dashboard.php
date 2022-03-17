<?php
require '../vendor/autoload.php';
use \Marta\Dashboard;
$obj = new Dashboard();
$funzione = $_POST['trigger'];
unset($_POST['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($obj);
  echo $trigger;
}

function addComunicazione($obj){ return json_encode($obj->addComunicazione($_POST['testo'])); }
function editComunicazione($obj){ return json_encode($obj->editComunicazione($_POST['id'], $_POST['testo'])); }
function delComunicazione($obj){ return json_encode($obj->delComunicazione($_POST['id'])); }
function comunicazioni($obj){ return json_encode($obj->comunicazioni()); }
function statoSchede($obj){ return json_encode($obj->statoSchede($_POST)); }
function schedatori($obj){ return json_encode($obj->schedatori()); }
?>
