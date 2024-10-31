<?php
require '../vendor/autoload.php';
use \Marta\Info;
$obj = new Info();
$funzione = $_POST['trigger'];
unset($_POST['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($obj);
  echo $trigger;
}
function introMuseo($obj){return json_encode($obj->introMuseo());}
?>
