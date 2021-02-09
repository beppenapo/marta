$(document)
.ajaxStart(function(){console.log('...loading');})
.ajaxStop(function(){$("#loadingDiv").remove();});

$(document).ready(function() {
  buildTable();
  $('body').on('click', '[name=updateUser]', function() { $.redirectPost('usrAdd.php',{id:$(this).val()}); });
});

function buildTable(){
  $.ajax({ url: 'api/usr.php', type: 'POST', dataType: 'json', data: {trigger: 'getUser'} })
  .done(function(data) {
    data.forEach(function(v,i){
      console.log(v);
      act = v.attivo == true ? "<i class='fas fa-check-circle text-success' data-toggle='tooltip' title='attivo'></i>" : "<i class='fas fa-times-circle text-danger' data-toggle='tooltip' title='non attivo'></i>";
      classe = '<i class="fas '+v.ico+'" data-toggle="tooltip" title="'+v.classe+'"></i>';
      btnUpdate = $("<button/>", {class:'btn btn-light btn-sm', type:'button', name:'updateUser', value:v.id}).html("<i class='fas fa-edit' data-toggle='tooltip' title='modifica utente'></i>");
      tr = $("<tr/>").appendTo('#dataTable');
      $("<td/>",{text:v.cognome+" "+v.nome}).appendTo(tr);
      $("<td/>",{text:v.email}).appendTo(tr);
      $("<td/>",{text:v.cellulare}).appendTo(tr);
      $("<td/>",{html:classe, class:'text-center'}).appendTo(tr);
      $("<td/>",{html:act, class:'text-center'}).appendTo(tr);
      $("<td/>",{html:btnUpdate, class:'text-center'}).appendTo(tr);
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
