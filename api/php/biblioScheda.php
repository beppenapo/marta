<?php
session_start();
if (!isset($_SESSION['id'])){ header("location:login.php");}
require 'vendor/autoload.php';
use \Marta\Scheda;
use \Marta\Biblio;
$sk = new Scheda();
$biblio = new Biblio();
$scheda = $sk->getScheda($_GET['sk']);

?>
