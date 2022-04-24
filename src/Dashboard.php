<?php
namespace Marta;
session_start();
use \Marta\Conn;

class Dashboard extends Conn{
  function __construct(){}

  public function comunicazioni(){
    $sql = "select c.id, c.testo, c.data, u.id as session, concat(u.nome,' ',u.cognome) as utente from progetto.comunicazioni c, utenti u where c.utente = u.id order by c.data desc, utente asc;";
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
    // $where = '';
    // if($dati && !empty($dati)){ $where = ' where s.cmpn = '.$dati['cmpn']; }
    // $sql = "select nctn.nctn, s.titolo, stato.*, s.cmpd
    // from scheda s
    // INNER JOIN nctn_scheda on nctn_scheda.scheda = s.id
    // INNER JOIN nctn on nctn_scheda.nctn = nctn.nctn
    // INNER JOIN stato_scheda stato on stato.scheda = s.id
    //  ".$where."
    // ORDER BY nctn asc;";
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
    $sql = "select u.id, concat(u.cognome,' ',u.nome) utente, count(s.*) schede from utenti u left join scheda s on s.cmpn = u.id group by u.id having (u.classe = 3 and u.id <> 36) or (u.classe <> 3 and count(s.*) > 0) order by 2 asc;";
    return $this->simple($sql);
  }

}

?>
