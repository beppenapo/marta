<?php
require '../vendor/autoload.php';
use \Marta\Login;
$obj = new Login();
$funzione = $_POST['trigger'];
unset($_POST['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($obj);
  echo $trigger;
}

function login($obj){ return json_encode($obj->login($_POST)); }
function rescuePwd($obj){ return json_encode($obj->rescuePwd($_POST['email'])); }
?>
