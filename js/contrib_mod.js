const API = 'api/biblio.php';
const ID = $("[name=contributo]").val();
$('[name=submit]').on('click', function (e) {
  form = $("#addBiblioForm");
  isvalidate = form[0].checkValidity();
  if (isvalidate) {
    e.preventDefault();
    dati = {};
    dati.id = ID;
    dati.raccolta = $("[name=raccolta]").val();
    dati.titolo = $("[name=titolo]").val();
    dati.autore = $("[name=autore]").val();
    if ($("[name=altri_autori]").val()) {dati.altri_autori = $("[name=altri_autori]").val();}
    if ($("[name=pag]").val()) {dati.pag = $("[name=pag]").val();}
    $.ajax({
      url: API,
      type: 'POST',
      dataType: 'json',
      data: {trigger : 'editContributo', dati}
    })
    .done(function(data) {
      data.url='contribView.php?get='+ID;
      data.btn = [];
      data.btn.push("<a href='"+data.url+"' class='btn btn-light btn-sm'>visualizza scheda</a>");
      data.btn.push("<a href='bibliografia.php' class='btn btn-light btn-sm'>visualizza archivio</a>");
      toast(data);
    })
    .fail(function(data){console.log(data);});
  }
});
