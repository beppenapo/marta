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
    $.ajax({
      url: API,
      type: 'POST',
      dataType: 'json',
      data: {trigger : 'addBiblio', dati}
    })
    .done(function(data) {
      data.url='biblioView.php?get='+data.id;
      data.btn = [];
      data.btn.push("<button type='button' class='btn btn-light btn-sm' name='continua'>continua inserimento</button>");
      data.btn.push("<a href='"+data.url+"' class='btn btn-light btn-sm'>visualizza scheda</a>");
      data.btn.push("<a href='bibliografia.php' class='btn btn-light btn-sm'>termina inserimento</a>");
      toast(data);
    })
    .fail(function(){console.log("error");});
  }
});
