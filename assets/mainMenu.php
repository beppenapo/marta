<nav id="mainMenu" class="noPrint">
  <ul>
    <li>
      <a href="dashboard.php" class='d-block m-0 mainLink animated' title="dashboard" data-toggle='tooltip' data-placement='left'><i class="fas fa-home fa-fw"></i> dashboard</a>
    </li>
    <li>
      <p class='text-primary'><i class="fas fa-archive fa-fw"></i> archivi</p>
      <ul class="subMenu">
        <li>
          <a href="schede.php" class='d-block m-0 animated' title="archivio schede" data-toggle='tooltip' data-placement='left'><i class="fas fa-chevron-right fa-fw"></i> schede</a>
        </li>
        <li>
          <a href="bibliografia.php" class='d-block m-0 animated' title="archivio record bibliografici" data-toggle='tooltip' data-placement='left'><i class="fas fa-chevron-right fa-fw"></i> bibliografia</a>
        </li>
      </ul>
    </li>
    <li>
      <p class='text-info'><i class="fas fa-folder-open fa-fw"></i> aggiungi authority file</p>
      <ul class="subMenu">
        <!-- <li>
          <a href="#" class="animated" data-toggle='tooltip' data-placement='left' title="inserisci una nuova ricognizione"><i class="fas fa-chevron-right fa-fw"></i> ricognizione</a>
        </li> -->
        <!-- <li>
          <a href="#" class="animated" data-toggle='tooltip' data-placement='left' title="inserisci una nuova scheda sito"><i class="fas fa-chevron-right fa-fw"></i> scheda sito</a>
        </li> -->
        <li>
          <a href="bibliografia_add.php" class="animated" data-toggle='tooltip' data-placement='left' title="inserisci un nuovo record bibliografico"><i class="fas fa-chevron-right fa-fw"></i> bibliografia</a>
        </li>
        <li>
          <a href="contributo_add.php" class="animated" data-toggle='tooltip' data-placement='left' title="inserisci un nuovo contributo"><i class="fas fa-chevron-right fa-fw"></i> contributo</a>
        </li>
      </ul>
    </li>
    <li>
      <p class='text-success'><i class="fas fa-database fa-fw"></i> aggiungi record</p>
      <ul class="subMenu">
        <li>
          <a href="scheda-ra.php" class="animated" data-toggle='tooltip' data-placement='left' title="inserisci una nuova scheda RA"><i class="fas fa-chevron-right fa-fw"></i> scheda RA</a>
        </li>
        <li>
          <a href="scheda-nu.php" class="animated" data-toggle='tooltip' data-placement='left' title="inserisci una nuova scheda NU"><i class="fas fa-chevron-right fa-fw"></i> scheda NU</a>
        </li>
      </ul>
    </li>
    <?php if ($_SESSION['classe'] == 1) {?>
    <li>
      <p class='text-warning'><i class="fas fa-cogs fa-fw"></i> gestione sistema</p>
      <ul class="subMenu">
        <!-- <li>
          <a href="usr.php" class="animated" data-toggle='tooltip' data-placement='left' title="gestisci utenti di sistema"><i class="fas fa-chevron-right fa-fw"></i> utenti</a>
        </li> -->
        <li>
          <a href="#" class="animated" data-toggle='tooltip' data-placement='left' title="modifica le liste valori"><i class="fas fa-chevron-right fa-fw"></i> liste valori</a>
        </li>
        <li>
          <a href="report.php" class="animated" data-toggle='tooltip' data-placement='left' title="gestisci report personali"><i class="fas fa-chevron-right fa-fw"></i> report</a>
        </li>
      </ul>
    </li>
  <?php } ?>
    <li>
      <p class='text-danger'><i class="fas fa-user fa-fw"></i> Account</p>
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
