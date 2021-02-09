<?php session_start() ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/login.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <main class="container text-center">
      <?php if(isset($_SESSION['id'])){ ?>
        <div id="logged" class="mt-5">
          <h3>Ciao <span id="utente"></span>, come mai qui?</h3>
          <p>Hai già effettuato il login, se hai notato qualcosa di strano ti consiglio di terminare la sessione di lavoro corrente e rifare la procedura di autenticazione!</p>
        </div>
      <?php }else{ ?>
        <div id="login" class="m-auto">
          <form class="form" name="loginForm">
            <div class="form-group mb-3">
              <label for="email">Inserisci la tua email</label>
              <input type="email" name="email" class="form-control" value="" required>
            </div>
            <div class="form-group">
              <label for="email">Inserisci la password</label>
              <div class="input-group">
                <input type="password" id="pwd" name="pwd" class="form-control" value="" required>
                <div class="input-group-append">
                  <span class="input-group-text toggle-password" data-toggle='#pwd'>
                    <i class="fa fa-fw fa-eye"></i>
                  </span>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col">
                <div class="outputMsg"></div>
              </div>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-info btn-sm mt-3" id="submit" data-form='loginForm'>entra</button>
              <button type="button" class="btn btn-secondary btn-sm mt-3" data-toggle="collapse" data-target="#rescueForm">password dimenticata</button>
            </div>
          </form>
          <div class="collapse" id="rescueForm">
            <form class="form" name="rescueForm">
              <p>inserisci la mail con cui ti sei registrato al sistema, il server ti invierà una nuova password</p>
              <div class="form-group mb-3">
                <label for="email">Inserisci email</label>
                <input type="email" name="rescueEmail" class="form-control" value="" required>
              </div>
              <div class="form-row mb-3">
                <div class="col">
                  <div class="outputMsg"></div>
                </div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-info btn-sm" id="submitRescue" data-form='rescueForm'>invia richiesta</button>
                <button type="button" class="btn btn-secondary btn-sm" name="cancelRescue" data-toggle='collapse' data-target="#rescueForm">annulla operazione</button>
              </div>
            </form>
          </div>
        </div>
      <?php } ?>
    </main>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/login.js" charset="utf-8"></script>
  </body>
</html>
