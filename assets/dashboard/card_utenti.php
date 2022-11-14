<div class="card" id="utenti">
  <div class="card-header bg-white font-weight-bold">
    <p class="card-title m-0">Utenti</p>
  </div>
  <div class="card-body">
    <table id="dataTable" class="dataTable table table-striped table-bordered">
      <thead>
        <tr>
          <th>Utente</th>
          <th>Email</th>
          <th class="no-sort">Telefono</th>
          <?php if($_SESSION['classe'] !==3){ ?>
          <th class="no-sort"></th>
          <th class="no-sort"></th>
          <th class="no-sort"></th>
          <?php } ?>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
  <div class="card-footer">
    <a href="usrAdd.php" class="btn btn-sm btn-outline-marta"><i class="fas fa-plus"></i> nuovo utente</a>
  </div>
</div>
