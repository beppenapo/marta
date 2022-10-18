<?php
$logged = isset($_SESSION['id']) ? 'y' : 'n';
?>
<header class="shadow" data-log="<?php echo $logged; ?>">
  <div class="logo"><img src="img/loghi/logo_completo_nero.png" height="60" alt="logo header"></div>
  <div class="headerMenu">
    <ul>
      <li><a href="index.php" class="animated" data-toggle='tooltip' data-placement="bottom" title="torna alla pagina principale">home</a></li>
      <li><a href="esplora.php" class="animated" data-toggle='tooltip' data-placement="bottom" title="esplora il museo attraverso le piante interattive">esplora</a></li>
      <li>
        <a class="dropdown-toggle animated" href="#" role="button" id="headerMenuLinkArchivi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">archivi</a>
        <div class="dropdown-menu" aria-labelledby="headerMenuLinkArchivi">
          <a class="dropdown-item" href="schede.php">archivio schede</a>
          <a class="dropdown-item" href="bibliografia.php">archivio bibliografico</a>
        </div>
      </li>
      <li><a href="board.php" class="animated" data-toggle='tooltip' data-placement="bottom" title="leggi le informazioni relative al progetto">project</a></li>
      <li><a href="#" class="animated" data-toggle='tooltip' data-placement="bottom" title="conosci il gruppo di lavoro">team</a></li>
      <?php if (!isset($_SESSION['id'])) {?>
        <li><a href="login.php" class="animated" data-toggle='tooltip' data-placement="bottom" title="entra nell'area riservata">login</a></li>
      <?php }else { ?>
        <li><a href="#" id="toggleMenu" class="animated"><i class="fas fa-bars"></i></a></li>
      <?php } ?>
    </ul>
  </div>
</header>
