const API = 'api/usr.php';
const ID = $("[name=idReport]").val();
$(document).ready(function() {
  $('[name=reportDel]').on('click', function () {
    if (!confirm("Attenzione! Stai per eliminare il report, procedere?")){
      return false;
    }

    $.ajax({
      url: API,
      type: 'POST',
      dataType: 'json',
      data: {trigger : 'deleteReport', id:ID}
    })
    .done(function(data) {
      data.url='report.php';
      data.btn = [];
      data.btn.push("<a href='report.php' class='btn btn-light btn-sm'>ok</a>");
      toast(data);
    })
    .fail(function() {console.log(data); });
  });
});
