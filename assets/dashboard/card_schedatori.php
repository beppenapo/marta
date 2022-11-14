<div class="card" id="schedatori">
  <div class="card-header bg-white font-weight-bold">
    <p class="card-title m-0">Schedatori <span class="float-right">num. schede</span> </p>
  </div>
  <div class="list-group scroller" id="wrapSchedatori">
    <div class="list-group list-group-flush">
      <?php
      foreach ($schedatoriList as $item) {
        $disabled = $item['schede'] == 0 ? 'disabled' : '';
        $tooltip = $item['schede'] == 0 ? '' : 'data-toggle="tooltip" data-placement="left" title="visualizza le schede di <br>'.$item['utente'].'"';
        echo "<button type='button' class='btn-sm list-group-item list-group-item-action checkBtn' name='usrBtn' ".$tooltip." value='".$item['id']."' ".$disabled."><span>".$item['utente']."</span><span class='float-right'>".$item['schede']."</span></button>";
      }
      ?>
    </div>
  </div>
</div>
