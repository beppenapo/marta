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
    console.log(data.length);
    let opts = [];
    let firstOpt = "<option value='' selected disabled>-- definizione --</option>";
    let noOpt = "<option value=''>-- nessuna specifica presente --</option>";
    if (data.length == 0) {
      opts.push(noOpt);
    } else {
      opts.push(firstOpt);
      data.forEach((item, i) => {
        let opt = "<option value='"+item.id+"'>"+item.value+"</option>";
        opts.push(opt);
      });
    }
    sel.html(opts.join(''));
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
