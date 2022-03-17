$(document).ready(function() {
  buildTable();
});

function buildTable(){
  $('#dataTable').DataTable().clear().destroy();
  let dati = {};
  if (localStorage.getItem('operatore')) {
    dati.operatore = localStorage.getItem('operatore');
  }
  $.ajax({
    url: 'api/scheda.php',
    type: 'POST',
    dataType: 'json',
    data: { trigger: 'listaSchede', dati}
    // data: { trigger: 'listaSchede', dati:{tipo:1,stato:{field:'chiusa', value:'f'}}}
  })
  .done(function(data) {
    console.log(data);
    if (localStorage.getItem('operatore')) {
      let item = $("<button/>", {class:'btn btn-secondary mx-2', type:'button', title:'cancella filtro operatpre'})
      .attr({"data-toggle":'tooltip', "data-placement":'top'})
      .appendTo('#filtri')
      .on('click', function(){
        $(this).tooltip('hide')
        $(this).remove()
        localStorage.removeItem("operatore");
        buildTable()
      })
      $("<span/>",{class:'btn-text'}).text(data[0].operatore).appendTo(item)
      $("<i/>",{class:'fas fa-times ml-2'}).appendTo(item)
    }
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
      paging: true,
      lengthMenu: [20, 50, 75, 100, 200],
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
