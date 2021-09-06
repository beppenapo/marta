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
  $salaList = $obj->getSale($scheda['lc']['piano']);
  if ($scheda['lc']['piano'] > 0) {
    $labelContenitore = 'Vetrina';
    $contenitore = 'vetrine';
  }else {
    $labelContenitore = 'Scaffale';
    $contenitore = 'scaffali';
  }
  $contenitoreList = $obj->getContenitore(["sala"=>$scheda['lc']['sala'], "contenitore"=>$contenitore]);
  $lcNoVetrine = count($contenitoreList) == 0 ? '' : 'lcSel';
}
$nctnCheck = isset($_POST['s']) ? $checked : '';
$nctnDisabled = isset($_POST['s']) ? '' : 'disabled';
$nctnSelected =isset($_POST['s']) ? $scheda['scheda']['nctn'] : '';
$ogtdDisabled = isset($_POST['s']) ? '' : 'disabled';
$lcView = isset($_POST['s']) ? '' : 'lcSel';
$misCheck = isset($scheda['mt']['mis']['misr']) ? $checked : '';
?>
