<?php
namespace Marta;
session_start();
// use \Marta\Conn;
class Sala extends Conn{
  function __construct(){}

  public function getRepertiPiano(array $dati){
    $info['numSale'] = $this->numSale($dati['piano']);
    $info['numContenitori'] = $this->numContenitori($dati['piano']);
    $info['numReperti'] = $this->numReperti($dati['piano']);
    $info['schede'] = $this->getSchede($dati['piano']);
    return $info;
  }
  // public function getRepertiSala(array $dati){
  //   $piano = $dati['piano'];
  //   $sala = $dati['sala'];
  //   $info=[];
  //   $info['numContenitori'] = $this->numContenitori($piano, $sala);
  //   $info['nomeSala'] = $this->nomeSala($sala);
  //   $info['numReperti'] = $this->numReperti($piano, $sala);
  //   return $info;
  // }

  private function numSale(int $piano){
    $v = $this->simple("select count(*) from liste.sale where piano = ".$piano.";");
    return $v[0];
  }

  private function numContenitori(int $piano, int $sala = null){
    if ($piano == -1) {
      $campo = 'scaffale';
      $tab = 'scaffali';
    }else {
      $campo = 'vetrina';
      $tab = 'vetrine';
    }
    $andSala = $sala !== null ? " and sala = ".$sala : "";
    $query = "select count(distinct a.".$campo.") from liste.".$tab." a inner join liste.sale b on a.sala = b.id where b.piano = ".$piano.$andSala.";";
    $v = $this->simple($query);
    return $v[0];
  }

  private function numReperti(int $piano, int $sala = null){
    $andSala = $sala !== null ? " and sala = ".$sala : "";
    $v = $this->simple("select count(*) from lc where piano = ".$piano.$andSala.";");
    return $v[0];
  }
  private function nomeSala(int $id){
    $v = $this->simple("select coalesce(descrizione,sala::text,'') sala from liste.sale where id = ".$id.";");
    return $v[0];
  }
  private function getSchede(int $piano, int $sala = null, int $contenitore = null){
    $v = $this->simple("select * from gallery where piano = ".$piano.";");
    return $v;
  }
}
?>
