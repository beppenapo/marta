<?php
require '../vendor/autoload.php';
use \Marta\Scheda;
$obj = new Scheda();
$funzione = $_POST['trigger'];
unset($_POST['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($obj);
  echo $trigger;
}
function liste($obj){return json_encode($obj->liste($_POST['tipo']));}
function getSale($obj){return json_encode($obj->getSale($_POST['piano']));}
function getContenitore($obj){return json_encode($obj->getContenitore($_POST));}
function getColonna($obj){return json_encode($obj->getColonna($_POST));}
function vocabolari($obj){return json_encode($obj->vocabolari($_POST));}
function autocomplete($obj){return json_encode($obj->autocomplete($_POST));}
function getScheda($obj){return json_encode($obj->getScheda($_POST['id']));}
function addScheda($obj){return json_encode($obj->addScheda($_POST['dati']));}
function editScheda($obj){return json_encode($obj->editScheda($_POST['dati']));}
function deleteScheda($obj){return json_encode($obj->deleteScheda($_POST['id']));}
?>
