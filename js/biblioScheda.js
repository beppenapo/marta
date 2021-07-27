const API = 'api/biblio.php';
$(document).ready(function() {
  $("[name=submit]").on('click', (e) => {
    const form = $("form");
    if(form[0].checkValidity()){
      e.preventDefault();
      dati = {}
      dati.scheda = $("[name=scheda]").val()
      dati.biblio = $("[name=biblio]").val()
      dati.livello = $("[name=livello]").val()
      if($("[name=pagine]").val()){dati.pagine = $("[name=pagine]").val()}
      if($("[name=figure]").val()){dati.figure = $("[name=figure]").val()}
      $.ajax({
        url: API,
        type: 'POST',
        dataType: 'json',
        data: {trigger : 'biblioScheda', dati}
      })
      .done(function(data) {
        console.log(data);
        data.url='schedaView.php?get='+dati.scheda;
        classe = data.res === true ? 'bg-success' : 'bg-danger';
        $(".toast").removeClass('[class^="bg-"]').addClass(classe);
        $("#headerTxt").html('Risultato query');
        $(".toast>.toast-body>.toast-body-msg").html(data.msg);
        $(".toast").toast({delay:3000});
        $(".toast").show();
        $(".toast").toast('show');
        $("[name='continua']").on('click', function(){window.location.reload(true);})

        if (data.res === true) {
          $("[name='continua']").show();
          $("[name='viewRec']").text('torna alla scheda').on('click', function(){window.location.href = data.url});
        }else {
          $("[name='viewRec']").text('ok, chiudi alert');
          $("[name='continua']").hide();
          $('.toast').on('hidden.bs.toast', function () {
            $(".toast").removeClass(classe);
            $(".toast").hide();
          })
        }
      })
      .fail(function(){console.log("error");});
    }
  })
});
