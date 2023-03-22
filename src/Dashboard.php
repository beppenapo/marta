<?php
namespace Marta;
session_start();

class Dashboard extends Conn{
  function __construct(){}

  public function comunicazioni(){
    $sql = "select c.id, c.testo, c.data, u.id as session from progetto.comunicazioni c, utenti u where c.utente = u.id and u.id = ".$_SESSION['id']." order by c.data desc, utente asc;";
    return $this->simple($sql);
  }
  public function addComunicazione(string $testo){
    $dati = array("testo"=>$testo, "utente"=>$_SESSION['id']);
    $sql = "insert into progetto.comunicazioni(testo,utente) values (:testo, :utente);";
    return $this->prepared($sql, $dati);
  }

  public function editComunicazione(int $id, string $testo){
    $dati = array("id"=>$id,"testo"=>$testo);
    $sql = "update progetto.comunicazioni set testo=:testo where id = :id;";
    return $this->prepared($sql, $dati);
  }

  public function delComunicazione(int $id){
    $dati = array("id"=>$id);
    $sql = "delete from progetto.comunicazioni where id = :id;";
    return $this->prepared($sql, $dati);
  }
  public function statoSchede(){
    $sql = "with
      a as (select count(*) aperta from stato_scheda where chiusa = false),
      b as (select count(*) chiusa from stato_scheda where chiusa = true),
      c as (select count(*) verificata from stato_scheda where verificata = true),
      d as (select count(*) inviata from stato_scheda where inviata = true),
      e as (select count(*) accettata from stato_scheda where accettata = true)
      select a.aperta, b.chiusa, c.verificata, d.inviata, e.accettata from a, b, c, d, e";
    return $this->simple($sql);
  }

  function schedatori(){
    $sql = "select u.id, concat(u.cognome,' ',u.nome) utente, count(s.*) schede from utenti u left join scheda s on s.cmpn = u.id group by u.id having (u.classe = 3) or (u.classe <> 3 and count(s.*) > 0) order by 2 asc;";
    return $this->simple($sql);
  }

  public function schede(array $dati){
    //1 = senza biblio | 2 = senza immagini | 3 = aperta | 4 = chiusa | 5 = verificata | 6 = inviata | 7 = accettata | 8 = nctn | 9 = inventario | 10 = operatore
    $sql = "select scheda, nctn, inventario, titolo, ogtdid, ogtd, piano, sala, cassetta from schede_dashboard";
    $filter=[];
    if($_SESSION['classe'] == 3){array_push($filter,'cmpn = '.$_SESSION['id']);}
    $tipo = $dati['tipo'];
    switch (true) {
      case $tipo == 1:
        array_push($filter,'scheda not in (select scheda from biblio_scheda)');
      break;
      case $tipo == 2:
        array_push($filter,'scheda not in (select scheda from file where tipo = 3)');
      break;
      case $tipo == 3:
        array_push($filter,'scheda not in (select scheda from geolocalizzazione) and scheda not in (select scheda from gp)');
      break;
      case $tipo == 4:
        array_push($filter,'scheda in (select scheda from stato_scheda where chiusa = false)');
      break;
      case $tipo == 5: array_push($filter,'scheda in (select scheda from stato_scheda where chiusa = true and verificata = false)'); break;
      case $tipo == 6: array_push($filter,'scheda in (select scheda from stato_scheda where verificata = true and inviata = false)'); break;
      case $tipo == 7: array_push($filter,'scheda in (select scheda from stato_scheda where inviata = true and accettata = false)'); break;
      case $tipo == 8: array_push($filter,'scheda in (select scheda from stato_scheda where accettata = true)'); break;
      case $tipo == 9: array_push($filter,"nctn::text ilike '".$dati['nctn']."%'"); break;
      case $tipo == 10: array_push($filter,"inventario::text ilike '".$dati['inv']."%'"); break;
      case $tipo == 11: array_push($filter,"cmpn = ".$dati['operatore']); break;
    }
    if($tipo > 0){ $sql = $sql . " where " . join(' and ', $filter).";"; }
    return $this->simple($sql);
  }

  public function checkSchede(){
    $out=[];
    $schedatore = $_SESSION['classe'] == 3 ? ' and cmpn = '.$_SESSION['id'] : '';
    $noBiblio = $this->simple("select count(*) from scheda where id not in(select scheda from biblio_fake)".$schedatore.";");
    $noImg = $this->simple("select count(*) from scheda where id not in(select scheda from file where tipo = 3)".$schedatore.";");
    $noGeo = $this->simple("select count(*) from scheda where id not in (select scheda from geolocalizzazione) and id not in (select scheda from gp)".$schedatore.";");
    $out['biblio'] = $noBiblio[0];
    $out['img'] = $noImg[0];
    $out['geo'] = $noGeo[0];
    return $out;
  }

  public function schede_operatore(){
    $f = $_SESSION['classe'] == 3 ? "where cmpn = ".$_SESSION['id'] : '';
    $sql = "select count(*) schede from scheda ".$f;
    $query = $this->simple($sql);
    return $query[0];
  }

  function biblio(array $dati = NULL){
    $where = '';
    $filtri = [];
    if(isset($dati)){
      foreach ($dati as $campo => $valore) {
        if ($campo !== 'tipo') {
          array_push($filtri, $campo . " ilike '%".$valore."%' ");
        }else {
          array_push($filtri, $campo .' = '.$valore);
        }
      }
      $where = " where ".join(" and ", $filtri);
    };
    $sql = "with  a as(select b.id,l.id as tipo,b.titolo,b.autore,count(s.*) as schede from bibliografia b inner join liste.biblio_tipo l on b.tipo = l.id left join biblio_scheda bs on bs.biblio = b.id left join scheda s on bs.scheda = s.id group by b.id, l.id, b.autore, b.titolo UNION select c.id,0 as tipo,c.titolo, c.autore,count(s.*) as schede from contributo c left join biblio_scheda bs on bs.contributo = c.id left join scheda s on bs.scheda = s.id group by c.id, c.autore, c.titolo order by titolo, autore ASC) select * from a ".$where.";";
    return $this->simple($sql);
  }


}

?>
