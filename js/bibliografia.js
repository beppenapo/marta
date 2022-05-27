$(document).ready(function() {
  buildTable();
});

function buildTable(){
  $.ajax({ url: 'api/biblio.php', type: 'POST', dataType: 'json', data: {trigger: 'elencoBiblio'} })
  .done(function(data) {
    data.forEach(function(v,i){
      let linkIco = $("<i/>", {class:'fas fa-link', title:'visualizza scheda completa'}).attr("data-toggle", 'tooltip');
      let link = $("<a/>",{href:v.link+v.id, html:linkIco});
      tr = $("<tr/>").appendTo('#dataTable');
      $("<td/>",{text:v.id}).appendTo(tr);
      $("<td/>",{text:v.tipo}).appendTo(tr);
      $("<td/>",{text:v.autore}).appendTo(tr);
      $("<td/>",{text:v.titolo}).appendTo(tr);
      $("<td/>",{text:v.schede, class:'text-center'}).appendTo(tr);
      $("<td/>",{html:link, class:'text-center'}).appendTo(tr);
    })
    $('#dataTable').DataTable(dataTableOpt);
  })
  .fail(function() {console.log("error");});
}
