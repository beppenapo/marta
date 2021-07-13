<?php
session_start();
$formFolder = "assets/form_section/";

require 'vendor/autoload.php';
use \Marta\Scheda;
$obj = new Scheda();
$listeComuni = $obj->listeComuni();
$listeNU = $obj->listeNu();
$listeRA = $obj->listeRA();
$nctnList = $obj->nctnList();
$munsellList = $obj->munsellList();

?>
