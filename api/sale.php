<?php
require '../vendor/autoload.php';
use \Marta\Sala;
$obj = new Sala();
$funzione = $_POST['trigger'];
unset($_POST['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($obj);
  echo $trigger;
}
function getReperti($obj){return json_encode($obj->getReperti($_POST['dati']));}
// function getRepertiSala($obj){return json_encode($obj->getRepertiSala($_POST['dati']));}
?>
