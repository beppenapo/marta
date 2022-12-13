<?php
namespace Marta;
session_start();
use \Marta\Conn;

class Home extends Conn{
  public $totra = 20000;
  public $totnu = 20000;
  public $totfoto = 80000;
  public $totstereo = 5000;
  public $tot3d = 110;
  function __construct(){}

  public function statHome(){
    $ra = $this->simple("select count(*) from scheda where tsk = 1;");
    $nu = $this->simple("select count(*) from scheda where tsk = 2;");
    $foto = $this->simple("select count(*) from file where tipo = 3;");
    $stereo = $this->simple("select count(*) from file where tipo = 2;");
    $modelli = $this->simple("select count(*) from file where tipo = 1;");
    $arr = array( "ra"=>$ra[0]['count'], "nu"=>$nu[0]['count'], "foto"=>$foto[0]['count'], "stereo"=>$stereo[0]['count'], "modelli"=>$modelli[0]['count']);
    return $arr;
  }

  public function ogtdStat(){
    $sql = "with tot as (select count(*) from og_ra),
    ra as (select l4, count(*) from og_ra group by l4)
    select ra.l4, l4.value as ogtd, ra.count, ((ra.count * 100 ) / tot.count)::int as perc
    from ra, tot, liste.ra_cls_l4 l4
    where ra.l4 = l4.id
    group by ra.l4, ra.count, tot.count, l4.value
    HAVING ((ra.count * 100 ) / tot.count)::int > 1
    order by 3 desc;";
    return $this->simple($sql);
  }

  public function nuCronoStat(){
    $sql = "SELECT crono.id, crono.value as cronologia, count(dt.*) from dt inner join liste.cronologia crono on dt.dtzgi = crono.id group by crono.id, crono.value having count(dt.*) > 30 order by 1 desc;";
    return $this->simple($sql);
  }

  public function miniGallery(){
    return $this->simple("select s.id, s.ogtd, f.file from gallery s inner join file f on s.id = f.scheda where s.piano > 0 and f.tipo = 3 order by random() limit 6;");
  }
}
?>
