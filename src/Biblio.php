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
    try {
      if (isset($dati['bs'])) {
        $bs = $dati['bs'];
        unset($dati['bs']);
        $msg = 'Il record bibliografico è stato creato ed associato alla scheda reperto';
      }else {
        $msg = 'La scheda bibliografica è stata correttamente inserita';
      }
      $this->begin();
      $sql = $this->buildInsert('bibliografia',$dati);
      $sql = rtrim($sql, ";") . " returning id;";
      $biblioId = $this->returning($sql,$dati);
      if(isset($bs['scheda'])){
        $bs['biblio'] = $biblioId['field'];
        $sql = $this->buildInsert('biblio_scheda',$bs);
        $this->prepared($sql,$bs);
      }
      $this->commit();
      return array("res"=>true,"msg"=>$msg, "id"=>$biblioId['field']);
    } catch (\PDOException $e) {
      return array("res"=>false, "msg"=>'La query riporta il seguente errore:<br/>'.$e->getMessage());
    }catch (\Exception $e) {
      return array("res"=>false,"msg"=>'La query riporta il seguente errore:<br/>'.$e->getMessage());
    }
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

  public function biblioScheda(array $dati){
    $sql = $this->buildInsert('biblio_scheda',$dati);
    $res = $this->prepared($sql, $dati);
    if($res === true){$res = array('res'=>true, 'msg'=> "Il record bibliografico è stato correttamente associato alla scheda reperto");}
    return $res;
  }

}
?>
