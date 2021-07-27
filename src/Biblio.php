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

  public function editScheda(array $dati){
    $filter = ['id'=>$dati['id']];
    unset($dati['id']);
    $sql = $this->buildUpdate('bibliografia',$filter,$dati);
    $res = $this->prepared($sql, $dati);
    if($res === true){$res = array('res'=>true, 'msg'=> "La scheda bibliografica è stata correttamente modificata");}
    return $res;
  }

  public function deleteScheda(int $id){
    $dati = ['id'=>$id];
    $sql = "delete from bibliografia where id = :id;";
    $res = $this->prepared($sql, $dati);
    if($res === true){$res = array('res'=>true, 'msg'=> "La scheda bibliografica è stata definitivamente eliminata");}
    return $res;
  }

  public function elencoBiblio(){
    $sql = "select b.id, l.value as tipo, b.autore, b.titolo, b.anno, count(s.*) as schede
    from bibliografia b
    inner join liste.biblio_tipo as l on b.tipo = l.id
    left join biblio_scheda bs on bs.biblio = b.id
    left join scheda s on bs.scheda = s.id
    group by b.id, l.value, b.autore, b.titolo, b.anno
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

    $sql =" select bs.scheda, nctn.nctn, scheda.tsk, scheda.titolo
    from biblio_scheda bs
    inner JOIN nctn_scheda nctn on nctn.scheda = bs.scheda
    inner join scheda on bs.scheda = scheda.id
    where bs.biblio = ".$id." order by nctn asc;";
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
