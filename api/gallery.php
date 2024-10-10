<?php
require '../vendor/autoload.php';

// $data = json_decode(file_get_contents('php://input'), true);

if (!isset($_POST['filter'])) {
  http_response_code(400); // Bad request
  echo json_encode(["error" => "Nessun item selezionato"]);
  exit;
}

use Marta\Gallery;
$item = $_POST['filter'];
$obj = new Gallery($item);
$items = $obj->getItems($_POST);
echo json_encode($items);

?>
