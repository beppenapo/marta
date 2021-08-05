<<<<<<< HEAD
<?php
namespace Marta;
use PDO;
class Conn {
  public $conn;
  public function connect() {
    $params = parse_ini_file('db.ini');
    if ($params === false) {
      throw new \Exception("Error reading database configuration file");
    }
    $conStr = sprintf(
      "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
      $params['host'],
      $params['port'],
      $params['database'],
      $params['user'],
      $params['password']
    );

    $this->conn = new \PDO($conStr);
    $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    // return $pdo;
  }

  public function pdo(){
    if (!$this->conn){ $this->connect();}
    return $this->conn;
  }

  public function simple($sql){
    try {
      $pdo = $this->pdo();
      $exec = $pdo->prepare($sql);
      $exec->execute();
      return $exec->fetchAll(PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      return array("res"=>false, "msg"=>'La query riporta il seguente errore:<br/>'.$e->getMessage());
    } catch (\Exception $e) {
      return array("res"=>false, "msg"=>'La query riporta il seguente errore:<br/>'.$e->getMessage());
    }
  }

  public function prepared(string $sql, array $dati){
    // $pdo = $this->pdo();
    // $exec = $pdo->prepare($sql);
    // $res = $exec->execute($dati);
    // if (!$res) { throw new \Exception(false, $res);}
    try {
      $pdo = $this->pdo();
      $exec = $pdo->prepare($sql);
      $exec->execute($dati);
      return true;
    } catch (\PDOException $e) {
      return array("res"=>false, "msg"=>'La query riporta il seguente errore:<br/>'.$e->getMessage());
    } catch (\Exception $e) {
      return array("res"=>false, "msg"=>'La query riporta il seguente errore:<br/>'.$e->getMessage());
    }
    // return [$sql, $dati];
  }

  public function returning(string $sql, array $dati){
    try {
      $pdo = $this->pdo();
      $exec = $pdo->prepare($sql);
      $exec->execute($dati);
      $returning = $exec->fetchAll();
      return array('res' => true, 'field'=>$returning[0][0] );
    } catch (\PDOException $e) {
      return array("res"=>false, "msg"=>'La query riporta il seguente errore:<br/>'.$e->getMessage());
    } catch (\Exception $e) {
      return array("res"=>false, "msg"=>'La query riporta il seguente errore:<br/>'.$e->getMessage());
    }
  }
  public function buildInsert(string $tab, array $dati){
    $field = [];
    $value = [];
    foreach ($dati as $key => $val) {
      $v = $key == 'pwd' ? "crypt(:pwd, gen_salt('md5'))" : ":".$key;
      array_push($field,$key);
      array_push($value,$v);
    }
    $sql = "insert into ".$tab."(".join(",",$field).") values (".join(",",$value).");";
    return $sql;
  }

  public function buildUpdate(string $tab, array $filter, array $dati){
    $field = [];
    $where = [];
    foreach ($dati as $key => $val) {
      $v = $key == 'pwd' ? "crypt(:pwd, gen_salt('md5'))" : ":".$key;
      array_push($field,$key."=".$v);
    }
    foreach ($filter as $key => $val) { array_push($where,$key." = ".$val); }
    $sql = "update ".$tab." set ".join(",",$field)." where ".join(" AND ", $where).";";
    return $sql;
  }

  public function buildDelete(string $tab, array $filter){
    $where = [];
    foreach ($filter as $key => $val) { array_push($where,$tab.".".$key." = ".$val); }
    $sql = "delete from ".$tab." where ".join(" AND ", $where).";";
    return $sql;
  }

  public function begin(){
    $pdo = $this->pdo();
    $pdo->beginTransaction();
  }
  public function commit(){
    $pdo = $this->pdo();
    $pdo->commit();
  }
  public function rollback(){
    $pdo = $this->pdo();
    $pdo->rollBack();
  }

  public function __construct() {}
  private function __clone() {}
  private function __wakeup() {}

}
?>
=======
<?php
namespace Marta;
use PDO;
class Conn {
  public $conn;
  public function connect() {
    $params = parse_ini_file('db.ini');
    if ($params === false) {
      throw new \Exception("Error reading database configuration file");
    }
    $conStr = sprintf(
      "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
      $params['host'],
      $params['port'],
      $params['database'],
      $params['user'],
      $params['password']
    );

    $this->conn = new \PDO($conStr);
    $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    // return $pdo;
  }

  public function pdo(){
    if (!$this->conn){ $this->connect();}
    return $this->conn;
  }

  public function simple($sql){
    try {
      $pdo = $this->pdo();
      $exec = $pdo->prepare($sql);
      $exec->execute();
      return $exec->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function prepared($sql, $dati=array()){
    try {
      $pdo = $this->pdo();
      $exec = $pdo->prepare($sql);
      $exec->execute($dati);
      return true;
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function returning($sql, $dati=array()){
    try {
      $pdo = $this->pdo();
      $exec = $pdo->prepare($sql);
      $exec->execute($dati);
      $returning = $exec->fetchAll();
      return array('res' => true, 'field'=>$returning[0][0] );
    } catch (\Exception $e) {
      return array('res'=>false, 'msg'=>$e->getMessage());
    }
  }
  public function buildInsert(string $tab, array $dati){
    $field = [];
    $value = [];
    foreach ($dati as $key => $val) {
      $v = $key == 'pwd' ? "crypt(:pwd, gen_salt('md5'))" : ":".$key;
      array_push($field,$key);
      array_push($value,$v);
    }
    $sql = "insert into ".$tab."(".join(",",$field).") values (".join(",",$value).");";
    return $sql;
  }

  public function buildUpdate(string $tab, array $filter, array $dati){
    $field = [];
    $where = [];
    foreach ($dati as $key => $val) {
      $v = $key == 'pwd' ? "crypt(:pwd, gen_salt('md5'))" : ":".$key;
      array_push($field,$key."=".$v);
    }
    foreach ($filter as $key => $val) { array_push($where,$key."=".$val); }
    $sql = "update ".$tab." set ".join(",",$field)." where ".join(" AND ", $where);
    return $sql;
  }

  public function begin(){
    $pdo = $this->pdo();
    $pdo->beginTransaction();
  }
  public function commit(){
    $pdo = $this->pdo();
    $pdo->commit();
  }
  public function rollback(){
    $pdo = $this->pdo();
    $pdo->rollBack();
  }

  public function __construct() {}
  private function __clone() {}
  private function __wakeup() {}

}
?>
>>>>>>> first commit
