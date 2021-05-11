<?php
namespace Marta;
session_start();
use \Marta\Conn;
class Biblio extends Conn{
  public $db;
  function __construct(){}

  public function listaTipo(){
    $tipo = $this->simple("select * from liste.biblio_tipo order by 2 asc");
    return $tipo;
  }
  public function listaLivello(){
    $liv = $this->simple("select * from liste.biblio_livello order by 2 asc");
    return $liv;
  }

  public function addBiblio(array $dati){
    $sql = $this->buildInsert('bibliografia',$dati);
    $res = $this->prepared($sql, $dati);
    if($res === true){$res = array('res'=>true, 'msg'=> "La scheda bibliografica è stata correttamente inserita");}
    return $res;
  }

  public function elencoBiblio(){
    $sql = "select b.id, l.value as tipo, b.autore, b.titolo, count(s.*) as schede
    from bibliografia b
    inner join liste.biblio_tipo as l on b.tipo = l.id
    left join biblio_scheda bs on bs.biblio = b.id
    left join scheda s on bs.scheda = s.id
    group by b.id, l.value, b.autore, b.titolo
    order by b.titolo asc;";
    return $this->simple($sql);
  }

#### Ale here #####
  public function getScheda(int $id){
  $sql = "SELECT bibliografia.id, bibliografia.titolo, bibliografia.tipo, bibliografia.autore, bibliografia.altri_autori, bibliografia.titolo_raccolta, bibliografia.editore, bibliografia.anno, bibliografia.luogo, bibliografia.isbn, bibliografia.url, bibliografia.pagine
  FROM public.bibliografia
  WHERE bibliografia.id = ".$id.";";
    return $this->simple($sql);
  }

public function insbiblioinscheda(int $id_scheda, int $id_biblio){
	$this->begin();
    try {
	  $this->addSchedaBiblio('biblio_scheda', $id_scheda, $id_biblio);
	  $this->commit();
	  return array("res"=>true, "msg"=>'Associazione con la scheda bibliografica effettuata correttamente.');
	  // return array("res"=>true, "msg"=>$out);
	} catch (\Exception $e) {
	  // $this->rollback();
	  return array("res"=>false, "msg"=>$e->getMessage());
	}
}



  public function editScheda(array $dati){
    $this->begin();
    try {
    $id_scheda = (int)$dati['bibliografia']['id'];
      if($id_scheda<1){ throw new \Exception($schedaId['msg'], 1);  }
    $filter_scheda = ['id'=>$id_scheda];
      $schedaSql = $this->buildUpdate('bibliografia',$filter_scheda,$dati['bibliografia']);
    $this->prepared($schedaSql,$dati['bibliografia']);
      $this->commit();
      return array("res"=>true, "msg"=>'La scheda bibliografica è stata correttamente modificata');
      // return array("res"=>true, "msg"=>$out);
    } catch (\Exception $e) {
      // $this->rollback();
      return array("res"=>false, "msg"=>$e->getMessage());
    }
  }
  public function deleteScheda(int $id){
    $this->begin();
    try {
    $filter_scheda = ['biblio'=>$id];
    $sqldel = $this->buildDelete('public.biblio_scheda',$filter_scheda);
    $filter_scheda = ['id'=>$id];
    $sqldel = $this->buildDelete('public.bibliografia',$filter_scheda);
    $this->prepared($sqldel);
    $this->commit();
    return array("res"=>true, "msg"=>'La scheda bibliografica è stata correttamente eliminata');
    // return array("res"=>true, "msg"=>$out);
    } catch (\Exception $e) {
    // $this->rollback();
    return array("res"=>false, "msg"=>$e->getMessage());
    }
  }

  protected function addSchedaBiblio(string $tab, int $scheda, int $biblio){
    $dati['scheda'] = $scheda;
    $dati['biblio'] = $biblio;
    $sql = $this->buildInsert($tab,$dati);
    $res = $this->prepared($sql,$dati);
    if (!$res) { throw new \Exception($res, 1);}
    return $res;
  }
}
?>
