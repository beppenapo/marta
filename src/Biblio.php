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
    $this->begin();
    $msg = 'La scheda bibliografica è stata correttamente inserita';
    $sql = $this->buildInsert('bibliografia',$dati);
    $sql = rtrim($sql, ";") . " returning id;";
    $biblioId = $this->returning($sql,$dati);
    $this->commit();
    if ($biblioId['res']===false) {
      return array("res"=>false,"msg"=>$biblioId['msg']);
    }else {
      return array("res"=>true,"msg"=>$msg, "id"=>$biblioId['field']);
    }
  }
  public function addContrib(array $dati){
    $this->begin();
    $msg = 'Il contributo è stato correttamente inserito';
    $sql = $this->buildInsert('contributo',$dati);
    $sql = rtrim($sql, ";") . " returning id;";
    $contribId = $this->returning($sql,$dati);
    $this->commit();
    if ($contribId['res']===false) {
      return array("res"=>false,"msg"=>$contribId['msg']);
    }else {
      return array("res"=>true,"msg"=>$msg, "id"=>$contribId['field']);
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
  public function editContributo(array $dati){
    $filter = ['id'=>$dati['id']];
    unset($dati['id']);
    $sql = $this->buildUpdate('contributo',$filter,$dati);
    $res = $this->prepared($sql, $dati);
    if($res === true){$res = array('res'=>true, 'msg'=> "La scheda contributo è stata correttamente modificata");}
    return $res;
  }

  public function deleteScheda(int $id){
    $dati = ['id'=>$id];
    $sql = "delete from bibliografia where id = :id;";
    $res = $this->prepared($sql, $dati);
    if($res === true){$res = array('res'=>true, 'msg'=> "La scheda bibliografica è stata definitivamente eliminata");}
    return $res;
  }
  public function deleteContrib(int $id){
    $dati = ['id'=>$id];
    $sql = "delete from contributo where id = :id;";
    $res = $this->prepared($sql, $dati);
    if($res === true){$res = array('res'=>true, 'msg'=> "La scheda bibliografica è stata definitivamente eliminata");}
    return $res;
  }

  public function elencoBiblio(){
    $sql = "select b.id,l.id as tipo_id,l.value as tipo,b.titolo,b.autore,count(s.*) as schede,'biblioView.php?get=' as link from bibliografia b inner join liste.biblio_tipo l on b.tipo = l.id left join biblio_scheda bs on bs.biblio = b.id left join scheda s on bs.scheda = s.id group by b.id, l.id, l.value, b.autore, b.titolo UNION select c.id, 0,'contributo in raccolta' as tipo,c.titolo,c.autore,count(s.*) as schede,'contribView.php?get=' as link from contributo c left join biblio_scheda bs on bs.contributo = c.id left join scheda s on bs.scheda = s.id group by c.id, c.autore, c.titolo order by titolo, autore ASC;";
    return $this->simple($sql);
  }

  public function getScheda(int $id){
    $out = [];
    $sql = "SELECT b.id, b.titolo, b.tipo as tipoid, l.value as tipo, b.autore, b.altri_autori, b.editore, b.anno, b.luogo, b.isbn, b.url, b.curatore
    FROM bibliografia b
    inner join liste.biblio_tipo as l on b.tipo = l.id
    WHERE b.id = ".$id.";";
    $res = $this->simple($sql);
    $out['scheda'] = $res[0];

    $out['schede'] = $this->getSchedeList($id);
    $out['contributi'] = $this->getContribList($id);

    return $out;
  }

  public function biblioScheda(array $dati){
    $sql = $this->buildInsert('biblio_scheda',$dati);
    $res = $this->prepared($sql, $dati);
    if($res === true){$res = array('res'=>true, 'msg'=> "Il record bibliografico è stato correttamente associato alla scheda reperto");}
    return $res;
  }

  public function getContribList(int $id = null){
    $where = $id !== null ? "where raccolta = ".$id : "";
    $sql = "select * from contributo ".$where." order by titolo asc;";
    return $this->simple($sql);
  }
  public function getContrib(int $id){
    $sql = "select c.id as contrib_id, c.titolo as contrib_tit, c.autore as contrib_aut, c.altri_autori as contrib_alt, c.pag as contrib_pagine, b.id, b.titolo, b.autore, b.anno
    from contributo c left join bibliografia b on c.raccolta = b.id
    where c.id = ".$id.";";
    $res = $this->simple($sql);
    return $res[0];
  }

  public function getSchedeList(int $id = null){
    $where = $id !== null ? "where bs.biblio = ".$id : "";
    $sql =" select bs.scheda, nctn.nctn, scheda.tsk, scheda.titolo
    from biblio_scheda bs
    inner JOIN nctn_scheda nctn on nctn.scheda = bs.scheda
    inner join scheda on bs.scheda = scheda.id
    ".$where." order by nctn asc;";
    return $this->simple($sql);
  }
  public function getSchedeContrib(int $id){
    $sql =" select bs.scheda, nctn.nctn, scheda.tsk, scheda.titolo
    from biblio_scheda bs
    inner JOIN nctn_scheda nctn on nctn.scheda = bs.scheda
    inner join scheda on bs.scheda = scheda.id
    where bs.contributo = ".$id." order by nctn asc;";
    return $this->simple($sql);
  }

  public function listaAuth(){
    return $this->simple("select id, titolo, autore, anno from bibliografia order by titolo asc;");
  }

}
?>
