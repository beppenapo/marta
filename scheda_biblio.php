<?php
session_start();
if (!isset($_SESSION['id'])){ header("location:login.php");}
require_once('funzioni.php');
$action = filtraGet('act');
switch ($action) {
  case 'add': $title = 'Aggiungi una nuova scheda bibliografia'; break;
  case 'edit': $title = 'Modifica scheda bibliografia'; break;
  case 'view': $title = 'Visualizza scheda bibliografia'; break;
}
$id_biblio = 0;
if (isset($_GET['id_biblio'])) {
	$id_biblio = filtraInt($_GET['id_biblio']);
}
$id_scheda = 0;
if (isset($_GET['id_scheda'])) {
	$id_scheda = filtraInt($_GET['id_scheda']);
}
$tipo = 0;
$tipo_title = "";
if (isset($_GET['tipo'])) {
	$tipo = filtraInt($_GET['tipo']);
  	$tipo_title = $tipo == 1 ? 'RA' : 'NU';
}
$act_scheda = "";
if (isset($_GET['act_scheda'])) {
	$act_scheda = filtraGet('act_scheda');
}
?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/scheda.css">
  </head>
  <body>
    <?php require('assets/headerMenu.php'); ?>
    <?php require('assets/mainMenu.php'); ?>
    <div id="loadingDiv" class="flexDiv invisible"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
    <main>
      <div class="container">
        <div class="row mb-4">
          <div class="col">
            <h3 class="border-bottom"><?php echo $title; ?></h3>
            <small class="text-danger font-weight-bold d-block">* Campi obbligatori</small>
          </div>
        </div>
		<?php if ($id_scheda > 0) : ?>
        <a href="scheda.php?tipo=<?php echo $tipo; ?>&act=<?php echo $act_scheda; ?>&id=<?php echo $id_scheda; ?>#divbiblio" target="_self" title="Torna alla scheda <?php echo $tipo_title; ?>">Torna alla scheda <?php echo $tipo_title; ?></a>
        <br /><br />
		<?php if ($action == 'add') : ?>
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">AGGIUNGI BIBLIOGRAFIA ESISTENTE ALLA SCHEDA</legend>
              <div class="row">
                <div class="col-md-4">
                  <label for="biblioexist" class="text-danger font-weight-bold">Scheda bibliografica</label>
                  <input type="text" class="form-control form-control-sm tab" id="biblioexist" name="biblioexist" value="" required>
                  <input type="hidden" data-table="biblio_scheda" id="biblioexistval" name="biblioexistval" value="">
                </div>
              </div>
            </fieldset>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-6">
              <button type="submit" class="btn btn-sm btn-marta tastischeda" name="addbiblioexist" id="addbiblioexist">conferma</button>
            </div>
          </div>
          <br /><b>oppure inserisci un nuovo record:</b>
        </div>
        <?php endif; ?>
        <?php endif; ?>
        <form id="formScheda" autocomplete="off">
          <input type="hidden" name="data_ins" class="tab" data-table="bibliografia" value="<?php echo date('Y-m-d H:i:s'); ?>">
          <input type="hidden" name="compilatore" class="tab" data-table="bibliografia" value="<?php echo $_SESSION['id']; ?>">
          <div class="form-group">
            <fieldset class="bg-light rounded border p-3">
              <legend class="w-auto bg-marta text-white border rounded p-1">SCHEDA BIBLIOGRAFIA</legend>
              <div class="row">
                <div class="col-md-4">
                  <label for="titolo" class="text-danger font-weight-bold">Titolo</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="bibliografia" id="titolo" name="titolo" value="" required>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="tipo" class="text-danger font-weight-bold">Tipo</label>
                  <select class="form-control form-control-sm" id="tipo" name="tipo" required>
                    <option selected disabled>-- tipo --</option>
                    <option value="1">Monografia</option>
                    <option value="2">Atti convegno</option>
                    <option value="3">Articolo in rivista</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="autore" class="text-danger font-weight-bold">Autore</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="bibliografia" id="autore" name="autore" value="" required>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="altri_autori" class="font-weight-bold">Altri autori</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="bibliografia" id="altri_autori" name="altri_autori" value="">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="titolo_raccolta" class="font-weight-bold">Titolo raccolta</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="bibliografia" id="titolo_raccolta" name="titolo_raccolta" title="specificare il titolo degli atti del convegno o della raccolta" value="">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="editore" class="font-weight-bold">Editore</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="bibliografia" id="editore" name="editore" value="">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="anno" class="font-weight-bold">Anno</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="bibliografia" id="anno" name="anno" value="">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="luogo" class="font-weight-bold">Luogo</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="bibliografia" id="luogo" name="luogo" value="">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="isbn" class="font-weight-bold">ISBN</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="bibliografia" id="isbn" name="isbn" value="">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="url" class="font-weight-bold">Url</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="bibliografia" id="url" name="url"  title="se il record bibliografico è disponibile on-line, indicare il link alla risorsa" value="">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="pagine" class="font-weight-bold">Pagine</label>
                  <input type="text" class="form-control form-control-sm tab" data-table="bibliografia" id="pagine" name="pagine" value="">
                </div>
              </div>
            </fieldset>
          </div>
          <?php if ($action!=='view') {?>
          <div class="form-group">
            <div class="row">
              <div class="col-6">
                <button type="submit" class="btn btn-sm btn-marta tastischeda" name="submit" id="submit">salva dati</button>
              </div>
              <?php if ($action!=='add') {?>
              <div class="col-6 text-right">
                <button type="submit" class="btn btn-sm btn-danger tastischeda" name="elimina_scheda" id="elimina_scheda">elimina scheda</button>
              </div>
            <?php } ?>
            </div>
          </div>
          <?php } ?>
        </form>
      </div>

    </main>
    <script>let id_biblio = <?php echo $id_biblio; ?>; let id_scheda = <?php echo $id_scheda; ?>; let action = "<?php echo $action; ?>"; let tipoScheda = <?php echo $tipo; ?>; let act_scheda = "<?php echo $act_scheda; ?>";</script>
    <?php require('assets/toast.html'); ?>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/scheda_biblio.js" charset="utf-8"></script>
  </body>
</html>
