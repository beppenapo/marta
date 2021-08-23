<?php require('api/php/uploadFile.php'); ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/upload.css">
    <style media="screen">
    body>main {padding-top: 60px !important;}
    </style>
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php if (isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <main>
      <div class="container mt-5">
        <h3 id="title" class="border-bottom border-dark mb-5"><?php echo $title; ?></h3>
        <form method="post" action="" name="uploadFile" id="uploadFile" enctype="multipart/form-data">
          <input type="hidden" name="scheda" value="<?php echo $_GET['sk']; ?>">
          <input type="hidden" name="tipo" value="<?php echo $_GET['t']; ?>">
          <div class="row py-4">
            <div class="col-lg-6 mx-auto">
              <div class="input-group mb-3 px-2 py-2 bg-white shadow-sm">
                <input id="upload" name="upload" type="file" class="form-control border-0">
                <label id="upload-label" for="upload" class="font-weight-light text-muted">upload file</label>
                <div class="input-group-append">
                  <label for="upload" class="btn btn-light m-0 rounded-pill px-4"> <i class="fa fa-cloud-upload mr-2 text-muted"></i><small class="text-uppercase font-weight-bold text-muted">Scegli file da caricare</small></label>
                </div>
              </div>
              <p class="font-italic text-center" id="msg"></p>
              <div class="image-area mt-4">
                <figure>
                  <img id="imageResult" src="" alt="" class="img-fluid rounded shadow-sm mx-auto d-block">
                  <figcaption></figcaption>
                </figure>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 mx-auto">
              <button type="submit" name="carica" class="btn btn-marta">carica</button>
            </div>
          </div>
        </form>
      </div>
    </main>
    <?php require('assets/toast.html'); ?>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/uploadFile.js" charset="utf-8"></script>
  </body>
</html>
