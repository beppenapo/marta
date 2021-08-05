$(document).ready(function() {
  // $(".tab").each(function(){ console.log($(this).data('table')+": "+$(this).attr('name')); })
  const listCategorie = {tab:'liste.ra_cls_l1', sel:'cls1'};
  const listProvince ={tab:'liste.province', sel:'prvp'};
  const listTcl ={tab:'liste.la_tcl', sel:'tcl'};
  const listDtm ={tab:'liste.dtm_motivazione_cronologia', sel:'dtmSel'};
  const listDtzs ={tab:'liste.dtzs_frazione_cronologia', sel:'dtzs'};
  const listStcc ={tab:'liste.conservazione', sel:'stcc'};
  const listCdgg ={tab:'liste.condizione_giuridica', sel:'cdgg'};
  const list = {listCategorie,listProvince,listTcl,listDtm,listDtzs,listStcc,listCdgg}
  var dtmVal = [];
  $("#ogtdAlert").hide();
  getList(list);
  $("[name=misr]").on('click', function(){ $('.misure').prop('disabled', (i, v) => !v); })
  $("#toggleInventarioTipText").click(function () {$(this).text(function(i, text){return text === "mostra guida" ? "nascondi guida" : "mostra guida";})});

  $.ajax({
    url: 'api/scheda.php',
    type: 'POST',
    dataType: 'json',
    data: {trigger:'vocabolari', tab:'liste.materiale'}
  })
  .done(function(data){
    $( "[name=materia]" ).autocomplete({
      minLength: 0,
      source: data,
      change: function(event,ui){
        if(!ui.item){
          $(this).val('');
          $( "[name=tecnica],[name=addTecnica]" ).prop('disabled', true);
          alert('devi selezionare una materia!');
        }
        return false;
      },
      select: function(event,ui){
        $( "[name=tecnica], [name=addTecnica]" ).prop('disabled', false);
        $(this).val(ui.item.label)
        mtcWrap(ui.item);
        return false;
      }
    }).focus(function() {
      $(this).autocomplete('search', $(this).val())
    });
  })
  .fail(function(data) { console.log(data); });

  $.ajax({
    url: 'api/scheda.php',
    type: 'POST',
    dataType: 'json',
    data: {trigger:'vocabolari', tab:'liste.ra_tecnica'}
  })
  .done(function(data){
    $( "[name=tecnica]" ).autocomplete({
      minLength: 0,
      source: data,
      change: function(event,ui){if(!ui.item){
        $(this).val('');
        $( "[name=addMtc]" ).prop('disabled', true);
        alert('devi selezionare un valore!');
      }},
      select: function(event,ui){
        $( "[name=tecnica]" ).val( ui.item.value );
        $( "[name=addMtc]" ).prop('disabled', false);
        return false;
      }
    }).focus(function() {
      $(this).autocomplete('search', $(this).val())
    });
  })
  .fail(function(data) { console.log(data); });


  $('body').on('click', '[name=addTecnica]', function(event) {
    $("[name=materia]").autocomplete('enable');
    var materiaVal = $("[name=materia]").val();
    materiaVal = materiaVal.replace(/\s+/g, '-');
    var tecnicaInput = $("[name=tecnica]");
    var tecnicaItem = $("#"+materiaVal+"Row").find('[name=tecnicaItem]');
    if (!tecnicaInput.val()) {
      alert("Devi selezionare almeno una tecnica e/o confermare la scelta cliccando sul tasto +");
      return false;
    }
    v = tecnicaInput.val();
    if (!tecnicaItem.val()) {
      tecnicaItem.val(v)
    }else {
      tecnicaItem.val(tecnicaItem.val()+','+ v);
    }
    tecnicaInput.val('');
  });

  $('body').on('click', '[name=addMtc]', function(event) {
    var materiaVal = $("[name=materia]").val();
    materiaVal = materiaVal.replace(/\s+/g, '-');
    var t = $("#"+materiaVal+"Row").find('[name=tecnicaItem]').val();
    if(t.length == 0){
      alert("Devi selezionare almeno una tecnica e/o confermare la scelta cliccando sul tasto +");
      return false;
    }
    $("[name=materia]").prop('disabled',false).val('');
    $( "[name=tecnica],[name=addTecnica]" ).prop('disabled', true);
  });


  $("body").on('change', '[name=cls1]', function(el) {
    const dati = {
      el:el,
      sel:$('[name=cls2]'),
      tab:'liste.ra_cls_l2',
      filter:{field:'l1',value:$(this).val()}
    }
    subList(dati);
  });
  $("body").on('change', '[name=prvp]', function(el) {
    const dati = {
      el:el,
      sel:$('[name=prvc]'),
      tab:'liste.comuni',
      filter:{field:'provincia',value:$(this).val()}
    }
    subList(dati);
  });
  $("body").on('click', '[name=dtmSel]', function() {
    let v = $(this).val();
    if (v) {
      let text = $(this).find("option:selected").text();
      var group = $("<div/>", {class:'input-group mb-3', id:'choice'+v}).appendTo('#dtmWrap');
      $("<input/>",{type:'text', class:'form-control form-control-sm', name:'dtmText'}).val(text).prop('disabled', true).appendTo(group);
      var addon = $("<div/>", {class:'input-group-append'}).appendTo(group);
      $("<button/>",{class:'btn btn-danger btn-sm', type:'button', name:'delDtmOpt'}).val(v).attr("data-text",text).html('<i class="fas fa-times"></i>').appendTo(addon).on('click', function(){
        reuseOption(v,text);
        group.remove();
        dtmVal = $.grep(dtmVal, function(value) { return value != v;});
        if(dtmVal.length==0){$("[name=dtmSel]").prop('required', true)}
      });
      $(this).find('option[value='+v+']').remove();
      $(this).prop('required', false);
      dtmVal.push(parseInt(v));
    }
  });
  $("body").on('change', '[name=cls2]', function() {
    let v = $(this).val();
    $.ajax({
      url: 'api/scheda.php',
      type: 'POST',
      dataType: 'json',
      data: {trigger:'vocabolari', tab:'liste.ra_ogtd', filter:{field:'classe', value:v}}
    })
    .done(autocomp)
    .fail(function(data) { console.log(data); });
  });
  $("[name=resetOgtd]").on('click', function(){$( "[name=ogtdLabel], [name=ogtd]" ).val('');});

  $("body").on('click', '[name=toggleSection]', function() {
    var fieldset = $(this).data('fieldset');
    $("#"+fieldset+" label").toggleClass('text-danger');
    $("#"+fieldset+" .tab").prop('disabled',(i,v)=>!v).prop('required',(i,v)=>!v);
  });

  $('[name=submit]').on('click', function (e) {
    isvalidate = $("#formScheda")[0].checkValidity()
    if (isvalidate) {
      var mtcVal = [];
      form = $("#formScheda");
      e.preventDefault();
      dati = {};
      tab = [];
      $("[data-table]").each(function(){tab.push($(this).data('table'));})
      tab = tab.filter((v, p) => tab.indexOf(v) == p);
      $.each(tab,function(i,v){ dati[v]={} })
      dati.scheda.inventario = $("[name=inventario]").val();
      if($("[name=suffix]").val()){dati.scheda.suffix = $("[name=suffix]").val();}
      dati.cd.tsk = parseInt($("[name=tsk]").val());
      dati.cd.lir = parseInt($("[name=lir]").val());
      dati.og.ogtd = parseInt($("[name=ogtd]").val());
      dati.lc.ldcs = $("[name=ldcs]").val();
      if ($("#toggleLA").is(':checked')) {
        dati.la.tcl = $("[name=tcl]").val();
        dati.la.prvc = $("[name=prvc]").val();
      }else {
        delete dati['la'];
      }
      if ($("#toggleRE").is(':checked')) {
        dati.re.scan = $("[name=scan]").val();
        dati.re.dsca = $("[name=dsca]").val();
        dati.re.dscd = $("[name=dscd]").val();
      }else {
        delete dati['re'];
      }

      dati.dtz.dtzg = $("[name=dtzg]").val();
      if($("[name=dtzs]").val()){dati.dtz.dtzs = $("[name=dtzs]").val();}

      if ($("#toggleDTS").is(':checked')) {
        dati.dts.dtsi = $("[name=dtsi]").val();
        dati.dts.dtsf = $("[name=dtsf]").val();
      }else {
        delete dati['dts'];
      }
      dtm = dtmVal.join(',');
      dati.dtm={dtm}
      $("#mtcWrap>div").each(function(){
        materia = $(this).find("[name=materiaItem]").val();
        materia = parseInt(materia);
        tecnica = $(this).find("[name=tecnicaItem]").val();
        mtcVal.push({materia,tecnica});
      });
      if (mtcVal.length == 0) {
        alert("Attenzione, devi selezionare almeno una materia e una tecnica!");
        return false;
      }else {
        dati.mtc=mtcVal;
      }
      if($("[name=misr]").is(':checked')){
        dati.mis['misr'] = $("[name=misr]").val();
      }else{
        count = 0;
        $(".misure").each(function(){ if($(this).val()){count ++} });
        if (count == 0) {
          alert('Attenzione! Se il reperto è misurabile devi inserire almeno una misura, altrimenti seleziona la checkbox "misure non rilevabili"');
          return false;
        }else {
          $(".misure").each(function(){
            if ($(this).val()) {
              field = $(this).attr('name');
              val = $(this).val();
              dati.mis[field] = val;
            }
          })
        }
      }
      dati.da.deso = $("[name=deso]").val();
      dati.co.stcc = $("[name=stcc]").val();
      dati.tu.cdgg = $("[name=cdgg]").val();
      if($("[name=adsp]").is(':checked')){
        dati.ad.adsp = $("[name=adsp]:checked").val();
      }else {
        alert('Attenzione, devi selezionare un profilo di accesso (campo ADSP)');
        return false;
      }
      if ($("[name=adsm]").is(":checked")) {
        dati.ad.adsm = $("[name=adsm]:checked").val();
      }else {
        alert('Attenzione, devi selezionare una motivazione di accesso ai dati (campo ADSM)');
        return false;
      }
      dati.cm.cmpd = $("[name=cmpd]").val();
      dati.cm.cmpn = $("[name=cmpn]").val();
      dati.cm.fur = $("[name=fur]").val();
      $.ajax({
        url: 'api/scheda.php',
        type: 'POST',
        dataType: 'json',
        data: {trigger : 'addScheda', dati}
      })
      .done(function(data) {
        console.log(data);
        if (data.res === true || data.msg == 'There is no active transaction') {
          $(".toast").addClass('bg-success');
          $(".toast-body").html('La scheda è stata correttamente inserita');
          $(".toast").toast({delay:3000});
          $(".toast").toast('show');
          $('.toast').on('hidden.bs.toast', function () {
            $(".toast").removeClass('bg-success');
            location.reload();
          })
        }else {
          $(".toast").removeClass('[class^="bg-"]').addClass('bg-danger');
          $("#headerTxt").html('Errore nella query');
          $(".toast>.toast-body").html(data.msg);
          $(".toast").toast({delay:3000});
          $(".toast").toast('show');
          $('.toast').on('hidden.bs.toast', function () {
            $(".toast").removeClass('bg-danger');
          })
        }
      })
      .fail(function() {console.log("error"); });
    }
  })
});

function mtcWrap(item){
  var label = item.label.replace(/\s+/g, '-');
  const wrap = $("#mtcWrap");
  if ($("#"+label+"Row").length > 0) {
    $("[name=materia]").val('');
    $("[name=tecnica]").val('').prop('disabled', true);
    $("[name=addTecnica], [name=addMtc]").prop('disabled', true);
    alert("Attenzione! La materia '"+item.label+"' è stata già definita, puoi cancellarla e ridefinirla o scegliere una nuova materia");
    return false
  }
  $("[name=materia]").autocomplete('disable');
  var row = $("<div/>",{class:'form-row mb-3', id:label+'Row'}).appendTo(wrap);
  var col1 = $("<div/>",{class:'col-md-5'}).appendTo(row);
  var col2 = $("<div/>",{class:'col-md-6'}).appendTo(row);
  var col3 = $("<div/>",{class:'col-md-1'}).appendTo(row);
  $("<input/>",{type:'text', class:'form-control form-control-sm', name:'materiaLabel'}).val(item.label).prop('disabled', true).appendTo(col1);
  $("<input/>",{type:'hidden', name:'materiaItem'}).val(item.id).appendTo(col1);
  $("<input/>",{type:'text', class:'form-control form-control-sm', name:'tecnicaItem'}).prop('disabled', true).appendTo(col2);
  $("<button/>",{class:'btn btn-danger btn-sm', type:'button', name:'delMateriaItem'}).html('<i class="fas fa-times"></i>').appendTo(col3).on('click', function(){
    row.remove();
    $( "[name=materia]" ).val('');
    $( "[name=tecnica]" ).val('').prop('disabled', true);
    $( "[name=addTecnica], [name=addMtc]" ).prop('disabled', true);
    $("[name=materia]").autocomplete('enable');
  });
}

function autocomp(data){
  ogtdVal=[];
  data.forEach(function(item,idx){ ogtdVal.push({value:item.id,label:item.value}) });
  $("[name=resetOgtd]").prop('disabled',false);
  $( "[name=ogtdLabel]" ).prop('disabled',false).autocomplete({
    minLength: 0,
    source: ogtdVal,
    change: function(event,ui){if(!ui.item){$("#ogtdAlert").fadeIn("fast");}},
    select: function(event,ui){
      $( "[name=ogtdLabel]" ).val( ui.item.label );
      $( "[name=ogtd]" ).val( ui.item.value );
      $("#ogtdAlert").fadeOut("fast");
      return false;
    }
  }).focus(function() {
    $(this).autocomplete('search', $(this).val())
  });
}

function reuseOption(v,text){
  $("<option/>").val(v).text(text).appendTo('[name=dtmSel]');
  var options = $("[name=dtmSel] option");
  options.detach().sort(function(a,b) {
    var at = $(a).text();
    var bt = $(b).text();
    return (at > bt)?1:((at < bt)?-1:0);
  });
  options.appendTo("[name=dtmSel]");
}

function subList(dati){
  dati.sel.html('').prop('disabled',true);
  $("<option/>",{value:'', text:'--seleziona valore--'}).prop('disabled',true).prop('selected',true).appendTo(dati.sel);
  $.ajax({url: 'api/scheda.php', type: 'POST', dataType: 'json', data: {trigger:'vocabolari', tab:dati.tab, filter:dati.filter}})
  .done(function(data) {
    if (data.length > 0) {
      data.forEach(function(v,i){
        switch (dati.sel.attr('name')) {
          case 'prvc':
            $("<option/>",{value:v.codice, text:v.value}).appendTo(dati.sel);
          break;
          default:
            $("<option/>",{value:v.id, text:v.value}).appendTo(dati.sel);
        }
      });
      dati.sel.prop('disabled',false);
    }
  })
  .fail(function(data) { console.log(data); });
}
