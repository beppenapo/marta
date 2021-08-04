const API = 'api/biblio.php';
let y = getData();
$("#anno").prop("max",y['y']);

$('[name=submit]').on('click', function (e) {
  form = $("#addBiblioForm");
  isvalidate = form[0].checkValidity();
  if (isvalidate) {
    e.preventDefault();
    dati = {};
    dati.tipo = $("[name=tipo]").val();
    dati.titolo = $("[name=titolo]").val();
    dati.autore = $("[name=autore]").val();
    if ($("[name=altri_autori]").val()) {dati.altri_autori = $("[name=altri_autori]").val();}
    if ($("[name=curatore]").val()) {dati.curatore = $("[name=curatore]").val();}
    if ($("[name=editore]").val()) {dati.editore = $("[name=editore]").val();}
    if ($("[name=anno]").val()) {dati.anno = $("[name=anno]").val();}
    if ($("[name=luogo]").val()) {dati.luogo = $("[name=luogo]").val();}
    if ($("[name=isbn]").val()) {dati.isbn = $("[name=isbn]").val();}
    if ($("[name=url]").val()) {dati.url = $("[name=url]").val();}
    if ($("[name=scheda]").val()) {
      dati.bs = {}
      dati.bs.scheda = $("[name=scheda]").val();
      dati.bs.livello = $("[name=livello]").val();
      if($("[name=pagine]").val()){dati.bs.pagine = $("[name=pagine]").val()}
      if($("[name=figure]").val()){dati.bs.figure = $("[name=figure]").val()}
    }
    $.ajax({
      url: API,
      type: 'POST',
      dataType: 'json',
      data: {trigger : 'addBiblio', dati}
    })
    .done(function(data) {
      data.url='biblioView.php?get='+data.id;
      createToast(data);
    })
    .fail(function(){console.log("error");});
  }
});
