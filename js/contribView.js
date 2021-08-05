const API = 'api/biblio.php';
const ID = $("[name=idContrib]").val();
$(document).ready(function() {
  $('[name=biblioDel]').on('click', function () {
    if (!confirm("Attenzione! Stai per elimnare la scheda bibliografica, procedere?")){
      return false;
    }

    $.ajax({
      url: API,
      type: 'POST',
      dataType: 'json',
      data: {trigger : 'deleteContrib', id:ID}
    })
    .done(function(data) {
      data.url='bibliografia.php';
      data.btn = [];
      data.btn.push("<a href='bibliografia.php' class='btn btn-light btn-sm'>ok</a>");
      toast(data);
    })
    .fail(function() {console.log(data); });
  });
});
