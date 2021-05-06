<?php
require '../vendor/autoload.php';
use \Marta\Home;
$obj = new Home();
$funzione = $_POST['trigger'];
unset($_POST['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($obj);
  echo $trigger;
}

function statHome($obj){ return json_encode($obj->statHome()); }
?>
