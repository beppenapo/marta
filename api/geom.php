<?php
require '../vendor/autoload.php';
use \Marta\Geom;
$obj = new Geom();
$funzione = $_GET['trigger'];
unset($_GET['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($obj);
  echo $trigger;
}
function getComune($obj){return $obj->getComune($_GET['dati']);}
function getVia($obj){return $obj->getVia($_GET['id']);}
function getMarker($obj){return json_encode($obj->getMarker());}
?>
