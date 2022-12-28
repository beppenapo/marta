<?php
require '../vendor/autoload.php';
use \Marta\Conn;
$obj = new Conn();
$out=[];
if ($_POST['campo'] == 'tsk') {
  if($_POST['val'] == 1){
    $out['cls'] = classeRa($obj);
    $out['ogtd'] = ogtdRa($obj);
  }
  if($_POST['val'] == 2){$out['ogtd'] = ogtdNu($obj);}
  $out['materia'] = materia($obj);
}
if ($_POST['campo'] == 'cls') {$out['ogtd'] = ogtdRa($obj);}
if ($_POST['campo'] == 'dtzgi') {if (isset($_POST['val'])) {$out['dtzgf'] = dtzgf($obj);}else {$out['dtzgi'] = dtzgi($obj);}}
if ($_POST['campo'] == 'dtzgf') {
  if(!isset($_POST['val'])){$out['dtzgf'] = dtzgf($obj);}
}

function dtzgi($obj){
  $sql = "select distinct l.* from liste.cronologia l inner join dt on dt.dtzgi = l.id order by 1 asc;";
  return $obj->simple($sql);
}
function dtzgf($obj){
  $filter = isset($_POST['val']) ? 'where dt.dtzgf >= '.$_POST['val'] : '';
  $sql = "select distinct l.* from liste.cronologia l inner join dt on dt.dtzgf = l.id ".$filter." order by 1 asc;";
  return $obj->simple($sql);
}

function classeRa($obj){
  $sql ="select distinct l.id, l.value from liste.ra_cls_l3 l inner join og_ra on og_ra.l3 = l.id order by 2 asc;";
  return $obj->simple($sql);
}
function ogtdRa($obj){
  $filter = $_POST['campo'] == 'cls' ? 'where og_ra.l3 = '.$_POST['val'] : '';
  $sql ="select distinct l.id, l.value from liste.ra_cls_l4 l inner join og_ra on og_ra.l4 = l.id ".$filter." order by 2 asc;";
  return $obj->simple($sql);
}
function ogtdNu($obj){
  $sql ="select distinct l.id, l.value from liste.ogtd l inner join og_nu on og_nu.ogtd = l.id order by 2 asc;";
  return $obj->simple($sql);
}
function materia($obj){
  $sql ="select distinct l.id, l.value from liste.materia l inner join mtc on mtc.materia = l.id where l.tsk = 0 or l.tsk = ".$_POST['val']." order by 2 asc;";
  return $obj->simple($sql);
}


echo json_encode($out);

?>
