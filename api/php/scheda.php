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
$munsellGroup = $obj->munsellGroup();
$munsellCode = $obj->munsellCode();
$adspArr=$obj->vocabolari(array("tab"=>'liste.adsp', "order"=>1));
$adsmArr=$obj->vocabolari(array("tab"=>'liste.adsm', "order"=>1));

$comuniPuglia = $obj->comuniPuglia();
//Nel form di inserimento questi elementi partono nascosti
$lcViewColonna = $lcViewRipiano = $lcNoVetrine = 'lcSel';
//********************************************//
// Questa sezione gestisce il form di modifica
//*******************************************//
if (isset($_POST['s'])) {
  //recupero i dati della scheda e le liste fisse
  $scheda = $obj->getScheda($_POST['s']);
  if ($scheda['scheda']['tskid'] == 1) {
    $l4List = $obj->ogtdSel(array("tab"=>'ra_cls_l4', "field"=>'l3', "val"=>$scheda['og']['cls3id']));
    $l5List = $obj->ogtdSel(array("tab"=>'ra_cls_l5', "field"=>'l4', "val"=>$scheda['og']['cls4id']));
  }
  $salaList = $obj->getSale($scheda['lc']['piano']);

  //************* sezione LC *********************//
  // creo la label della lista in base al piano
  $labelContenitore = $scheda['lc']['piano'] > 0 ? 'Vetrina' : 'Scaffale';
  $labelLista = $scheda['lc']['piano'] > 0 ? 'vetrina' : 'scaffale';
  $lcViewCassetta = $scheda['lc']['piano'] > 0 ? 'lcSel' : '';
  // sempre in base al piano decido da quale tabella creare la lista precedente
  $contenitore = $scheda['lc']['piano'] > 0 ? 'vetrine' : 'scaffali';
  $contenitoreList = $obj->getContenitore(array("sala"=>$scheda['lc']['id_sala'], "contenitore"=>$contenitore));

  // se la sala non ha scaffali o vetrine mostra l'avviso
  if (count((array)$contenitoreList) == 0) {
    $lcNoVetrine = '';
    $lcView = 'lcSel';
    $lcViewCassetta = 'lcSel';
  }

  // preparo l'array per le options della lista contenitore
  $selContenitore = [];
  // se il contenitore è specificato aggiungo l'attributo 'selected' al contenitore salvato
  if (is_numeric($scheda['lc']['contenitore'])) {
    $firstContenitore = "<option value=''>-- ".$labelLista." --</option>";
    array_push($selContenitore,$firstContenitore);
    foreach ($contenitoreList as $key => $val) {
      $sel = $val['c'] == $scheda['lc']['contenitore'] ? 'selected' : '';
      array_push($selContenitore,"<option value='".$val['c']."' ".$sel.">".$val['c']." ".$val['note']."</option>");
    }
    // visualizzo la lista valori per le colonne
    $lcViewColonna = '';
    // preparo l'array per le options della lista colonne
    $selColonna = [];
    // recupero i dati sulle colonne della sala e del contenitore specificati
    $colonnaList = $obj->getColonna(array("sala"=>$scheda['lc']['sala'], "scaffale"=>$scheda['lc']['contenitore']));
    // se nel db c'è una colonna specificata le aggiungo l'attributo 'selected'
    if (is_numeric($scheda['lc']['colonna'])) {
      $firstColonna = "<option value=''>--colonna--</option>";
      array_push($selColonna,$firstColonna);
      foreach ($colonnaList as $key => $val) {
        $sel = $val['val'] == $scheda['lc']['colonna'] ? 'selected' : '';
        array_push($selColonna,"<option value='".$val['val']."' ".$sel.">".$val['colonna']."</option>");
      }
      $lcViewRipiano = '';
      $selRipiano=[];
      switch (true) {
        case $scheda['lc']['contenitore'] == 40:
          $labelRipiano = 'Plateau';
          $slot = setCassetti(104);
        break;
        case $scheda['lc']['contenitore'] == 41 && $scheda['lc']['colonna'] == 1:
          $labelRipiano = 'Cassetto';
          $slot = setCassetti(56);
        break;
        case $scheda['lc']['contenitore'] == 41 && ($scheda['lc']['colonna'] >= 2 && $scheda['lc']['colonna'] <= 3):
          $labelRipiano = 'Ripiano';
          $slot = setCassetti(4);
        break;
        case $scheda['lc']['contenitore'] == 41 && $scheda['lc']['colonna'] == 4:
          $labelRipiano = 'Cassetto';
          $slot = setCf4();
        break;
        default:
          $labelRipiano = 'Ripiano';
          $slot = setCassetti(10);
      }
      if ($scheda['lc']['ripiano'] !== 'dato non inserito o assente') {
        $firstRipiano = "<option value=''>--".$labelRipiano."--</option>";
        array_push($selRipiano,$firstRipiano);
        foreach ($slot as $key => $val) {
          $sel = $val == $scheda['lc']['ripiano'] ? 'selected' : '';
          array_push($selRipiano,"<option value='".$val."' ".$sel.">".$val."</option>");
        }
      }else {
        $firstRipiano = "<option value='' selected>--".$labelRipiano."--</option>";
        array_push($selRipiano,$firstRipiano);
        foreach ($slot as $key => $val) {
          array_push($selRipiano,"<option value='".$val."'>".$val."</option>");
        }
      }
    }else {
      $firstColonna = "<option value='' selected>--colonna--</option>";
      array_push($selColonna,$firstColonna);
      foreach ($colonnaList as $key => $val) {
        array_push($selColonna,"<option value='".$val['val']."'>".$val['colonna']."</option>");
      }
    }
  }else {
  // se non è stato inserito il contenitore, seleziono il primo valore della lista
    $firstContenitore = "<option value='' selected>-- ".$labelLista." --</option>";
    array_push($selContenitore,$firstContenitore);
    foreach ($contenitoreList as $key => $val) {
      array_push($selContenitore,"<option value='".$val['c']."'>".$val['c']." ".$val['note']."</option>");
    }
  }
  //**********************************************//
  //************* sezione UB *********************//
  $ubCheckLabel = count((array)$scheda['ub']) == 0 ? 'Compila dati sezione' : 'Cancella dati sezione';
  $ubRequired = count((array)$scheda['ub']) == 0 ? 'disabled' : 'required';
  $ubDisabled = count((array)$scheda['ub']) == 0 ? 'disabled' : '';
  $ubLabelClass = count((array)$scheda['ub']) == 0 ? '' : 'text-danger';
  $stimArr=$obj->vocabolari(array("tab"=>'liste.stim'));
  $stimList=$obj->buildSel($stimArr,$scheda['ub']['idstim']);
  //**********************************************//
  //************* sezione GP *********************//
  $viaDisabled = count((array)$scheda['geoloc']) == 0 ? 'disabled' : '';

  $gpCheckLabel = count((array)$scheda['gp']) == 0 ? 'Compila dati sezione' : 'Cancella dati sezione';
  $gpRequired = count((array)$scheda['gp']) == 0 ? 'disabled' : 'required';
  $gpDisabled = count((array)$scheda['gp']) == 0 ? 'disabled' : '';
  $gpLabelClass = count((array)$scheda['gp']) == 0 ? '' : 'text-danger';
  $gplArr=$obj->vocabolari(array("tab"=>'liste.gpl'));
  $gplList=$obj->buildSel($gplArr,$scheda['gp']['gplid']);
  $gppArr=$obj->vocabolari(array("tab"=>'liste.gpp'));
  $gppList=$obj->buildSel($gppArr,$scheda['gp']['gppid']);
  $gpmArr=$obj->vocabolari(array("tab"=>'liste.gpm'));
  $gpmList=$obj->buildSel($gpmArr,$scheda['gp']['gpmid']);
  $gptArr=$obj->vocabolari(array("tab"=>'liste.gpt'));
  $gptList=$obj->buildSel($gptArr,$scheda['gp']['gptid']);
  //**********************************************//
  //************* sezione RCG *********************//
  $rcgCheckLabel = count((array)$scheda['re']['rcg']) == 0 ? 'Compila dati sezione' : 'Cancella dati sezione';
  $rcgRequired = count((array)$scheda['re']['rcg']) == 0 ? 'disabled' : 'required';
  $rcgDisabled = count((array)$scheda['re']['rcg']) == 0 ? 'disabled' : '';
  $rcgLabelClass = count((array)$scheda['re']['rcg']) == 0 ? '' : 'text-danger';
  //**********************************************//
  //************* sezione DSC *********************//
  $dscCheckLabel = count((array)$scheda['re']['dsc']) == 0 ? 'Compila dati sezione' : 'Cancella dati sezione';
  $dscRequired = count((array)$scheda['re']['dsc']) == 0 ? 'disabled' : 'required';
  $dscDisabled = count((array)$scheda['re']['dsc']) == 0 ? 'disabled' : '';
  $dscLabelClass = count((array)$scheda['re']['dsc']) == 0 ? '' : 'text-danger';
  //**********************************************//
  //************* sezione AIN *********************//
  $ainCheckLabel = count((array)$scheda['re']['ain']) == 0 ? 'Compila dati sezione' : 'Cancella dati sezione';
  $ainRequired = count((array)$scheda['re']['ain']) == 0 ? 'disabled' : 'required';
  $ainDisabled = count((array)$scheda['re']['ain']) == 0 ? 'disabled' : '';
  $ainLabelClass = count((array)$scheda['re']['ain']) == 0 ? '' : 'text-danger';
  $aintArr=$obj->vocabolari(array("tab"=>'liste.aint'));
  $aintList=$obj->buildSel($gptArr,$scheda['re']['ain']['aintid']);
  //**********************************************//
  //************* sezione DTZ *********************//
  $cronoArr=$obj->vocabolari(array("tab"=>'liste.cronologia', "order"=>1));
  $dtzgiList=$obj->buildSel($cronoArr,$scheda['dt']['dt']['ciid']);
  $dtzgfList=$obj->buildSel($cronoArr,$scheda['dt']['dt']['cfid']);
  $dtzsArr=$obj->vocabolari(array("tab"=>'liste.dtzs'));
  $dtzsList=$obj->buildSel($dtzsArr,$scheda['dt']['dt']['dtzsid']);
  //**********************************************//
  //************* sezione DTS *********************//
  $dtsCheckLabel = !isset($scheda['dt']['dt']['dtsi']) ? 'Compila dati sezione' : 'Cancella dati sezione';
  $dtsDisabled = isset($scheda['dt']['dt']['dtsi']) ? '' : 'disabled';
  $dtsRequired = isset($scheda['dt']['dt']['dtsi']) ? 'required' : 'disabled';
  // $dtsLabelClass = isset($scheda['dt']['dt']['dtsi']) ? 'text-danger' : '';
  //**********************************************//
  //************* sezione DTM *********************//
  $dtmArr = [];
  foreach ($scheda['dt']['dtm'] as $i) {
    $item = "<div class='input-group mt-3' id='dtm".$i['dtmid']."'>";
    $item .= "<input type='text' class='form-control form-control-sm' name='dtmText' value='".$i['dtm']."' disabled/>";
    $item .= "<div class='inupt-group-append'><button type='button' name='delDtmOpt' class='btn btn-danger btn-sm' value='".$i['dtmid']."'><i class='fas fa-times'></i></button></div>";
    $item .= "</div>";
    array_push($dtmArr,$item);
  }
  //**********************************************//
  //************* sezione MTC ********************//
  $mtcItems = mtcItems($scheda['mt']['mtc'],$obj);
  //**********************************************//
  //************* sezione MIS ********************//
  $misCheck = isset($scheda['mt']['mis']['misr']) ? $checked : '';
  $misDisabled = isset($scheda['mt']['mis']['misr']) ? 'disabled' : '';
  //**********************************************//
  //************* sezione STC ********************//
  $stccArr=$obj->vocabolari(array("tab"=>'liste.stcc'));
  $stccList=$obj->buildSel($stccArr,$scheda['co']['stccid']);
  $stclArr=$obj->vocabolari(array("tab"=>'liste.stcl'));
  $stclList=$obj->buildSel($stclArr,$scheda['co']['stclid']);
  //**********************************************//
  //************* sezione ACQ ********************//
  $acqCheck = count((array)$scheda['tu']['acq']) == 0 ? '' : 'checked';
  $acqRequired = count((array)$scheda['tu']['acq']) == 0 ? 'disabled' : 'required';
  $acqDisabled = count((array)$scheda['tu']['acq']) == 0 ? 'disabled' : '';
  $acqLabelClass = count((array)$scheda['tu']['acq']) == 0 ? '' : 'text-danger';
  $acqtArr=$obj->vocabolari(array("tab"=>'liste.acqt'));
  $acqtList=$obj->buildSel($acqtArr,$scheda['tu']['acq']['acqtid']);
  //**********************************************//
  //************* sezione CDG ********************//
  $cdggArr=$obj->vocabolari(array("tab"=>'liste.cdgg'));
  $cdggList=$obj->buildSel($cdggArr,$scheda['tu']['cdg']['cdggid']);
  //**********************************************//
  //************* sezione NVC ********************//
  $nvcCheck = count((array)$scheda['tu']['nvc']) == 0 ? '' : 'checked';
  $nvcRequired = count((array)$scheda['tu']['nvc']) == 0 ? 'disabled' : 'required';
  $nvcDisabled = count((array)$scheda['tu']['nvc']) == 0 ? 'disabled' : '';
  $nvcLabelClass = count((array)$scheda['tu']['nvc']) == 0 ? '' : 'text-danger';
  $nvctArr=$obj->vocabolari(array("tab"=>'liste.nvct'));
  $nvctList=$obj->buildSel($nvctArr,$scheda['tu']['nvc']['nvctid']);
  //**********************************************//
  //************* sezione AD *********************//
  $adSectDiv = adSectDiv($scheda['ad'], $adspArr, $adsmArr);
  //**********************************************//
}else {
  $misCheck = $checked;
  $ubCheckLabel = $gpCheckLabel = $rcgCheckLabel = $dscCheckLabel = $ainCheckLabel = $dtsCheckLabel = $acqCheckLabel = $nvcCheckLabel = 'Compila dati sezione';
  $ubLabelClass = $gpLabelClass = $rcgLabelClass = $dscLabelClass = $ainLabelClass = $dtsLabelClass = $acqLabelClass = $nvcLabelClass = '';
  $ubRequired = $gpRequired = $rcgRequired = $dscRequired = $ainRequired = $dtsRequired = $ubDisabled = $gpDisabled = $rcgDisabled = $dscDisabled = $ainDisabled = $dtsDisabled = $misDisabled = $nvcRequired = $nvcDisabled = $acqRequired = $acqDisabled = $viaDisabled = 'disabled';

  $stimList = $listeComuni['stim'];
  $gplList = $listeComuni['gpl'];
  $gppList = $listeComuni['gpp'];
  $gpmList = $listeComuni['gpm'];
  $gptList = $listeComuni['gpt'];
  $aintList = $listeComuni['aint'];
  $dtzgiList = $listeComuni['dtzg'];
  $dtzsList = $listeComuni['dtzs'];
  $adSectDiv = adSectDiv(null,$adspArr, $adsmArr);
  $stccList = $listeComuni['stcc'];
  $stclList = $listeComuni['stcl'];
  $cdggList = $listeComuni['cdgg'];
  $nvctList = $listeComuni['nvct'];
  $acqtList = $listeComuni['acqt'];

  $lcViewCassetta = 'lcSel';
}
$nctnCheck = isset($_POST['s']) && $_POST['act'] == 'mod' ? $checked : '';
$nctnDisabled = isset($_POST['s']) && $_POST['act'] == 'mod' ? '' : 'disabled';
$nctnSelected =isset($_POST['s']) && $_POST['act'] == 'mod' ? $scheda['scheda']['nctn'] : '';

switch ($_POST['act']) {
  case 'mod': $titolo = $scheda['scheda']['titolo']; break;
  //case 'clone': $titolo = date("ymdHis")."-".$scheda['scheda']['titolo']; break;
  default: ''; break;
}

$ogtdDisabled = isset($_POST['s']) ? '' : 'disabled';
$dtzgDisabled = isset($_POST['s']) ? '' : 'disabled';
// $lcView = isset($_POST['s']) ? '' : 'lcSel';


function setCf4(){ return explode(',',"A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U");}

function setCassetti($n){
  $cassetti = array();
  for ($i=1; $i < $n + 1; $i++) { array_push($cassetti,$i); }
  return $cassetti;
}
function adSectDiv(array $dati = null, $adspArr, $adsmArr){
  $adDiv = [];
  $adsp = [];
  $adsm = [];
  foreach ($adspArr as $item) {
    if (isset($dati)) {
      $c = $item['id'] == $dati['adspid'] ? 'checked' : '';
    }else {
      $c = $item['id'] == 1 ? 'checked' : '';
    }
    $div = '<div class="custom-control custom-radio">';
    $div .= '<input type="radio" id="adsp'.$item['id'].'" name="adsp" class="custom-control-input tab" data-table="ad" value="'.$item['id'].'" required '.$c.'>';
    $div .= '<label class="custom-control-label" for="adsp'.$item['id'].'">'.$item['value'].'<i class="far fa-question-circle" data-toggle="tooltip" data-placement="top" title="'.$item['tooltip'].'"></i>
    </label>';
    $div .= '</div>';
    array_push($adsp,$div);
  }
  foreach ($adsmArr as $item) {
    if (isset($dati)) {
      $c = $item['id'] == $dati['adsmid'] ? 'checked' : '';
    }else {
      $c = $item['id'] == 1 ? 'checked' : '';
    }
    $div = '<div class="custom-control custom-radio">';
    $div .= '<input type="radio" id="adsm'.$item['id'].'" name="adsm" class="custom-control-input tab" data-table="ad" value="'.$item['id'].'" required '.$c.'>';
    $div .= '<label class="custom-control-label" for = "adsm' .$item['id'] . '">'.$item['value'].'</label>';
    $div .= '</div>';
    array_push($adsm,$div);
  }
  $adDiv['adsp'] = $adsp;
  $adDiv['adsm'] = $adsm;
  return $adDiv;
}

function mtcItems(array $dati, $obj){
  $out = [];
  foreach ($dati as $i) {
    $item = "<div class='form-row mb-3' id='".str_replace(" ","-",$i['materia'])."Row'>";
    $item .= "<div class='col-md-5'>";
    $item .= "<input type='text' class='form-control form-control-sm' name='materiaLabel' value='".$i['materia']."' disabled />";
    $item .= "<input type='hidden' name='materiaItem' value='".$i['materiaid']."' disabled />";
    $item .= "</div>";
    $item .= "<div class='col-md-6'>";
    $item .= "<input type='text' class='form-control form-control-sm' name='tecnicaItem' value='".$i['tecnica']."' disabled />";
    $item .= "</div>";
    $item .= "<div class='col-md-1'>";
    $item .= "<button type='button' class='btn btn-danger btn-sm' name='delMateriaItem' value='#".str_replace(" ","-",$i['materia'])."Row'><i class='fas fa-times'></i></button>";
    $item .= "</div>";
    $item .= "</div>";
    array_push($out,$item);
  }
  return $out;
}
?>
