<?php
session_start();
require 'vendor/autoload.php';
use \Marta\Scheda;
$obj = new Scheda();
$fake = $obj->getBiblioFake();
$fakeArr = [];
foreach ($fake as $i) {
  $anno = $i['anno'] ? $i['anno'].", ": '';
  $pagArr = [];
  if($i['pagine']!== null){array_push($pagArr, "pag. ".$i['pagine']);}
  if($i['figure']!== null){array_push($pagArr, "fig. ".$i['figure']);}
  $pag = count((array)$pagArr) == 0 ? '' : "(".implode(', ', $pagArr).")";
  if ($i['contrib_id'] !== null) {
    $el = $i['contrib_aut'].", ".$i['contrib_tit'].", ".$pag." presente in: ".$i['autore'].", ". $anno .$i['titolo'];
  }else {
    $el = $i['autore'].", ". $anno .$i['titolo'].", ".$pag;
  }
  $item = array("scheda"=>$i['scheda'], "riferimento"=>$el);
  array_push($fakeArr,$item);
}
$add = $obj->addBiblioFake($fakeArr);
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/main.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <div id="loadingDiv" class="flexDiv"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main>
      <div class="card w-75 mx-auto">
        <?php echo "<h3>".$add."</h3>"; ?>
        <!-- <?php print_r($fakeArr); ?> -->
      </div>
    </main>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
  </body>
</html>
