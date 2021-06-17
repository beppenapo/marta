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
  public $ra;
  public $nu;
  public $foto;
  public $stereo;
  public $modelli;
  public $raPerc;
  public $nuPerc;
  public $fotoPerc;
  public $stereoPerc;
  public $modelliPerc;
  function __construct(){
    $ra = $this->simple("select count(*) from scheda where tsk = 1;");
    $nu = $this->simple("select count(*) from scheda where tsk = 2;");
    $foto = $this->simple("select count(*) from file where tipo = 3;");
    $stereo = $this->simple("select count(*) from file where tipo = 2;");
    $modelli = $this->simple("select count(*) from file where tipo = 1;");
    $this->ra = $ra[0]['count'];
    $this->nu = $nu[0]['count(int)'];
    $this->foto = $foto[0]['count'];
    $this->stereo = $stereo[0]['count'];
    $this->modelli = $modelli[0]['count'];
    $this->raPerc = $ra[0]['count']*100/$this->totra;
    $this->nuPerc = $nu[0]['count']*100/$this->totnu;
    $this->fotoPerc = $foto[0]['count']*100/$this->totfoto;
    $this->stereoPerc = $stereo[0]['count']*100/$this->totstereo;
    $this->modelliPerc = $modelli[0]['count']*100/$this->tot3d;
  }

  public function statHome(){
    $ra = $this->simple("select count(*) from scheda where tsk = 1;");
    $nu = $this->simple("select count(*) from scheda where tsk = 2;");
    $foto = $this->simple("select count(*) from file where tipo = 3;");
    $stereo = $this->simple("select count(*) from file where tipo = 2;");
    $modelli = $this->simple("select count(*) from file where tipo = 1;");

    $raPerc = $ra[0]['count']*100/$this->totra;
    $nuPerc = $nu[0]['count']*100/$this->totnu;


    $arr = array( "ra"=>$ra[0]['count'], "nu"=>$nu[0]['count'], "foto"=>$foto[0]['count'], "stereo"=>$stereo[0]['count'], "modelli"=>$modelli[0]['count']);
    return $arr;
  }
}
?>
