$(document).ready(function() {
  buildTable();
});

function buildTable(){
  $.ajax({ url: 'api/biblio.php', type: 'POST', dataType: 'json', data: {trigger: 'elencoBiblio'} })
  .done(function(data) {
    data.forEach(function(v,i){
      console.log(v);
      let linkIco = $("<i/>", {class:'fas fa-link', title:'visualizza scheda completa'}).attr("data-toggle", 'tooltip');
      let link = $("<a/>",{href:'schedaBiblio.php?get='+v.id, html:linkIco});
      tr = $("<tr/>").appendTo('#dataTable');
      $("<td/>",{text:v.id}).appendTo(tr);
      $("<td/>",{text:v.tipo}).appendTo(tr);
      $("<td/>",{text:v.autore}).appendTo(tr);
      $("<td/>",{text:v.titolo}).appendTo(tr);
      $("<td/>",{text:v.schede, class:'text-center'}).appendTo(tr);
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
