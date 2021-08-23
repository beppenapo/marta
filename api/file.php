<?php
require '../vendor/autoload.php';
use \Marta\File;
$obj = new File();
$funzione = $_POST['trigger'];
unset($_POST['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($obj);
  echo $trigger;
}

function uploadImage($obj){return json_encode($obj->uploadImage($_POST,$_FILES));}
?>
