$(document).ready(function() {
  $(".list-group-item > span").each(function(){
    if ($(this).text()=='dato non inserito') {
      $(this).removeClass('font-weight-bold').addClass('text-secondary')
    }
  })
  if ($("[name=mapInit]").val() > 0) {
    $("#mappa").css("height",$("#mappa").width()/2);
    mapInit()
  }

  $("[name=delBiblioScheda]").on('click', function() {
    dati = {}
    dati.biblio = $(this).data('biblio');
    dati.scheda = $(this).data('scheda');
    if (window.confirm("vuoi davvero eliminare l'associazione tra il record bibliografico scelto e la presente scheda?")) {
      delBiblioScheda(dati);
    }
  });
});

function delBiblioScheda(dati){
  $.ajax({
    url: "api/scheda.php",
    type: 'POST',
    dataType: 'json',
    data: {trigger:'delBiblioScheda', dati:dati}
  })
  .done(function(data){
    obj={}
    obj.titolo='Risultato query';
    obj.res = data
    obj.msg = 'il riferimento bibliografico è stato eliminato';
    obj.classe = obj.res === true ? 'bg-success' : 'bg-danger';
    viewMsgToast(obj);
  })
  .fail(function (jqXHR, textStatus, error) {
    console.log("Post error: " + error);
  });
}

function mapInit(){
  let x = parseFloat($("[name=gpdpx]").val()).toFixed(4)
  let y = parseFloat($("[name=gpdpy]").val()).toFixed(4)
  let epsg = parseInt($("[name=epsg]").val())
  console.log([x,y,epsg]);
}

function viewMsgToast(obj){
  $(".toast").removeClass('[class^="bg-"]').addClass(obj.classe);
  $("#headerTxt").html(obj.titolo);
  $(".toast>.toast-body>.toast-body-msg").html(obj.msg);
  $(".toast").toast({delay:3000});
  $(".toast").show();
  $(".toast").toast('show');
  $("[name='continua']").hide();
  $("[name='viewRec']").text('ok').on('click', function(){location.reload();});
}
