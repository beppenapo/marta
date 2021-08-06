const API = 'api/usr.php';
const ID = $("[name=id]").val();
$('[name=submit]').on('click', function (e) {
  form = $("#modReportForm");
  isvalidate = form[0].checkValidity();
  if (isvalidate) {
    e.preventDefault();
    dati = {};
    dati.id = ID;
    dati.data = $("[name=data]").val();
    dati.report = $("[name=report]").val();
    $.ajax({
      url: API,
      type: 'POST',
      dataType: 'json',
      data: {trigger : 'editReport', dati}
    })
    .done(function(data) {
      data.url='reportView.php?get='+ID;
      data.btn = [];
      data.btn.push("<a href='"+data.url+"' class='btn btn-light btn-sm'>visualizza report</a>");
      data.btn.push("<a href='report.php' class='btn btn-light btn-sm'>lista report</a>");
      toast(data);
    })
    .fail(function(data){console.log(data);});
  }
});
