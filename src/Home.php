<?php
namespace Marta;
session_start();
use \Marta\Conn;

class Home extends Conn{
  function __construct(){}

  public function statHome(){
    $sql = "select count(*) from scheda where tipo = 1;";
    $ra = $this->simple($sql);
    $sql = "select count(*) from scheda where tipo = 2;";
    $nu = $this->simple($sql);
    $sql = "select count(*) from file where tipo = 3;";
    $foto = $this->simple($sql);
    $sql = "select count(*) from file where tipo = 2;";
    $stereo = $this->simple($sql);
    $sql = "select count(*) from file where tipo = 1;";
    $modelli = $this->simple($sql);
    $arr = array( "ra"=>$ra[0]['count'], "nu"=>$nu[0]['count'], "foto"=>$foto[0]['count'], "stereo"=>$stereo[0]['count'], "modelli"=>$modelli[0]['count']);
    return $arr;
  }
}
?>
