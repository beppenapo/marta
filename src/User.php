<?php
namespace Marta;
session_start();
use \Marta\Conn;
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\SMTP;
use \PHPMailer\PHPMailer\Exception;

class User{
  public $db;
  public $mail;
  function __construct(){
    $this->db = new Conn;
    $this->mail = new PHPMailer(true);
  }
  public function listaReport(int $id=null){
    $filter = $id !== null ? 'and r.id = '.$id : '';
    return $this->db->simple("select r.id,r.data, r.report, r.utente usr, concat(u.nome,' ',u.cognome) utente from progetto.report r, utenti u where r.utente = u.id ".$filter." order by r.data desc, utente asc;");
  }
  public function addReport(array $dati){
    $this->db->begin();
    $msg = 'Il report è stato correttamente inserito';
    $sql = $this->db->buildInsert('progetto.report',$dati);
    $sql = rtrim($sql, ";") . " returning id;";
    $reportId = $this->db->returning($sql,$dati);
    $this->db->commit();
    if ($reportId['res']===false) {
      return array("res"=>false,"msg"=>$reportId['msg']);
    }else {
      return array("res"=>true,"msg"=>$msg, "id"=>$reportId['field']);
    }
  }

  public function editReport(array $dati){
    $filter = ['id'=>$dati['id']];
    unset($dati['id']);
    $sql = $this->db->buildUpdate('progetto.report',$filter,$dati);
    $res = $this->db->prepared($sql, $dati);
    if($res === true){$res = array('res'=>true, 'msg'=> "Il report è stato correttamente modificato");}
    return $res;
  }

  public function deleteReport(int $id){
    $dati = ['id'=>$id];
    $sql = "delete from progetto.report where id = :id;";
    $res = $this->db->prepared($sql, $dati);
    if($res === true){$res = array('res'=>true, 'msg'=> "Il report è stato definitivamente eliminato");}
    return $res;
  }

  public function getUser(int $id=null){
    $filter = $id !== null ? "where u.id = ".$id : "";
    $sql = "select u.id, u.cognome, u.nome, u.email, u.cellulare, u.classe as idclasse, c.classe, c.ico, u.attivo from utenti u join liste.userclass c on u.classe = c.id ".$filter." order by 6,2 asc;";
    return $this->db->simple($sql);
  }
  public function updatePwd(array $dati){
    $sql = "update utenti set pwd = crypt(:pwd, gen_salt('md5')) where id = :id";
    try {
      $this->db->prepared($sql, $dati);
      return true;
    } catch (\Exception $e) {
      return [$e->getMessage(), $e->getCode()];
    }
  }

  public function classList(){
    return $this->db->simple("select * from liste.userclass where id >= ".$_SESSION['classe']." order by 2 asc;");
  }

  public function aggiungiUsr(array $dati){
    try {
      $dati['pwd'] = $this->genPwd();
      $sql = $this->db->buildInsert("utenti", $dati);
      $this->db->prepared($sql, $dati);
      $datiMail=array("email"=>$dati['email'], "utente"=>$dati['nome']." ".$dati['cognome'], "pwd"=>$dati['pwd'], "tipo"=>1);
      $this->sendMail($datiMail);
      return array("utente creato con successo", 1);
    } catch (\Exception $e) {
      return [$e->getMessage(), $e->getCode()];
    }
  }
  public function modificaUsr(array $dati){
    try {
      $filter = array("id"=>$dati['id']);
      unset($dati['id']);
      $sql = $this->db->buildUpdate("utenti",$filter, $dati);
      $this->db->prepared($sql, $dati);
      return array("utente modificato con successo", 1);
    } catch (\Exception $e) {
      return [$e->getMessage(), $e->getCode()];
    }
  }

  public function modificaPassword(array $dati){
    try {
      $this->checkPwd($dati);
      $newData = array("id"=>$dati['id'], "pwd" =>$dati['new']);
      $this->updatePwd($newData);
      return ["Ok, password aggiornata. Dal prossimo login potrai utilizzare la nuova password", 1];
    } catch (\Exception $e) {
      return [$e->getMessage(), $e->getCode()];
    }

  }

  protected function checkPwd(array $dati){
    $sql = "SELECT id FROM utenti where id = ".$dati['id']." and pwd = crypt('".$dati['pwd']."',pwd) ;";
    $out = $this->db->simple($sql);
    $x = count((array)$out[0]);
    if ($x == 0) { throw new \Exception("La password inserita non è corretta, riprova o richiedi una nuova password", 0); }
    return true;
  }

  protected function genPwd(){
    $pwd = "";
    $pwdRand = array_merge(range('A','Z'), range('a','z'), range(0,9));
    for($i=0; $i < 10; $i++) {$pwd .= $pwdRand[array_rand($pwdRand)];}
    return $pwd;
  }
  protected function sendMail(array $dati){
    switch ($dati['tipo']) {
      case 1:
        $titolo = "Creazione nuovo account per il sistema di gestione del catalogo MArTA";
        $body = file_get_contents('../assets/mail/newUser.html');
        $body = str_replace('%utente%', $dati['utente'], $body);
        $body = str_replace('%password%', $dati['pwd'], $body);
      break;
      case 2:
        $titolo="Invio password di ripristino per il sistema di gestione del catalogo MArTA";
        $body = file_get_contents('../assets/mail/rescuePwd.html');
        $body = str_replace('%utente%', $dati['utente'], $body);
        $body = str_replace('%password%', $dati['pwd'], $body);
      break;
    }
    $mailParams = parse_ini_file('mail.ini');
    if ($mailParams === false) {
      throw new \Exception("Error reading mail configuration file",0);
    }
    $this->mail->isSMTP();
    // $this->mail->SMTPDebug = SMTP::DEBUG_SERVER; //da usare solo per i test, non stampa messaggi a video ma solo in console, non usare in produzione!!!!
    $this->mail->Host = $mailParams['host'];
    $this->mail->Port = $mailParams['port'];
    $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $this->mail->SMTPAuth = true;
    $this->mail->Username = $mailParams['usr'];
    $this->mail->Password = $mailParams['pwd'];
    $this->mail->setFrom($mailParams['usr'], 'MArTA crew');
    $this->mail->addAddress($dati['email'], $dati['utente']);
    $this->mail->Subject = $titolo;
    $this->mail->msgHTML($body, __DIR__);
    $this->mail->AltBody = $this->htmlToPlainText($body);
    if (!$this->mail->send()) {
      throw new \Exception('Mailer Error: '. $this->mail->ErrorInfo,0);
    }
    return true;
  }

  private function htmlToPlainText($str){
    $str = str_replace('&nbsp;', ' ', $str);
    $str = html_entity_decode($str, ENT_QUOTES | ENT_COMPAT , 'UTF-8');
    $str = html_entity_decode($str, ENT_HTML5, 'UTF-8');
    $str = html_entity_decode($str);
    $str = htmlspecialchars_decode($str);
    $str = strip_tags($str);
    return $str;
  }
}

?>
