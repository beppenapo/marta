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
function statoSchede($obj){ return json_encode($obj->statoSchede()); }
function schedatori($obj){ return json_encode($obj->schedatori()); }
function schede($obj){return json_encode($obj->schede($_POST['dati']));}
function biblio($obj){return json_encode($obj->biblio($_POST['dati']));}
?>
