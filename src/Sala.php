<?php
namespace Marta;
use Marta\Scheda;
session_start();

class Sala extends Conn{
  public $scheda;
  function __construct(){
    $this->scheda = new Scheda();
  }


  public function getSchedeByLocation(array $filter){
    $where = [];
    $reperti = [];
    $monete = [];
    $sala = isset($filter['sala']) ? $filter['sala'] : null;
    $res = [];

    if (isset($filter['contenitore']) && isset($filter['piano']) && $filter['piano'] > -1) {
      $vetrina = $this->simple("select vetrina from liste.vetrine where sala = ".$filter['sala']." and id = ".$filter['contenitore'].";");
      if (!empty($vetrina)) {
        $vetrina = $vetrina[0]['vetrina'];
        $filter['contenitore'] = $vetrina;
      }
    }
    foreach ($filter as $key => $value) {
      if (is_string($value)){$out[]="lc.".$key." = '".$value."'";}else{$out[]="lc.".$key." = ".$value;}
    }
    $where = join(" and ", $out);
    $schedeTot = $this->simple("select s.id, s.tsk from scheda s inner join lc on lc.scheda = s.id where ".$where.";" );
    foreach ($schedeTot as $record) {
      if ($record['tsk'] == 1) { $reperti[] = $record['id']; } 
      elseif ($record['tsk'] == 2) { $monete[] = $record['id']; }
    }
    if(count($reperti) > 0){$totReperti = $this->totReperti(1, $reperti);}
    if(count($monete) > 0){$totMonete = $this->totReperti(2, $monete);}
    foreach ($totReperti as $items) {$res['schede'][]=$items;}
    foreach ($totMonete as $items) {$res['schede'][]=$items;}
    $res['sale'] = $this->numSale($filter['piano']);
    $res['contenitori'] = $this->numContenitori($filter['piano'], $sala);
    return $res;
  }

  private function totReperti(int $tsk, array $items){
    $classeId='';
    $classe='';
    $ogtd='';
    $joinTable='';
    if ($tsk == 1){
      $classeId = 'classe.id';
      $classe = 'classe.value';
      $ogtd = 'ogtd.value';
      $joinTable ='JOIN og_ra ON og_ra.scheda = scheda.id
      JOIN liste.ra_cls_l4 ogtd ON og_ra.l4 = ogtd.id
      JOIN liste.ra_cls_l3 l3 ON ogtd.l3 = l3.id
      JOIN liste.ra_cls_l2 l2 ON l3.l2 = l2.id
      JOIN liste.ra_cls_l1 classe ON l2.l1 = classe.id';
    }
    elseif ($tsk == 2){
      $classeId = 11;
      $classe = "'MONETE'::character varying";
      $ogtd = "btrim(concat(ogtd.value, ' ', COALESCE(ogto.value, ''::character varying)))";
      $joinTable = 'JOIN og_nu ON og_nu.scheda = scheda.id
      JOIN liste.ogtd ON og_nu.ogtd = ogtd.id
      LEFT JOIN liste.ogto ON og_nu.ogto = ogto.id';
    }
    $sql = "SELECT scheda.id,
        nctn.nctn,
        ".$classeId." AS classe_id,
        ".$classe." AS classe,
        upper(".$ogtd.") AS ogtd,
        lc.piano,
        sala.sala,
        lc.contenitore,
        file.file
      FROM scheda
      JOIN nctn_scheda nctn ON nctn.scheda = scheda.id
      ".$joinTable."
      join lc on lc.scheda = scheda.id
      join liste.sale sala on lc.sala = sala.id
      join file on file.scheda = scheda.id
      WHERE scheda.tsk = 1
        and file.tipo = 3
        and (substring(file.file from 16 for 3) = '_A_' or substring(file.file from 16 for 4) = '_02_')
        and scheda.id = any(array[".implode(',',$items)."])
    ;";
    return $this->simple($sql);
  }

  public function getReperti(array $dati){
    $sala = isset($dati['sala']) ? $dati['sala'] : null;
    $contenitore = isset($dati['contenitore']) ? $dati['contenitore'] : null;
    $info['sale'] = $this->numSale($dati['piano']);
    $info['contenitori'] = $this->numContenitori($dati['piano'], $sala);
    $info['schede'] = $this->getSchede($dati['piano'], $sala, $contenitore);
    return $info;
  }

  private function numSale(int $piano){
    $v = $this->simple("select count(*) from liste.sale where piano = ".$piano.";");
    return $v[0];
  }

  private function numContenitori(int $piano, int $sala = null){
    if ($piano == -1) {
      $campo = 'scaffale';
      $tab = 'scaffali';
      $fv = '';
    }else {
      $campo = 'vetrina';
      $tab = 'vetrine';
      $fv = " and a.".$campo." != 'fuori vetrina' ";
    }
    $andSala = $sala !== null ? " and a.sala = ".$sala : "";
    $query = "select count(distinct a.".$campo.") from liste.".$tab." a inner join liste.sale b on a.sala = b.id where b.piano = ".$piano.$andSala.$fv.";";
    $v = $this->simple($query);
    return $v[0];
  }

  private function nomeSala(int $id){
    $v = $this->simple("select coalesce(descrizione,sala::text,'') sala from liste.sale where id = ".$id.";");
    return $v[0];
  }

  private function getSchede(int $piano, int $sala = null, int $contenitore = null){
    if ($piano == -1) {
      $lista = 'scaffali';
      $elemento = 'scaffale';
      $cast = '::int';
      $andContenitore = $contenitore !== null ? 'and contenitore.scaffale = '.$contenitore : '';
    }else {
      $lista = 'vetrine';
      $elemento = 'vetrina';
      $cast = '';
      $andContenitore = $contenitore !== null ? 'and contenitore.id = '.$contenitore : '';
    }

    $andSala = $sala !== null ? 'and gallery.sala_id = '.$sala : '';

    $query = "select distinct gallery.*, file.file from gallery, liste.".$lista." contenitore, file where
    contenitore.sala = gallery.sala_id and contenitore.".$elemento." = gallery.contenitore".$cast." and gallery.piano = ".$piano." ".$andSala." ".$andContenitore." and file.scheda = gallery.id order by 6,7,9 asc;";

    $v = $this->simple($query);
    return $v;
  }
}
?>
