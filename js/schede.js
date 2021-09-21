$(document).ready(function() {
  buildTable();
});

function buildTable(){
  $.ajax({
    url: 'api/scheda.php',
    type: 'POST',
    dataType: 'json',
    data: { trigger: 'listaSchede'}
    // data: { trigger: 'listaSchede', dati:{tipo:1,stato:{field:'chiusa', value:'f'}}}
  })
  .done(function(data) {
    console.log(data);
    data.forEach(function(v,i){
      let linkIco = $("<i/>", {class:'fas fa-link', title:'visualizza scheda completa'}).attr("data-toggle", 'tooltip').attr("data-placement", 'left');
      let link = $("<a/>",{href:'schedaView.php?get='+v.id, html:linkIco});
      tr = $("<tr/>").appendTo('#dataTable');
      v.materia = v.materia.replace(/[{}"]/g, "");
      $("<td/>",{text:v.nctn}).appendTo(tr);
      $("<td/>",{text:v.tipo}).appendTo(tr);
      $("<td/>",{text:v.titolo}).appendTo(tr);
      $("<td/>",{text:v.ogtd}).appendTo(tr);
      $("<td/>",{text:v.materia}).appendTo(tr);
      $("<td/>",{text:v.dtzgi == v.dtzgf ? v.dtzgi : v.dtzgi+' / '+v.dtzgf}).appendTo(tr);
      $("<td/>",{text:v.piano}).appendTo(tr);
      $("<td/>",{text:!v.nome_sala ? v.sala : v.nome_sala}).appendTo(tr);
      $("<td/>",{html:link, class:'text-center'}).appendTo(tr);
    })
    $('#dataTable').DataTable({
      order: [],
      columnDefs: [{targets  : 'no-sort', orderable: false }],
      destroy:true,
      retrieve:true,
      responsive: true,
      html:true,
      language: { url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Italian.json' }
    });
  })
  .fail(function() {console.log("error");});
}
