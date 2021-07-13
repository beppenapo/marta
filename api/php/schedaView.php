<?php
session_start();
require 'vendor/autoload.php';
use \Marta\Scheda;
$obj = new Scheda();
$scheda = $obj->getScheda($_GET['get']);


?>
