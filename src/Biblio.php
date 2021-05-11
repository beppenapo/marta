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

  public function getScheda(int $id){
    $out = [];
    $sql = "SELECT b.id, b.titolo, b.tipo as tipoid, l.value as tipo, b.autore, b.altri_autori, b.titolo_raccolta, b.editore, b.anno, b.luogo, b.isbn, b.url, b.curatore
    FROM bibliografia b
    inner join liste.biblio_tipo as l on b.tipo = l.id
    WHERE b.id = ".$id.";";
    $res = $this->simple($sql);
    $out['scheda'] = $res[0];

    $sql =" select og.scheda, scheda.tipo, ogtd.value as ogtd
    from og
    inner join liste.ogtd as ogtd on og.ogtd = ogtd.id
    inner join scheda on og.scheda = scheda.id
    inner join biblio_scheda bs on scheda.id = bs.scheda
    where bs.biblio = ".$id." order by ogtd asc;";
    $res = $this->simple($sql);
    $out['schede'] = $res;
    return $out;
  }
#### Ale here #####

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
