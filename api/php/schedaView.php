<?php
session_start();
require 'vendor/autoload.php';
use \Marta\Scheda;
$obj = new Scheda();
$scheda = $obj->getScheda($_GET['get']);
$bibScheda = $obj->getBiblioScheda($_GET['get']);
$stato = $obj->getStatoScheda($_GET['get']);
$noData = "<p class='text-secondary m-0'>La sezione non è stata compilata</p>";
$noValue = "<p class='text-secondary m-0'>Il campo non è stata compilato</p>";
$chiudi = !empty($stato['chiusa']) ? 'nascondi' : '';
$riapri = $verifica = $invia = $accettata = 'nascondi';
$modifica = '';
if (!empty($stato['chiusa']) && $_SESSION['classe'] !== 3) {
  $riapri = '';
  $modifica = 'nascondi';
}
if (!empty($stato['chiusa']) && empty($stato['verificata']) && $_SESSION['classe'] !== 3) { $verifica = '';}
if (!empty($stato['verificata']) && empty($stato['inviata']) && ($_SESSION['classe'] == 4 or $_SESSION['classe'] == 1)) { $invia = '';}
if (!empty($stato['inviata']) && empty($stato['accettata']) && ($_SESSION['classe'] == 4 or $_SESSION['classe'] == 1)) {
  $accettata = '';
  $riapri = 'nascondi';
}
?>
