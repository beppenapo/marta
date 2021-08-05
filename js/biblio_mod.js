const API = 'api/biblio.php';
const ID = $("[name=idScheda]").val()
let y = getData();
$("#anno").prop("max",y['y']);
$('[name=submit]').on('click', function (e) {
  form = $("#modBiblioForm");
  isvalidate = form[0].checkValidity();
  if (isvalidate) {
    e.preventDefault();
    dati = {};
    dati.id = ID;
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
      data: {trigger : 'editScheda', dati}
    })
    .done(function(data) {
      console.log(data);
      data.url='biblioView.php?get='+ID;
      data.btn = [];
      data.btn.push("<a href='"+data.url+"' class='btn btn-light btn-sm'>visualizza scheda</a>");
      data.btn.push("<a href='bibliografia.php' class='btn btn-light btn-sm'>vai all'archivio</a>");
      toast(data);
    })
    .fail(function(){console.log(data);});
  }
});
