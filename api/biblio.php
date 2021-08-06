<?php
require '../vendor/autoload.php';
use \Marta\Biblio;
$obj = new Biblio();
$funzione = $_POST['trigger'];
unset($_POST['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($obj);
  echo $trigger;
}
function listaTipo($obj){return json_encode($obj->listaTipo($_POST));}
function listaLivello($obj){return json_encode($obj->listaLivello($_POST));}
function elencoBiblio($obj){return json_encode($obj->elencoBiblio());}
function getScheda($obj){return json_encode($obj->getScheda($_POST['id']));}
function getContribList($obj){return json_encode($obj->getContribList($_POST['bib']));}
function addBiblio($obj){return json_encode($obj->addBiblio($_POST['dati']));}
function addContrib($obj){return json_encode($obj->addContrib($_POST['dati']));}
function editScheda($obj){return json_encode($obj->editScheda($_POST['dati']));}
function editContributo($obj){return json_encode($obj->editContributo($_POST['dati']));}
function deleteScheda($obj){return json_encode($obj->deleteScheda($_POST['id']));}
function deleteContrib($obj){return json_encode($obj->deleteContrib($_POST['id']));}
function biblioScheda($obj){return json_encode($obj->biblioScheda($_POST['dati']));}
?>
