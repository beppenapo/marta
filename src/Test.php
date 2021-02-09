<?php
namespace Marta;
use \Marta\Conn;

class Test{
  public $db;
  public function __construct(){
    $this->db = new Conn;
  }
  public function test(){
    try {
      $out = $this->db->simple('select cognome from utenti;');
      return $out;
    } catch (\PDOException $e) {
      return $e->getMessage();
    }
  }
}

?>
