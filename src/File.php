<?php
namespace Marta;
use \Marta\Scheda;
use Imagick;
class File extends Scheda{
  public $dirOrig = "../file/foto/orig/";
  public $dirLarge = "../file/foto/large/";
  public $dirMedium = "../file/foto/medium/";
  public $dirSmall = "../file/foto/small/";
  function __construct(){
    
  }
  public function uploadImage($dati, $file){
    try {
      if (!isset($file["file"])) { throw new \Exception("Non hai caricato nessun tipo di file", 1);}
      $filename = $_FILES['file']['tmp_name'];
      $fileSize = filesize($filename);
      $bytes2mb = intval($fileSize) / 1000000;
      $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
      $filetype = finfo_file($fileinfo, $filename);
      if ($fileSize === 0) { throw new \Exception("Attenzione! Il file risulta vuoto", 1);}
      if ($bytes2mb > 10) {
        throw new \Exception("Attenzione, immagine troppo grande! Il massimo consentito è di 10mb mentre l'immagine caricata è di ".round($bytes2mb,2)."mb. Riduci l'immagine e riprova", 1);
      }
      $name = $this->renameImage($dati['scheda'],$file['file']['name']);
      if (!move_uploaded_file($filename,$this->dirOrig.$name)) {throw new \Exception("Errore nel caricamento dell'immagine nella cartella large", 1);}
      chmod($this->dirOrig.$name,0777);

      if (!copy($this->dirOrig.$name,$this->dirLarge.$name)) {throw new \Exception("Errore nel copiare l'immagine nella cartella large", 1);}
      chmod($this->dirLarge.$name,0777);
      if (!copy($this->dirOrig.$name,$this->dirMedium.$name)) {throw new \Exception("Errore nel copiare l'immagine nella cartella medium", 1);}
      chmod($this->dirMedium.$name,0777);
      if (!copy($this->dirOrig.$name,$this->dirSmall.$name)) {throw new \Exception("Errore nel copiare l'immagine nella cartella small", 1);}
      chmod($this->dirSmall.$name,0777);
      $this->modifyImage($this->dirLarge.$name,500);
      $this->modifyImage($this->dirMedium.$name,250);
      $this->modifyImage($this->dirSmall.$name,150);
      $dati = array("scheda"=>$dati['scheda'],"file"=>$name,"tipo"=>3);
      $this->saveFile($dati);

      return array('res' => true, 'msg'=>"l'immagine è stata caricata sul server");
    }
    catch (\PDOException $e) {return array("res"=>false, "msg"=>$e->getMessage());}
    catch (\Exception $e) {return array("res"=>false, "msg"=>$e->getMessage());}
    catch (\ImagickException $e){return array("res"=>false, "msg"=>$e);}
  }
  private function renameImage($scheda,$nome){
    $prefix = "TA16M325";
    $nctn = $this->nctnScheda($scheda);
    $countImage = $this->countSchedaImage($scheda);
    $counter = intval($countImage) + 1;
    $ext = explode(".",$nome);
    $ext = array_pop($ext);
    $name = implode("_",[$prefix,$nctn,$counter]);
    $name = $name.".".$ext;
    return $name;
  }

  private function nctnScheda(int $scheda){
    $res = $this->simple("select nctn from nctn_scheda where scheda = ".$scheda.";");
    return $res[0]['nctn'];
  }
  private function countSchedaImage(int $scheda){
    $res = $this->simple("select count(*) from file where tipo = 3 and scheda = ".$scheda.";");
    return $res[0]['count'];
  }
  private function modifyImage($img,$dim){
    try {
      $image = new Imagick(realpath($img));
      $w = $image->getImageWidth();
      $h = $image->getImageHeight();
      if ($w > $h) {
        $new_w = $w * $dim / $h;
        $new_h = $dim;
      }
      else {
        $new_w = $dim;
        $new_h = $h * $dim / $w;
      }
      $image->shaveImage(100, 100);
      $image->resizeImage($new_w, $new_h, Imagick::FILTER_LANCZOS, 0.9,false);
      $image->cropImage($dim, $dim, ($new_w - $dim) / 2, ($new_h - $dim) / 2);
      $image->setImageExtent($dim,$dim);
      $image->setImageCompression(Imagick::COMPRESSION_JPEG);
      $image->setImageCompressionQuality(75);
      $image->stripImage();
      $image->writeImage($img);
      $image->destroy();
      return true;
    } catch (\ImagickException $e) {
      return $e;
    }

  }

  private function saveFile(array $dati){
    $sql = $this->buildInsert("file",$dati);
    $res = $this->prepared($sql,$dati);
    if (!$res) { throw new \Exception($res, 1);}
    return $res;
  }

  public function delImg(array $dati){
    try {
      unlink($this->dirOrig.$dati['file']);
      unlink($this->dirLarge.$dati['file']);
      unlink($this->dirMedium.$dati['file']);
      unlink($this->dirSmall.$dati['file']);
      $sql = "delete from file where id = :id and scheda = :scheda and file = :file;";
      $res = $this->prepared($sql, $dati);
      if (!$res) { throw new \Exception($res, 1);}
      return $res;
    } catch (\Exception $e) {
      return array("res"=>false, "msg"=>$e->getMessage());
    }

  }
}

?>
