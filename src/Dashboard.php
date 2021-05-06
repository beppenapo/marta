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

}

?>
