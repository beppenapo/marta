const API = 'api/usr.php';
$('[name=submit]').on('click', function (e) {
  form = $("#addReportForm");
  isvalidate = form[0].checkValidity();
  if (isvalidate) {
    e.preventDefault();
    dati = {};
    dati.data = $("[name=data]").val();
    dati.utente = $("[name=utente]").val();
    dati.report = $("[name=report]").val();
    $.ajax({
      url: API,
      type: 'POST',
      dataType: 'json',
      data: {trigger : 'addReport', dati}
    })
    .done(function(data) {
      data.url='reportView.php?get='+data.id;
      data.btn = [];
      data.btn.push("<button type='button' class='btn btn-light btn-sm' name='continua'>continua inserimento</button>");
      data.btn.push("<a href='"+data.url+"' class='btn btn-light btn-sm'>visualizza report</a>");
      data.btn.push("<a href='report.php' class='btn btn-light btn-sm'>termina inserimento</a>");
      toast(data);
    })
    .fail(function(data){console.log(data);});
  }
});
