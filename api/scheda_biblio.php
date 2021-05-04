<?php
require '../vendor/autoload.php';
require_once('../funzioni.php');
use \Marta\Biblio;
$obj = new Biblio();
$funzione = $_POST['trigger'];
unset($_POST['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($obj);
  echo $trigger;
}
function vocabolari($obj){return json_encode($obj->vocabolarioBiblio($_POST));}
function getScheda($obj){return json_encode($obj->getScheda(filtraInt($_POST['id'])));}
function addScheda($obj){return json_encode($obj->addScheda($_POST['dati']));}
function editScheda($obj){return json_encode($obj->editScheda($_POST['dati']));}
function deleteScheda($obj){return json_encode($obj->deleteScheda($_POST['id']));}
function listaScheda($obj){return json_encode($obj->listaScheda(filtraPost('txtSearch')));}
function insbiblioinscheda($obj){return json_encode($obj->insbiblioinscheda(filtraInt($_POST['id_scheda']),filtraInt($_POST['id_biblio'])));}
?>
