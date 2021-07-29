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
function getSale($obj){return json_encode($obj->getSale($_POST['piano']));}
function getContenitore($obj){return json_encode($obj->getContenitore($_POST));}
function getColonna($obj){return json_encode($obj->getColonna($_POST));}
function setCrono($obj){return json_encode($obj->setCrono($_POST));}
function mtc($obj){return json_encode($obj->mtc($_POST));}
function addScheda($obj){return json_encode($obj->addScheda($_POST['dati']));}
function listaSchede($obj){return json_encode($obj->listaSchede($_POST['dati']));}
function ogtdSel($obj){return json_encode($obj->ogtdSel($_POST['dati']));}
?>
