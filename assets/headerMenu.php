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
      <li><a href="territorio.php" class="animated" data-toggle='tooltip' data-placement="bottom" title="esplora il territorio e scopri da dove provengono i reperti del Museo">scopri</a></li>
      <li><a href="sfoglia.php" class="animated" data-toggle='tooltip' data-placement="bottom" title="sfoglia il catalogo completo e crea le tue gallery personalizzate">sfoglia</a></li>
      <!-- <li>
        <a class="dropdown-toggle animated" href="#" role="button" id="headerMenuLinkArchivi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">archivi</a>
        <div class="dropdown-menu" aria-labelledby="headerMenuLinkArchivi">
          <a class="dropdown-item" href="schede.php">schede</a>
          <a class="dropdown-item" href="bibliografia.php">bibliografia</a>
          <a class="dropdown-item disabled" href="">modelli 3d</a>
        </div>
      </li> -->
      <?php if (!isset($_SESSION['id'])) {?>
        <li><a href="login.php" class="animated" data-toggle='tooltip' data-placement="bottom" title="entra nell'area riservata">login</a></li>
      <?php }else { ?>
        <li><a href="#" id="toggleMenu" class="animated"><i class="fas fa-bars"></i></a></li>
      <?php } ?>
    </ul>
  </div>
</header>
