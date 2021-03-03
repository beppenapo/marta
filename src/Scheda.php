<?php
namespace Marta;
session_start();
use \Marta\Conn;
class Scheda extends Conn{
  public $db;
  function __construct(){}
    public function getSale(int $piano){
      $sql = "select id, sala from liste.sale where piano = ".$piano." order by sala asc;";
      return $this->simple($sql);
    }
    public function getContenitore(array $dati){
      $f = $dati['contenitore'] == 'vetrine' ? 'vetrina' : 'scaffale';
      $sql = "select distinct ".$f." as c, note from liste.".$dati['contenitore']." where sala = ".$dati['sala']." order by 1 asc;";
      return $this->simple($sql);
    }
  public function liste(int $tipo){}
  public function vocabolari(array $dati){
    $where = isset($dati['filter']) ? ' where '.$dati['filter']['field']. ' = '.$dati['filter']['value'] : '';
    $sql = "select * from ". $dati['tab'] . $where. " order by value asc;";
    return $this->simple($sql);
  }
  public function autocomplete(array $dati){
    $sql = "select * from ". $dati['tab'] . " where ".$dati['field']." ilike '&".$dati['val']."&' order by value asc;";
    return $this->simple($sql);
  }
  public function getScheda(int $id){}
  public function addScheda(array $dati){
    $this->begin();
    try {
      $schedaSql = $this->buildInsert('scheda',$dati['scheda']);
      $schedaSql = rtrim($schedaSql, ";") . " returning id;";
      $schedaId = $this->returning($schedaSql,$dati['scheda']);
      if($schedaId['res']==false){ throw new \Exception($schedaId['msg'], 1);  }
      $this->addSection('cd', $schedaId['field'], $dati['cd']);
      $this->addSection('og', $schedaId['field'], $dati['og']);
      $this->addSection('lc', $schedaId['field'], $dati['lc']);
      if (isset($dati['la'])) {$this->addSection('la', $schedaId['field'], $dati['la']);}
      if (isset($dati['re'])) {$this->addSection('re', $schedaId['field'], $dati['re']);}
      $this->addSection('dtz', $schedaId['field'], $dati['dtz']);
      if (isset($dati['dts'])) {$this->addSection('dts', $schedaId['field'], $dati['dts']);}
      $dtmVal = explode(',',$dati['dtm']['dtm']);
      foreach ($dtmVal as $value) {$this->addSection('dtm',$schedaId['field'],array("dtm"=>$value));}
      foreach ($dati['mtc'] as $val) {
        $datiMtc = array('materia'=>$val['materia'], 'tecnica'=>$val['tecnica']);
        $this->addSection('mtc', $schedaId['field'], $datiMtc);
      }
      $this->addSection('mis', $schedaId['field'], $dati['mis']);
      $this->addSection('da', $schedaId['field'], $dati['da']);
      $this->addSection('co', $schedaId['field'], $dati['co']);
      $this->addSection('tu', $schedaId['field'], $dati['tu']);
      $this->addSection('ad', $schedaId['field'], $dati['ad']);
      $this->addSection('cm', $schedaId['field'], $dati['cm']);
      $this->commit();
      return array("res"=>true, "msg"=>'La scheda è stata correttamente inserita');
      // return array("res"=>true, "msg"=>$out);
    } catch (\Exception $e) {
      // $this->rollback();
      return array("res"=>false, "msg"=>$e->getMessage());
    }
  }
  public function editScheda(array $dati){}
  public function deleteScheda(int $id){}

  protected function addSection(string $tab, int $scheda, array $dati){
    $dati['scheda'] = $scheda;
    $sql = $this->buildInsert($tab,$dati);
    $res = $this->prepared($sql,$dati);
    if (!$res) { throw new \Exception($res, 1);}
    return $res;
  }

}

?>
