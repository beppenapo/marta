const API = 'api/biblio.php';
const ID = $("[name=idScheda]").val();
$(document).ready(function() {
  $("#scheda-card .list-group-item>span:first-child").css("width","120px");
  $("#scheda-card .list-group-item>span:last-child").css("width","calc(100% - 125px)");
});

$('[name=biblioDel]').on('click', function () {
  if (!confirm("Attenzione! Stai per elimnare la scheda bibliografica, procedere?")){
    return false;
  }
  $.ajax({
    url: API,
    type: 'POST',
    dataType: 'json',
    data: {trigger : 'deleteScheda', id:ID}
  })
  .done(function(data) {
    data.url='bibliografia.php';
    data.btn = [];
    data.btn.push("<a href='bibliografia.php' class='btn btn-light btn-sm'>ok</a>");
    toast(data);
  })
  .fail(function() {console.log(data); });

});
