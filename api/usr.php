<?php
require '../vendor/autoload.php';
use \Marta\User;
$obj = new User();
$funzione = $_POST['trigger'];
unset($_POST['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($obj);
  echo $trigger;
}
function getUser($obj){
  $id = isset($_POST['id']) ? $_POST['id'] : null;
  return json_encode($obj->getUser($id));
}
function classList($obj){ return json_encode($obj->classList()); }
function aggiungiUsr($obj){ return json_encode($obj->aggiungiUsr($_POST)); }
function modificaUsr($obj){ return json_encode($obj->modificaUsr($_POST)); }
function modificaPassword($obj){return json_encode($obj->modificaPassword($_POST));}
function addReport($obj){return json_encode($obj->addReport($_POST['dati']));}
function editReport($obj){return json_encode($obj->editReport($_POST['dati']));}
function listaReport($obj){return json_encode($obj->listaReport());}
function deleteReport($obj){return json_encode($obj->deleteReport($_POST['id']));}
?>
