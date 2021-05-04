<?php
namespace Marta;
session_start();
use \Marta\Conn;
class Biblio extends Conn{
  public $db;
  function __construct(){}
  
  public function vocabolarioBiblio(array $dati){
    $where = isset($dati['filter']) ? ' where '.$dati['filter']['field']. ' = '.$dati['filter']['value'] : '';
    $sql = "select id, (titolo || ' (' || (CASE WHEN tipo = 1 THEN 'Monografia' WHEN tipo = 2 THEN 'Atti convegno' ELSE 'Articolo in rivista' END) || ') ' || autore) AS value from ". $dati['tab'] . $where. " order by titolo asc;";
    return $this->simple($sql);
  }
  
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
  
  public function addScheda(array $dati){
    $this->begin();
    try {
      $biblioSql = $this->buildInsert('bibliografia',$dati['bibliografia']);
      $biblioSql = rtrim($biblioSql, ";") . " returning id;";
      $biblioId = $this->returning($biblioSql,$dati['bibliografia']);
      if($biblioId['res']==false){ throw new \Exception($biblioId['msg'], 1);  }
	  if (isset($dati['biblio_scheda']['id_scheda'])){
		  $id_scheda = (int)$dati['biblio_scheda']['id_scheda'];
		  if ($id_scheda > 0) {
			  $this->addSchedaBiblio('biblio_scheda', $id_scheda, (int)$biblioId['field']);
		  }
	  }
      $this->commit();
      return array("res"=>true, "msg"=>'La scheda bibliografica è stata correttamente inserita');
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

  public function listaScheda(string $search){
    $sql = "
      SELECT bibliografia.id, bibliografia.titolo, bibliografia.autore, (CASE WHEN bibliografia.tipo = 1 THEN 'Monografia' WHEN bibliografia.tipo = 2 THEN 'Atti convegno' ELSE 'Articolo in rivista' END) AS tipo
      FROM  bibliografia
      WHERE (bibliografia.titolo ILIKE '%".$search."%' OR bibliografia.autore ILIKE '%".$search."%' OR (CASE WHEN bibliografia.tipo = 1 THEN 'Monografia' WHEN bibliografia.tipo = 2 THEN 'Atti convegno' ELSE 'Articolo in rivista' END) ILIKE '%".$search."%')
      ORDER BY bibliografia.titolo ASC;";
    return $this->simple($sql);
  }
}
?>
