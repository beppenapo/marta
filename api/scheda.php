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

//TODO: controlla se queste funzioni sono ancora usate, eventualmente cancella
function vocabolari($obj){return json_encode($obj->vocabolari($_POST));}
function autocomplete($obj){return json_encode($obj->autocomplete($_POST));}
function getScheda($obj){return json_encode($obj->getScheda($_POST['id'],$_POST['tipo']));}
function addScheda($obj){return json_encode($obj->addScheda($_POST['dati']));}
function editScheda($obj){return json_encode($obj->editScheda($_POST['dati']));}
function deleteScheda($obj){return json_encode($obj->deleteScheda($_POST['id']));}
function listaScheda($obj){return json_encode($obj->listaScheda($_POST['tipo'],'txtSearch'));}
function delbiblioref($obj){return json_encode($obj->delbiblioref($_POST['id_scheda'],$_POST['id_biblio']));}
?>
