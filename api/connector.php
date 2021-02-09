<?php
require '../vendor/autoload.php';
use \Marta\Test;
$t = new Test();
$funzione = $_POST['trigger'];
unset($_POST['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($t);
  echo $trigger;
}

function test($t){ return json_encode($t->test()); }
?>
