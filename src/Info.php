<?php
namespace Marta;
use \Marta\Conn;

class Info{
  public $db;
  public function __construct(){ $this->db = new Conn; }
  
  public function introMuseo(){
    $totReperti = $this->totReperti()[0];
    $totDeposito = $this->totReperti(piano: -1)[0];
    $totEsposto = $totReperti['count'] - $totDeposito['count'];
    $sale = $this->totSale();
    $repertiPiano = $this->totReperti(groupBy: 'piano');
    $vetrine = $this->totContenitori(tipoContenitore: 'vetrine', groupBy: 'piano');
    $scaffali = $this->totContenitori(tipoContenitore: 'scaffali', groupBy: 'piano');
    $tipoScaffale = $this->totContenitori(tipoContenitore: 'scaffali', groupBy: 'v.note');
    $repertiCassefortiMonetieri = $this->totReperti(piano: -1, sala: 4, groupBy: 'contenitore', altriFiltri: ["(contenitore = '40' or contenitore = '41')"]);
    $fuoriVetrina = $this->totReperti(groupBy: 'piano', altriFiltri: ["contenitore = 'fuori vetrina'","piano > 0"]);
    return [
      "totReperti" => $totReperti['count'], 
      "totDeposito"=>$totDeposito['count'],
      "totEsposto"=>$totEsposto,
      "totSale"=>$sale,
      "repertiPiano"=>$repertiPiano,
      "vetrine"=>$vetrine,
      "scaffali"=>$scaffali,
      "tipoScaffale"=>$tipoScaffale,
      "fuoriVetrina"=>$fuoriVetrina,
      "repertiCassefortiMonetieri"=>$repertiCassefortiMonetieri
    ];
  }
  public function totSale(?int $piano = null){
    try {
      $filtro = $piano !== null ? " where piano = ".$piano : '';
      return $this->db->simple("select piano, count(*) from liste.sale ".$filtro." group by piano;");
    } catch (\Throwable $e) {
      return [$e->getMessage(),$e->getCode()];
    }
  }

  public function totContenitori(string $tipoContenitore, ?int $piano = null, ?int $sala = null, ?string $groupBy=null){
    try {
      $filtri = [];
      $campo = [];
      $addField = '';
      $group = '';
      $sql='';
      if($piano !== null){array_push($filtri, "s.piano = ".$piano);}
      if($sala !== null){array_push($filtri, "s.id = ".$sala);}
      if($groupBy !== null){
        array_push($campo, $groupBy);
        $group = 'group by '.$groupBy; 
        if($groupBy == 'v.note'){ array_push($filtri,'v.note is not null');}
      }
      if(!empty($campo)){ $addField = implode(",",$campo).',';}
      $filtro = !empty($filtri) ? " where ".implode(" and ",$filtri) : '';
      $sql = "select ".$addField." count(*) from liste.sale s inner join liste.".$tipoContenitore." v on v.sala = s.id ".$filtro." ".$group.";";
      return $this->db->simple($sql);
    } catch (\Throwable $e) {
      return [$e->getMessage(),$e->getCode()];
    }
  }

  public function totReperti(?int $piano = null, ?int $sala = null, ?string $contenitore = null, ?string $groupBy=null, ?array $altriFiltri = null){
    try {
      $filtri = [];
      $campo = [];
      $addField = '';
      $group = '';
      if($piano !== null){array_push($filtri, "piano = ".$piano);}
      if($sala !== null){array_push($filtri, "sala = ".$sala);}
      if($altriFiltri !== null && is_array($altriFiltri) && !empty($altriFiltri)){
        $filtri = array_merge($filtri,$altriFiltri);
      }
      
      if($groupBy !== null){
        array_push($campo, $groupBy);
        $group = 'group by '.$groupBy; 
      }
      if(!empty($campo)){ $addField = implode(",",$campo).',';}
      $filtro = !empty($filtri) ? " where ".implode(" and ",$filtri) : '';
      $sql = "select ".$addField." count(*) from lc ".$filtro." ".$group.";";
      return $this->db->simple($sql);
    } catch (\Throwable $e) {
      return [$e->getMessage(),$e->getCode()];
    }
  }
}

?>
