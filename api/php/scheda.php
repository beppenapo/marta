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

//Nel form di inserimento questi elementi partono nascosti
$lcViewContenitore = $lcViewColonna = $lcViewRipiano = $lcNoVetrine = 'lcSel';

// Questa sezione gestisce il form di modifica
if (isset($_POST['s'])) {
  //recupero i dati della scheda e le liste fisse
  $scheda = $obj->getScheda($_POST['s']);
  $l4List = $obj->ogtdSel(array("tab"=>'ra_cls_l4', "field"=>'l3', "val"=>$scheda['og']['cls3id']));
  $l5List = $obj->ogtdSel(array("tab"=>'ra_cls_l5', "field"=>'l4', "val"=>$scheda['og']['cls4id']));
  $salaList = $obj->getSale($scheda['lc']['piano']);

  // creo la label della lista in base al piano
  $labelContenitore = $scheda['lc']['piano'] > 0 ? 'Vetrina' : 'Scaffale';

  // sempre in base al piano decido da quale tabella creare la lista precedente
  $contenitore = $scheda['lc']['piano'] > 0 ? 'vetrine' : 'scaffali';
  $contenitoreList = $obj->getContenitore(array("sala"=>$scheda['lc']['sala'], "contenitore"=>$contenitore));

  // se la sala non ha scaffali o vetrine mostra l'avviso
  if (count($contenitoreList) == 0) {$lcNoVetrine = '';}

  if (condition) {
    // code...
  }
  // if (is_numeric($scheda['lc']['contenitore'])) {
  //   $contenitoreList = $obj->getContenitore(["sala"=>$scheda['lc']['sala'], "contenitore"=>$contenitore]);
  //   if (count($contenitoreList) > 0) {
  //     $colonnaList = $obj->getColonna(array("sala"=>$scheda['lc']['sala'], "scaffale"=>$scheda['lc']['contenitore']));
  //   }else {
  //     $colonnaList = 'le colonne esistono ma non sono state inserite';
  //   }
  // }
  // $lcNoVetrine = count($contenitoreList) == 0 ? '' : 'lcSel';
  // $lcContenitore = count($contenitoreList) > 0 ? '' : 'lcSel';
  $misCheck = isset($scheda['mt']['mis']['misr']) ? $checked : '';
}
$nctnCheck = isset($_POST['s']) ? $checked : '';
$nctnDisabled = isset($_POST['s']) ? '' : 'disabled';
$nctnSelected =isset($_POST['s']) ? $scheda['scheda']['nctn'] : '';
$ogtdDisabled = isset($_POST['s']) ? '' : 'disabled';
$lcView = isset($_POST['s']) ? '' : 'lcSel';
$misCheck = $checked;
?>
