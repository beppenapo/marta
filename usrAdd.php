<?php
session_start();
if (!isset($_SESSION['id'])){ header("location:login.php");}
if (isset($_SESSION['id']) && $_SESSION['classe'] == 3){ header("location:index.php");}
$title = isset($_POST['id']) ? 'Modifica' : 'Aggiungi';
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/usr.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php require('assets/mainMenu.php'); ?>
    <div id="loadingDiv" class="flexDiv"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main>
      <div class="container">
        <form name="handleUser" data-action="<?php echo strtolower($title); ?>">
          <input type="hidden" name="utente" value="<?php echo $_POST['id']; ?>">
          <div class="form-row">
            <div class="col pb-2 mb-3 border-bottom">
              <h3><?php echo $title; ?> utente</h3>
              <small>* campi obbligatori</small>
            </div>
          </div>
          <fieldset class="form-group">
            <label for="cognome">*Cognome</label>
            <input type="text" class="form-control form-control-sm" id="cognome" name="cognome" placeholder="Inserisci il cognome" required>
          </fieldset>
          <fieldset class="form-group">
            <label for="nome">*Nome</label>
            <input type="text" class="form-control form-control-sm" id="nome" name="nome" placeholder="Inserisci il nome" required>
          </fieldset>
          <fieldset class="form-group">
            <label for="cellulare">Cellulare</label>
            <input type="text" class="form-control form-control-sm" id="cellulare" name="cellulare" placeholder="Cellulare">
          </fieldset>
          <fieldset class="form-group">
            <label for="email">*Email</label>
            <input type="email" class="form-control form-control-sm" id="email" name="email" placeholder="Email" required>
            <small class="text-danger">La mail verrà utilizzata per l'accesso al sistema, per l'invio della nuova password e per il suo recupero, perciò assicurati di inserire la mail corretta</small>
          </fieldset>
          <fieldset class="form-group">
            <label for="classe">*Classe utente</label>
            <select class="form-control form-control-sm" id="classe" name="classe" required>
              <option value="" disabled selected>--scegli classe--</option>
            </select>
          </fieldset>
          <fieldset class="form-group">
            <label>Stato account</label>
            <div class="custom-control custom-radio">
              <input type="radio" id="attiva" name="attivo" class="custom-control-input" value="true" checked>
              <label class="custom-control-label" for="attiva">attiva account</label>
            </div>
            <div class="custom-control custom-radio">
              <input type="radio" id="disattiva" name="attivo" class="custom-control-input" value="false">
              <label class="custom-control-label" for="disattiva">disattiva account</label>
            </div>
            <small class="text-danger">disattivando l'account, i dati dell'utente non verranno cancellati dal database ma l'utente non potrà più accedere al sistema. L'account potrà essere riattivato in qualunque momento.</small>
          </fieldset>
          <div class="form-row">
            <div class="col">
              <button type="submit" class="btn btn-sm btn-primary">invia dati</button>
            </div>
          </div>
          <div class="mt-3 alert alert-success text-center" role="alert"></div>
        </form>
      </div>
    </main>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/usrEdit.js" charset="utf-8"></script>
  </body>
</html>
