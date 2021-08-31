<?php
session_start();
$formFolder = "assets/form_section/";
$checked = 'checked';

require 'vendor/autoload.php';
use \Marta\Scheda;
$obj = new Scheda();
$listeComuni = $obj->listeComuni();
$listeNU = $obj->listeNu();
$listeRA = $obj->listeRA();
$nctnList = $obj->nctnList();
$furList = $obj->furList();
$munsellList = $obj->munsellList();

if (isset($_POST['s'])) {
  $scheda = $obj->getScheda($_POST['s']);
  $l4List = $obj->ogtdSel(array("tab"=>'ra_cls_l4', "field"=>'l3', "val"=>$scheda['og']['cls3id']));
  $l5List = $obj->ogtdSel(array("tab"=>'ra_cls_l5', "field"=>'l4', "val"=>$scheda['og']['cls4id']));
}
$nctnCheck = isset($_POST['s']) ? $checked : '';
$nctnDisabled = isset($_POST['s']) ? '' : 'disabled';
$nctnSelected =isset($_POST['s']) ? $scheda['scheda']['nctn'] : '';
$ogtdDisabled = isset($_POST['s']) ? '' : 'disabled';
$misCheck = isset($scheda['mt']['mis']['misr']) ? $checked : '';
?>
