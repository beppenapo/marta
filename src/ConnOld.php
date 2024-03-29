<?php
namespace Marta;
use PDO;
class ConnOld {
  private static $conn;
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

    $pdo = new \PDO($conStr);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    return $pdo;
  }

  /**
  * return an instance of the Connection object
  * @return type
  */
  public static function get() {
    if (null === static::$conn) { static::$conn = new static(); }
    return static::$conn;
  }

  public function simple($sql){
    try {
      $pdo = $this->connect();
      $exec = $pdo->prepare($sql);
      $exec->execute();
      return $exec->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function prepared($sql, $dati=array()){
    try {
      $pdo = $this->connect();
      $exec = $pdo->prepare($sql);
      $exec->execute($dati);
      return true;
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function returning($sql, $dati=array()){
    try {
      $pdo = $this->connect();
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
    $pdo = $this->connect();
    $pdo->beginTransaction();
  }
  public function commit(){
    $pdo = $this->connect();
    $pdo->commit();
  }
  public function rollback(){
    $pdo = $this->connect();
    $pdo->rollBack();
  }

  public function __construct() {}
  private function __clone() {}
  private function __wakeup() {}

}
?>
