<?php
namespace Marta;
use \Marta\Conn;

class Info{
  public $db;
  public function __construct(){ $this->db = new Conn; }
  
  public function introMuseo(){
    return [
      "totReperti" => $this->totReperti()[0]['count'], 
      "totEsposto"=>$this->totEsposto()[0]['count'],
      "totDeposito"=>$this->totDeposito()[0]['count'],
      "totSale"=>$this->totSale(),
      "repertiPiano"=>$this->repertiPiano(),
      "vetrine"=>$this->totVetrine(),
      "fuoriVetrina"=>$this->fuoriVetrina(),
      "scaffali"=>$this->totScaffali()[0]['count'],
      "cassette"=>$this->totCassette()[0]['count'],
      "tipoScaffale"=>$this->tipoScaffale(),
      "repertiCassefortiMonetieri"=>$this->repertiCassefortiMonetieri(),
      "fuoriCassetta"=>$this->fuoriCassetta()[0]['count']
    ];
  }

  public function totReperti(){
    return $this->db->simple("select count(*) from scheda");
  }
  public function totEsposto(){
    return $this->db->simple("select count(*) from esposto");
  }
  public function totDeposito(){
    return $this->db->simple("select count(*) from deposito");
  }
  public function repertiPiano(){
    return $this->db->simple("select piano, count(*) from localizzazione group by piano;");
  }
  public function totSale(){
    return $this->db->simple("select piano, count(*) from liste.sale group by piano;");
  }
  public function totVetrine(){
    return $this->db->simple("select s.piano, count(*) from liste.vetrine v inner join liste.sale s on v.sala = s.id group by s.piano;");
  }
  public function fuoriVetrina(){
    return $this->db->simple("select piano, count(*) from localizzazione_esposto where vetrina_id is null group by piano;");
  }
  public function totScaffali(){
    return $this->db->simple("select count(*) from liste.scaffali;");
  }
  public function totCassette(){
    return $this->db->simple("select count(*) from liste.cassette;");
  }
  public function tipoScaffale(){
    return $this->db->simple("select note, count(*) from liste.scaffali where trim(note) is not null group by note;");
  }
  public function repertiCassefortiMonetieri(){
    return $this->db->simple("select scaffale_text contenitore, count(*) from localizzazione_deposito where scaffale_text is not null group by scaffale_text;");
  }
  public function fuoriCassetta(){
    return $this->db->simple("select count(*) from localizzazione_deposito where cassetta_id is null;");
  }
}

?>
