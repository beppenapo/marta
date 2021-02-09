<header class="shadow">
  <div class="logo"><img src="img/loghi/logo_completo_nero.png" height="60" alt="logo header"></div>
  <div class="headerMenu">
    <ul>
      <li><a href="home.php" class="animated" data-toggle='tooltip' data-placement="bottom" title="torna alla pagina principale">home</a></li>
      <li><a href="board.php" class="animated" data-toggle='tooltip' data-placement="bottom" title="leggi le informazioni relative al progetto">project</a></li>
      <li><a href="#" class="animated" data-toggle='tooltip' data-placement="bottom" title="conosci il gruppo di lavoro">team</a></li>
      <li><a href="#" class="animated" data-toggle='tooltip' data-placement="bottom" title="contattaci per suggerimenti o per dirci cosa ne pensi">contacts</a></li>
      <?php if (!isset($_SESSION['id'])) {?>
        <li><a href="login.php" class="animated" data-toggle='tooltip' data-placement="bottom" title="entra nell'area riservata">login</a></li>
      <?php }else { ?>
        <li><a href="#" id="toggleMenu" class="animated"><i class="fas fa-bars"></i></a></li>
      <?php } ?>
    </ul>
  </div>
</header>
