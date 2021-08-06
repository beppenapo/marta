$(document).ready(function() {
  buildTable();
});

function buildTable(){
  $.ajax({ url: 'api/usr.php', type: 'POST', dataType: 'json', data: {trigger: 'listaReport'} })
  .done(function(data) {
    data.forEach(function(v,i){
      let linkIco = $("<i/>", {class:'fas fa-link', title:'visualizza report completo'}).attr("data-toggle", 'tooltip');
      let link = $("<a/>",{href:'reportView.php?get='+v.id, html:linkIco});
      let report = v.report.length < 1000 ? nl2br(v.report) : nl2br(v.report).replace(/^(.{1000}[^\s]*).*/, "$1") + " <br>...";
      let output = "<div class='border-dark border-bottom my-3'><h6>Report creato il <strong>"+v.data+"</strong>, da <strong>"+v.utente+"</strong></h6></div>";
      output += "<div>"+report+"</div>";
      output += "<div><a href='reportView.php?get="+v.id+"' class=''>visualizza report completo</a></div>";
      tr = $("<tr/>").appendTo('#dataTable');
      td = $("<td/>",{html:output}).appendTo(tr);
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
