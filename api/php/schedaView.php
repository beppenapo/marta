<?php
session_start();
require 'vendor/autoload.php';
use \Marta\Scheda;
$obj = new Scheda();
$scheda = $obj->getScheda($_GET['get']);
$bibScheda = $obj->getBiblioScheda($_GET['get']);
$noData = "<p class='text-secondary'>La sezione non è stata compilata</p>";

?>
