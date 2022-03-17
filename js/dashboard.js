$(document)
.ajaxStart(function(){console.log('...loading');})
.ajaxStop(function(){$("#loadingDiv").remove();});

const userClass = parseInt($("[name=classe]").val());
const utente = parseInt($("[name=utente]").val());

stat();
initComunicazioni();
buildSchedeTable();
buildUserTable();

$("[name=addComunicazioneBtn]").on('click', function(){
  $("[name=testo]").val('');
  $("[name=idComunicazione]").val('');
  $("#addComunicazioneDiv").modal('show');
});
$("[name=saveComunicazione]").on('click', function(e){
  isvalidate = $("[name=addComunicazioneForm]")[0].checkValidity()
  if (isvalidate) {
    e.preventDefault();
    dati = !$("[name=idComunicazione]").val() ? {trigger : 'addComunicazione', testo:$("[name=testo]").val()} : {trigger : 'editComunicazione', id:$("[name=idComunicazione]").val(), testo:$("[name=testo]").val()}
    $.ajax({ url: 'api/dashboard.php', type: 'POST', dataType: 'json', data: dati })
    .done(function(data) {
      $('#addComunicazioneDiv').modal('hide');
      $(".toastAddComunicazione > .toast-body").html('<p>Comunicazione inserita correttamente!</p>');
      $(".toastAddComunicazione").toast('show');
      $('.toastAddComunicazione').on('hidden.bs.toast', function () {initComunicazioni();})
    })
    .fail(function() {console.log("error"); });
  }
})
//CBAEURSKUZBYXE
$("body").on('click', '[name=delNotesBtn]', function() {
  v=$(this).val();
  $.ajax({url: 'api/dashboard.php', type: 'POST', dataType: 'json', data: {trigger: 'delComunicazione', id:v}})
  .done(function(data) {
    $(".toastDelComunicazione").toast('hide');
    $(".toastAddComunicazione > .toast-body").html('<p>Comunicazione eliminata!</p>');
    $('.toastDelComunicazione').on('hidden.bs.toast', function () {
      $(".toastAddComunicazione").toast('show');
      $('.toastAddComunicazione').on('hidden.bs.toast', function () {initComunicazioni();})
    })
  })
  .fail(function(data) { console.log(data); });
});
$('body').on('click', '[name=updateUser]', function() { $.redirectPost('usrAdd.php',{id:$(this).val()}); });
$('body').on('click', 'a.schedatore', function(e) {
  e.preventDefault();
  localStorage.setItem('operatore',$(this).data('id'));
  $.redirectPost('schede.php');
});
function initComunicazioni(){
  $.ajax({ url: 'api/dashboard.php', type: 'POST', dataType: 'json', data: {trigger : 'comunicazioni'} })
  .done(function(data) {
    let sex = parseInt(localStorage.getItem('sex'));
    wrap = $("#comunicazioni>.list-group");
    wrap.html('');
    data.forEach(function(item,index){
      div = $("<div/>",{class:'list-group-item flex-column align-items-start'}).appendTo(wrap);
      $("<p/>", {class:'mb-1'}).html(nl2br(item.testo)).appendTo(div);
      footer = $("<div/>",{class:'d-flex w-100 justify-content-between'}).appendTo(div);
      $("<small/>").html(item.utente).appendTo(footer);
      $("<small/>").html(item.data).appendTo(footer);
      if (sex && sex === item.session) {
        btnWrap = $("<div/>",{class:'d-flex w-100 justify-content-start mt-3'}).appendTo(div);
        $("<button/>",{type:'button', class:'btn btn-sm btn-light text-dark mr-2 p-1', name:'editComunicazione'})
          .html('<small>modifica</small>')
          .appendTo(btnWrap)
          .on('click', function(){
            $("[name=testo]").val(item.testo);
            $("[name=idComunicazione]").val(item.id);
            $("#addComunicazioneDiv").modal('show');
          });
        $("<button/>",{type:'button', class:'btn btn-sm btn-light text-dark', name:'delComunicazione'})
          .html('<small>elimina</small>')
          .appendTo(btnWrap)
          .on('click', function(){
            $("[name=delNotesBtn]").val(item.id);
            $(".toastDelComunicazione").toast('show');
          });
      }
    });
  })
  .fail(function() {console.log("error"); });
}

function buildUserTable(){
  $.ajax({ url: 'api/usr.php', type: 'POST', dataType: 'json', data: {trigger: 'getUser'} })
  .done(function(data) {
    data.forEach(function(v,i){
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
    initDataTab('#dataTable')
  })
  .fail(function() {console.log("error");});
}

function buildSchedeTable(){
  let dati = {trigger:'statoSchede'}
  if (userClass == 3) {dati.cmpn = utente;}
  let opt={ url: 'api/dashboard.php', type: 'POST', dataType: 'json', data: dati }
  $.ajax(opt)
  .done(function(data) {
    console.log(data);
    data.forEach(function(v,i){
      let vero = "<i class='fas fa-check-circle text-success'></i>";
      let falso = "<i class='fas fa-times-circle text-danger'></i>";
      let link = $("<a/>", {href:'schedaView.php?get='+v.scheda}).html("<i class='fas fa-arrow-right'></i>");
      tr = $("<tr/>").appendTo('#dataTableScheda');
      $("<td/>",{text:v.nctn}).appendTo(tr);
      $("<td/>",{text:v.titolo}).appendTo(tr);
      $("<td/>",{class:'text-center'}).html(v.chiusa == true ? vero : falso).appendTo(tr);
      $("<td/>",{class:'text-center'}).html(v.verificata == true ? vero : falso).appendTo(tr);
      $("<td/>",{class:'text-center'}).html(v.inviata == true ? vero : falso).appendTo(tr);
      $("<td/>",{class:'text-center'}).html(v.accettata == true ? vero : falso).appendTo(tr);
      $("<td/>",{text:v.cmpd}).appendTo(tr);
      $("<td/>",{class:'text-center',html:link}).appendTo(tr);
    })
    initDataTab('#dataTableScheda')
  })
  .fail(function(xhr, ajaxOptions, thrownError) {
    console.log([xhr, ajaxOptions, thrownError]);
  });
}

function initDataTab(tab){
  let tableOpt = {
    order: [],
    columnDefs: [{targets  : 'no-sort', orderable: false }],
    destroy:true,
    retrieve:true,
    responsive: true,
    html:true,
    language: { url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Italian.json' }
  };
  $(tab).DataTable(tableOpt);
}
