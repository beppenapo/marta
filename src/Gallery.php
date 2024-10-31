<?php
namespace Marta;
session_start();
use Marta\Conn;
use Marta\Scheda;


class Gallery{
  public $db;
  public $item;
  public $scheda;
  function __construct(string $item){
    $this->db = new Conn();
    $this->scheda = new Scheda;
    switch ($item) {
      case 'reperti': $this->item = 1; break;
      case 'monete': $this->item = 2; break;
      case 'immagini': $this->item = 3; break;
      case 'stereo': $this->item = 4; break;
      case 'modelli': $this->item = 5; break;
    }
  }

  public function getItems(array $dati){
    switch (true) {
      case $this->item <= 2:
        return $this->scheda->search(["page" => $dati['page'], "limit" => $dati['limit'],"tsk" => $this->item, "principale" => true]);
        break;
      case $this->item == 3:
        $items = [];
        $reperti = $this->scheda->search(["page" => $dati['page'], "limit" => 12,"tsk" => 1]);
        $monete = $this->scheda->search(["page" => $dati['page'], "limit" => 12,"tsk" => 2]);
        $totalItems = (int) $reperti["totalItems"]["count"] + (int) $monete["totalItems"]["count"];
        $items = array_merge($items, $reperti["items"], $monete["items"]);
        shuffle($items);
        return ["totalItems" => ["count" => $totalItems], "items" => $items];  
        break;
      case $this->item == 4:
        $items = [];
        $reperti = $this->scheda->search(["page" => $dati['page'], "limit" => 12,"tsk" => 1, "tipo" => 7]);
        $monete = $this->scheda->search(["page" => $dati['page'], "limit" => 12,"tsk" => 2, "tipo" => 7]);
        $totalItems = (int) $reperti["totalItems"]["count"] + (int) $monete["totalItems"]["count"];
        $items = array_merge($items, $reperti["items"], $monete["items"]);
        shuffle($items);
        return ["totalItems" => ["count" => $totalItems], "items" => $items]; 
        break;
      case $this->item == 5:
        $schede = $this->db->simple("select scheda from file where tipo = 1;");
        $schede = array_column($schede, 'scheda');
        $items = [];
        $reperti = $this->scheda->search(["page" => $dati['page'], "limit" => $dati['limit'], "tsk" => 1, "ids" => $schede, "principale" => true]);
        $monete = $this->scheda->search(["page" => $dati['page'], "limit" => $dati['limit'], "tsk" => 2, "ids" => $schede, "principale" => true]);
        $totalItems = (int) $reperti["totalItems"]["count"] + (int) $monete["totalItems"]["count"];
        $items = array_merge($items, $reperti["items"], $monete["items"]);
        shuffle($items);
        return ["totalItems" => ["count" => $totalItems], "items" => $items]; 
        break;
    }
  } 
}
?>
