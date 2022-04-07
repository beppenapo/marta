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
}
?>
