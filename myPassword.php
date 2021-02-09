<?php
session_start();
if (!isset($_SESSION['id'])){ header("location:login.php");}
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/usr.css">
    <link rel="stylesheet" href="css/meter.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php require('assets/mainMenu.php'); ?>
    <div id="loadingDiv" class="flexDiv invisible"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main>
      <div class="container">
        <form name="handleUser" autocomplete="off">
          <input type="hidden" name="utente" value="<?php echo $_SESSION['id']; ?>">
          <div class="form-row">
            <div class="col pb-2 mb-3 border-bottom">
              <h3>Modifica password</h3>
              <small>* campi obbligatori</small>
            </div>
          </div>
          <fieldset class="form-group">
            <label for="old">*Password in uso</label>
            <input type="password" class="form-control form-control-sm" id="old" name="old" placeholder="Inserisci password in uso" autocomplete="off" value="" required>
          </fieldset>
          <fieldset class="form-group">
            <label for="new">*Nuova password</label>
            <small class="d-block text-muted">La password deve contenere almeno 8 caratteri di cui almeno 1 lettera maiuscola, 1 minuscola, 1 numero, 1 carattere speciale (es. !£$%&-_) e non deve contenere spazi</small>
            <div class="input-group">
              <input type="password" class="form-control form-control-sm" id="new" name="new" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" placeholder="Nuova password" required>
              <div class="input-group-append">
                <span class="input-group-text toggle-password" data-toggle='#new'>
                  <i class="fa fa-fw fa-eye"></i>
                </span>
              </div>
            </div>
            <meter max="4" id="password-strength-meter"></meter>
            <div id="password-strength-text" class="bg-light"></div>
          </fieldset>
          <fieldset class="form-group">
            <label for="conferma">*Conferma nuova password</label>
            <div class="input-group">
              <input type="password" class="form-control form-control-sm" id="confirm" name="confirm" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" placeholder="Reinserisci la nuova password" required>
              <div class="input-group-append">
                <span class="input-group-text toggle-password" data-toggle='#confirm'>
                  <i class="fa fa-fw fa-eye"></i>
                </span>
              </div>
            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/myPassword.js" charset="utf-8"></script>
  </body>
</html>
