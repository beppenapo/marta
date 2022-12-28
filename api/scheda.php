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
function addScheda($obj){return json_encode($obj->addScheda($_POST['dati']));}
function editScheda($obj){return json_encode($obj->editScheda($_POST['dati']));}
function cloneScheda($obj){return json_encode($obj->cloneScheda($_POST['dati']));}
function delScheda($obj){return json_encode($obj->delScheda($_POST['dati']));}
function delBiblioScheda($obj){return json_encode($obj->delBiblioScheda($_POST['dati']));}

function cambiaStatoScheda($obj){return json_encode($obj->cambiaStatoScheda($_POST));}

function checkNctn($obj){return json_encode($obj->checkNctn());}
function checkTitolo($obj){return json_encode($obj->checkTitolo($_POST['val']));}

function listaSchede($obj){return json_encode($obj->listaSchede($_POST['dati']));}
function mtc($obj){return json_encode($obj->mtc($_POST));}
function ogtdSel($obj){return json_encode($obj->ogtdSel($_POST['dati']));}
function getStatoScheda($obj){return json_encode($obj->getStatoScheda($_POST['id']));}
function getSale($obj){return json_encode($obj->getSale($_POST['piano']));}
function getFoto($obj){return json_encode($obj->getFoto($_POST['id']));}
function getContenitore($obj){return json_encode($obj->getContenitore($_POST));}
function getColonna($obj){return json_encode($obj->getColonna($_POST));}
function setDtzgf($obj){return json_encode($obj->setDtzgf($_POST));}
function uploadImage($obj){return json_encode($obj->uploadImage($_POST,$_FILES));}
function getComuneFromPoint($obj){return json_encode($obj->getComuneFromPoint($_POST['dati']));}
function munsellCode($obj){return json_encode($obj->munsellCode($_POST['gruppo']));}
function tagList($obj){return json_encode($obj->tagList());}
function search($obj){return json_encode($obj->search($_POST['dati']));}
?>
