let sess = $("[name=sessId]").val() ? [$("[name=sessId]").val(),$("[name=sessUsr]").val()] : [];
let dati = {};
let filter = {};
$(document).ready(function() {
  $("#filtriTitle").hide();
  if (sess.length > 0 && !localStorage.getItem('operatore')) {
    dati.operatore = parseInt(sess[0]);
    filter.operatore = sess[1];
  }
  if(localStorage.getItem('operatore')){
    i = JSON.parse(localStorage.getItem('operatore'));
    dati.operatore = i[0]
    filter.operatore = i[1]
    viewFilter()
  }
  $("#tableWrap").hide()

  $("[name=tipo]").on('change', function(){
    let v = $(this).val();
    $(".filtraTsk > option").hide()
    $(".filtraTsk > option").filter(function(){return $(this).data('tsk') == 0 || $(this).data('tsk') == v }).show()
  })
  $("[name=cerca]").on('click', cerca)
  $("[name=clear]").on('click', function(){ location.reload() })
  buildTable()
});

function viewFilter(){
  $("#filtri").html('');
  $("#filtriTitle").show();
  Object.keys(filter).forEach(f => {
    let item = $("<button/>", {class:'btn btn-secondary', type:'button', title:'cancella filtro '+f})
    .attr({"data-toggle":'tooltip', "data-placement":'top'})
    .appendTo('#filtri')
    .on('click', function(){
      $(this).tooltip('hide')
      $(this).remove()
      delete dati[f];
      delete filter[f];
      localStorage.removeItem(f);
      if(Object.keys(dati).length == 0){
        $("#filtriTitle").hide();
        $("[name=clear]").addClass('invisible');
        if (sess.length > 0 && !localStorage.getItem('operatore')) {
          dati.operatore = parseInt(sess[0]);
          filter.operatore = sess[1];
        }
      }
      buildTable()
    })
    $("<span/>",{class:'btn-text'}).text(filter[f]).appendTo(item)
    $("<i/>",{class:'fas fa-times ml-2'}).appendTo(item)
  });
}

function resetFilter(){
  $(".filtro").each(function(){
    if ($(this).is("input:text") || $(this).is("input[type=number]")){ $(this).val(''); }
    if ($(this).is("select")){
      $(this).prop('selectedIndex',0);
    }
    if ($(this).is(":radio:checked") || $(this).is(":checkbox:checked")){
      $(this).prop('checked', false);
      $(".btn-group > label").removeClass('active')
    }
  })
  $(".filtraTsk > option").show()
}

function cerca(){
  $("#filtri").html('');
  $(".filtro").each(function(i,v){
    if ($(this).is("input:text") || $(this).is("input[type=number]") || $(this).is("select") || $(this).is(":radio:checked") || $(this).is(":checkbox:checked")) {
      if (!$(this).is(':disabled')) {
        if ($(this).val()) {
          let filtro = $(this).attr('name');
          dati[filtro]=$(this).val();
          if ($(this).is("input:text") || $(this).is("input[type=number]")){
            filter[filtro]= filtro+': '+$(this).val();
          }
          if ($(this).is("select")){
            filter[filtro]=$(this).find('option:selected').text();
          }
          if ($(this).is(":radio:checked") || $(this).is(":checkbox:checked")){
            filter[filtro]=$(this).next('span').text();
          }
        }
      }
    }
  })
  if(Object.keys(dati).length==0){
    $("[name=clear]").addClass('invisible');
    $("<div/>",{class:'alert alert-warning'}).text("Per effettuare una ricerca nell'archivio devi impostare almeno un filtro tra quelli disponibili").appendTo("#filtri");
    return false;
  }
  $("[name=clear]").removeClass('invisible');
  resetFilter()
  viewFilter()
  buildTable()
}

function buildTable(){
  if (Object.keys(dati).length == 0) {
    $("#tableWrap").hide();
    return false;
  }
  $('#dataTable').DataTable().clear().destroy();
  $.ajax({
    url: 'api/scheda.php',
    type: 'POST',
    dataType: 'json',
    data: { trigger: 'listaSchede', dati}
  })
  .done(function(data) {
    data.forEach(function(v,i){
      let linkIco = $("<i/>", {class:'fas fa-link', title:'visualizza scheda completa'}).attr("data-toggle", 'tooltip').attr("data-placement", 'left');
      let link = $("<a/>",{href:'schedaView.php?get='+v.scheda, html:linkIco});
      tr = $("<tr/>").appendTo('#dataTable');
      let piano,stato;
      switch (v.piano) {
        case -1: piano = 'Deposito'; break;
        case 0: piano = 'Piano terra'; break;
        case 1: piano = 'Primo piano'; break;
        case 2: piano = 'Secondo piano'; break;
        case 3: piano = 'Terzo piano'; break;
      }
      if (!v.chiusa) {stato = 'in lavorazione';}
      else if (v.chiusa && !v.verificata) {stato = 'da verificare';}
      else if (v.chiusa && v.verificata && !v.inviata){stato = 'da inviare'}
      else if (v.chiusa && v.verificata && v.inviata && !v.accettata){stato = 'in attesa di accettazione ICCD'}
      else if (v.chiusa && v.verificata && v.inviata && v.accettata){stato = 'iter completo, scheda chiuda'}

      $("<td/>",{text:v.nctn}).appendTo(tr);
      $("<td/>",{text:v.inventario}).appendTo(tr);
      $("<td/>",{text:v.tipo}).appendTo(tr);
      $("<td/>",{text:stato}).appendTo(tr);
      $("<td/>",{text:v.titolo}).appendTo(tr);
      $("<td/>",{text:v.ogtd}).appendTo(tr);
      $("<td/>",{text:v.materia}).appendTo(tr);
      $("<td/>",{text:v.tecnica}).appendTo(tr);
      $("<td/>",{text:v.dtzgi == v.dtzgf ? v.inizio : v.inizio+' / '+v.fine}).appendTo(tr);
      $("<td/>",{text:piano}).appendTo(tr);
      $("<td/>",{text:!v.nome_sala ? v.sala : v.nome_sala}).appendTo(tr);
      $("<td/>",{text:v.contenitore}).appendTo(tr);
      $("<td/>",{text:v.cassetta}).appendTo(tr);
      $("<td/>",{text:v.comune}).appendTo(tr);
      $("<td/>",{text:v.via}).appendTo(tr);
      $("<td/>",{text:v.operatore}).appendTo(tr);
      $("<td/>",{html:link, class:'text-center'}).appendTo(tr);
    })
    $('#dataTable').DataTable(dataTableOpt);
    $("#tableWrap").show()
  })
  .fail(function() {console.log("error");});
}
