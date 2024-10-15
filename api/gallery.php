<?php
require '../vendor/autoload.php';

if (!isset($_POST['filter'])) {
  http_response_code(400);
  echo json_encode(["error" => "Nessun item selezionato"]);
  exit;
}

use Marta\Gallery;
$item = $_POST['filter'];
$obj = new Gallery($item);
$items = $obj->getItems($_POST);
echo json_encode($items);

?>
