<?php
namespace Marta;
session_start();
use \Marta\Conn;
use \Marta\User;
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\SMTP;
use \PHPMailer\PHPMailer\Exception;
/**
 *
 */
class Login extends User{
  public $mail;
  public $db;
  function __construct(){
    $this->db = new Conn;
    $this->mail = new PHPMailer(true);
  }
  public function login(array $dati){
    try {
      $user = $this->checkMail($dati['email']);
      $checkPwd = array("id"=>$user['id'], "pwd"=>$dati['pwd']);
      $this->checkPwd($checkPwd);
      $setSession = array($user['id'],$user['nome']." ".$user['cognome'],$user['classe']);
      $this->setSession($setSession);
      return ["Credenziali corrette, stai per essere reindirizzato nella dashboard del sistema.", 1, $user['id']];
    } catch (\Exception $e) {
      return [$e->getMessage(), $e->getCode()];
    }
    return $dati;
  }
  public function rescuePwd(string $email){
    try {
      $user = $this->checkMail($email);
      $newPwd = $this->genPwd();
      $this->updatePwd(array("id"=>$user['id'], "pwd"=>$newPwd));
      $datiMail=array("email"=>$email, "utente"=>$user['nome']." ".$user['cognome'], "pwd"=>$newPwd, "tipo"=>2);
      $this->sendMail($datiMail);
      return ["La password appena creata è stata inviata all'indirizzo email indicato.", 1];
    } catch (\Exception $e) {
      return [$e->getMessage(), $e->getCode()];
    }
  }
  private function checkMail(string $email){
    $sql = "select * from utenti where email = '".$email."' and attivo = 't';";
    $out = $this->db->simple($sql);
    $x = count($out[0]);
    if ($x == 0) { throw new \Exception("La mail indicata non è presente nel database o il tuo account è disabilitato. Prova a reinserire la mail, se il problema persiste contatta il responsabile del progetto", 0); }
    return $out[0];
  }

  // private function checkPwd(array $dati){
  //   $sql = "SELECT id FROM utenti where id = ".$dati['id']." and pwd = crypt('".$dati['pwd']."',pwd) ;";
  //   $out = $this->db->simple($sql);
  //   $x = count($out[0]);
  //   if ($x == 0) { throw new \Exception("La password inserita non è corretta, riprova o richiedi una nuova password", 0); }
  //   return true;
  // }


  private function setSession(array $dati){
    $_SESSION['id'] = $dati[0];
    $_SESSION['utente'] = $dati[1];
    $_SESSION['classe'] = $dati[2];
    return true;
  }
}

?>
