<?php
namespace Marta;
session_start();
// use \Marta\Conn;
class Sala extends Conn{
  function __construct(){}

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
    }else {
      $campo = 'vetrina';
      $tab = 'vetrine';
    }
    $andSala = $sala !== null ? " and a.sala = ".$sala : "";
    $query = "select count(distinct a.".$campo.") from liste.".$tab." a inner join liste.sale b on a.sala = b.id where b.piano = ".$piano.$andSala.";";
    $v = $this->simple($query);
    return $v[0];
    // return $query;
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

    $query = "select distinct gallery.* from gallery, liste.".$lista." contenitore where
    contenitore.sala = gallery.sala_id and contenitore.".$elemento." = gallery.contenitore".$cast." and gallery.piano = ".$piano." ".$andSala." ".$andContenitore." order by 6,7,9 asc;";

    $v = $this->simple($query);
    return $v;
  }
}
?>
