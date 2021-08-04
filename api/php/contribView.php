<?php
session_start();
require 'vendor/autoload.php';
// use \Marta\Scheda;
use \Marta\Biblio;
// $sk = new Scheda();
$biblio = new Biblio();

$contributo = $biblio->getContrib($_GET['get']);
$schede = $biblio->getSchedeContrib($_GET['get']);

?>
