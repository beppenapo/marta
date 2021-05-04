<nav id="mainMenu">
  <ul>
    <li>
      <a href="index.php" class='d-block m-0 mainLink animated' title="dashboard" data-toggle='tooltip' data-placement='left'><i class="fas fa-home fa-fw"></i> dashboard</a>
    </li>
    <li>
      <a href="#" class='d-block m-0 mainLink animated' title="archivio schede" data-toggle='tooltip' data-placement='left'><i class="fas fa-clipboard-list fa-fw"></i> archivio schede</a>
    </li>
    <li>
      <p class=''><i class="fas fa-database fa-fw"></i> gestione database</p>
      <ul class="subMenu">
        <li>
          <a href="scheda_lista.php?tipo=1" class="animated" data-toggle='tooltip' data-placement='left' title="lista schede reperto"><i class="fas fa-chevron-right fa-fw"></i> schede RA</a>
        </li>
        <li>
          <a href="scheda_lista.php?tipo=2" class="animated" data-toggle='tooltip' data-placement='left' title="lista schede numismatica"><i class="fas fa-chevron-right fa-fw"></i> schede NU</a>
        </li>
        <li>
          <a href="scheda_biblio_lista.php" class="animated" data-toggle='tooltip' data-placement='left' title="lista schede bibliografia"><i class="fas fa-chevron-right fa-fw"></i> Bibliografia</a>
        </li>
      </ul>
    </li>
    <?php if ($_SESSION['classe'] == 1) {?>
    <li>
      <p class=''><i class="fas fa-cogs fa-fw"></i> gestione sistema</p>
      <ul class="subMenu">
        <li>
          <a href="usr.php" class="animated" data-toggle='tooltip' data-placement='left' title="gestisci utenti di sistema"><i class="fas fa-chevron-right fa-fw"></i> utenti</a>
        </li>
        <li>
          <a href="#" class="animated" data-toggle='tooltip' data-placement='left' title="modifica le liste valori"><i class="fas fa-chevron-right fa-fw"></i> liste valori</a>
        </li>
      </ul>
    <?php } ?>
    </li>
    <li>
      <p class=''><i class="fas fa-user fa-fw"></i> Account</p>
      <ul class="subMenu">
        <li>
          <a href="myAccount.php" class="animated" data-toggle='tooltip' data-placement='left' title="modifica i tuoi dati personali"><i class="fas fa-chevron-right fa-fw"></i> dati personali</a>
        </li>
        <li>
          <a href="myPassword.php" class="animated" data-toggle='tooltip' data-placement='left' title="modifica la tua password"><i class="fas fa-chevron-right fa-fw"></i> modifica password</a>
        </li>
      </ul>
    </li>
    <li>
      <a href="" name="logOutBtn" class='d-block m-0 mainLink animated' title="chiudi sessione di lavoro" data-toggle='tooltip' data-placement='left'><i class="fas fa-power-off fa-fw"></i> logout</a>
    </li>
  </ul>
</nav>
