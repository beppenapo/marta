const API = 'api/scheda.php';
function setCf4(){
  var cassetti = [];
  for(let cassaforte4 of "ABCDEFGHIJKLMNOPQRSTU" ){cassetti.push(cassaforte4);}
  return cassetti;
}

function setCassetti(n){
  var cassetti = [];
  for (var i = 1; i < n+1; i++) { cassetti.push(i) }
  return cassetti;
}

function getSale(piano, value = null){
  postData("scheda.php",{trigger:'getSale', piano:piano}, function(data){
    let options = [];
  let selvoid = ""; if (value == null) { selvoid = " selected"; }
    options.push("<option disabled"+selvoid+">-- sala --</option>")
    $.each(data, function(index, el) {
    if (el.id == value) { var selected = " selected"; }else{ var selected = ""; }
      options.push("<option value='"+el.id+"'"+selected+">"+el.sala+"</option>");
    });
    $("[name=sala]").html(options.join());
  })
}

// NOTE: vetrina o scaffale
function getContenitore(contenitore, sala, label,piano, value = null){
  postData("scheda.php",{trigger:'getContenitore', contenitore:contenitore, sala:sala}, function(data){
    let options = [];
    let c = contenitore == 'vetrine' ? 'vetrina' : 'scaffale';
  let selvoid = ""; if (value == null) { selvoid = " selected"; }
    options.push("<option disabled"+selvoid+">-- "+c+" --</option>")
    if(data.length == 0){
      $("#lcContenitoreDiv").hide();
      $("#noVetrine").fadeIn('fast');
      return;
    }
    $.each(data, function(index, el) {
      note = !el.note ? '' : el.note;
    if (el.c == value) { var selected = " selected"; }else{ var selected = ""; }
      options.push("<option value='"+el.c+"'"+selected+">"+el.c+" "+note+"</option>");
    });
    $("[name=contenitore]").html(options.join());
    $("#contenitoreLabel").text(label);
    $("#noVetrine").hide();
    $("#lcContenitoreDiv").fadeIn('fast');
  })
}

function getColonna(sala, scaffale, value = null){
  postData("scheda.php",{trigger:'getColonna', sala:sala, scaffale:scaffale}, function(data){
    let options = [];
  let selvoid = ""; if (value == null) { selvoid = " selected"; }
    options.push("<option disabled"+selvoid+">-- colonna --</option>")
    $.each(data, function(index, el) {
    if (el.val == value) { var selected = " selected"; }else{ var selected = ""; }
      options.push("<option value='"+el.val+"'"+selected+">"+el.colonna+"</option>");
    });
    $("#lcColonnaDiv").fadeIn('fast');
    $("[name=colonna]").html(options.join());
  })
}

// NOTE: colonna, cassetto plateau, la funzione viene chiamata solo per le sale del deposito
function getRipiano(s,c){
  if(c==40){ t = setCassetti(10); return false;}
  t = setCf4()
}

function delbiblioref(id_biblio){
  let trigger = "delbiblioref";
  $.ajax({
  url: API,
  type: 'POST',
  dataType: 'json',
  data: {trigger : trigger, id_scheda:id_scheda, id_biblio:id_biblio}
  })
  .done(function(data) {
  if (data.res === true || data.msg == 'There is no active transaction') {
    $(".toast").addClass('bg-success');
    $(".toast-body").html('La scheda è stata correttamente eliminata');
    $(".toast").toast({delay:3000});
    $(".toast").toast('show');
    $('.toast').on('hidden.bs.toast', function () {
    $(".toast").removeClass('bg-success');
    location.reload();
    })
    window.setTimeout(function(){ location.reload(); },3000);
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

$(document).ready(function() {
  const listCategorie = {tab:'liste.ra_cls_l1', sel:'cls1'};
  const listProvince ={tab:'liste.province', sel:'prvp'};
  const listTcl ={tab:'liste.la_tcl', sel:'tcl'};
  const listDtm ={tab:'liste.dtm_motivazione_cronologia', sel:'dtmSel'};
  const listDtzs ={tab:'liste.dtzs_frazione_cronologia', sel:'dtzs'};
  const listStcc ={tab:'liste.conservazione', sel:'stcc'};
  const listCdgg ={tab:'liste.condizione_giuridica', sel:'cdgg'};
  if (tipoScheda != 2) {
  var lista = {listCategorie,listProvince,listTcl,listDtm,listDtzs,listStcc,listCdgg};
  }else{
    const listFtax ={tab:'liste.ftax', sel:'ftax'};
    const listFtap ={tab:'liste.ftap', sel:'ftap'};
  var lista = {listCategorie,listProvince,listTcl,listDtm,listDtzs,listStcc,listCdgg,listFtax,listFtap};
  }
  const list = lista;
  var dtmVal = [];
  $("#ogtdAlert").hide();
  getList(list);
  $("[name=misr]").on('click', function(){ $('.misure').prop('disabled', (i, v) => !v); })
  $("#toggleInventarioTipText").click(function () {$(this).text(function(i, text){return text === "mostra guida" ? "nascondi guida" : "mostra guida";})});

  $(".lcSel").hide();
  $("[name=piano]").on('change', function(){
    let piano = $(this).val();
    $("#lcSalaDiv").fadeIn('fast');
    $("#lcContenitoreDiv, #noVetrine,#lcColonnaDiv,#lcRipianoDiv").fadeOut('fast');
    getSale(piano)
  })

  $("[name=sala]").on('change', function(){
    $("#lcColonnaDiv,#lcRipianoDiv").fadeOut('fast');
    let piano = parseInt($("[name=piano]").val());
    let sala =  parseInt($(this).val());
    let label, contenitore;
    if (piano > 0) {
      label = 'Vetrina';
      contenitore = 'vetrine';
    }else {
      label = 'Scaffale';
      contenitore = 'scaffali';
    }
    getContenitore(contenitore,sala,label,piano)
  })

  $("[name=contenitore]").on('change', function(){
    $("#lcRipianoDiv").fadeOut('fast');
    let piano = parseInt($("[name=piano]").val());
    let sala = parseInt($("[name=sala]").val());
    let contenitore = parseInt($(this).val());
    if (piano === -1) { getColonna(sala, contenitore) }
  })

  $("[name=colonna]").on('change', function(){
    let scaffale = parseInt($("[name=contenitore]").val());
    let colonna = parseInt($(this).val());
    let options = [];

    switch (true) {
      case scaffale == 40:
        slot = setCassetti(104);
        label = 'Plateau';
      break;
      case scaffale == 41 && colonna == 1:
        slot = setCassetti(56);
        label = 'Cassetto';
      break;
      case scaffale == 41 && (colonna >= 2 && colonna <= 3):
        slot = setCassetti(4);
        label = 'Ripiano';
      break;
      case scaffale == 41 && colonna == 4:
        slot = setCf4();
        label = 'Cassetto';
      break;
      default:
        slot = setCassetti(10);
        label = 'Ripiano'
    }
    options.push("<option disabled selected>-- "+label+" --</option>")
    $.each(slot, function(index, val) {
      options.push("<option value='"+val+"'>"+val+"</option>");
    });
    $("#ripianoLabel").text(label);
    $("[name=ripiano]").html(options.join());
    $("#lcRipianoDiv").fadeIn('fast');
  })

  // NOTE: materia autocomplete
  $.ajax({
    url: API,
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
        $(this).val(ui.item.label);
        mtcWrap(ui.item);
        return false;
      }
    }).focus(function() {
      $(this).autocomplete('search', $(this).val())
    });
  })
  .fail(function(data) { console.log(data); });

  // NOTE: tecnica autocomplete
  $.ajax({
    url: API,
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

  ////////// mostra dati x EDIT
  if (action == 'edit') {
  $.ajax({
    url: API,
    type: 'POST',
    dataType: 'json',
    data: {trigger:'getScheda', tipo:tipoScheda, id:id_scheda}
  })
  .done(function(data) {
    //////////////// inizio passaggio dati
    let c = data.length;
    let id = data[0].id;
    let data_ins = data[0].data_ins;
    let titolo = data[0].titolo;
    $('#titolo').val(titolo);
    let cls1 = data[0].cls1;
    let cls2 = data[0].cls2;
    let ogtd = data[0].ogtd;
    let ogtd_value = data[0].ogtd_value;
    $('#ogtdLabel').val(ogtd_value).prop('disabled', false);
    $('[name=ogtd]').val(ogtd);
    $('#cls1').val(cls1);
    const daticls1 = {
      sel:$('[name=cls2]'),
      tab:'liste.ra_cls_l2',
      valor:cls2,
      filter:{field:'l1',value:cls1}
    }
    subList(daticls1);
    $.ajax({
      url: API,
      type: 'POST',
      dataType: 'json',
      data: {trigger:'vocabolari', tab:'liste.ra_ogtd', filter:{field:'classe', value:cls2}}
    })
    .done(autocomp)
    .fail(function(data) { console.log(data); });

    let piano = data[0].piano;
    $("[name=piano]").val(piano);
    let sala = data[0].stanza;
    let contenitore = data[0].contenitore;
    let colonna = data[0].colonna;
    let ripiano = data[0].ripiano;
    $("#lcSalaDiv").fadeIn();
    getSale(piano, sala);
    let label, contenitorelabel;
    if (piano > 0) {
      label = 'Vetrina';
      contenitorelabel = 'vetrine';
    }else {
      label = 'Scaffale';
      contenitorelabel = 'scaffali';
    }
    getContenitore(contenitorelabel,sala,label,piano,contenitore)
    if (piano === -1) { getColonna(sala, contenitore, colonna); $("#lcRipianoDiv").fadeIn(); }

      let options = [];
      switch (true) {
      case ripiano == 40:
        slot = setCassetti(104);
        label = 'Plateau';
      break;
      case ripiano == 41 && colonna == 1:
        slot = setCassetti(56);
        label = 'Cassetto';
      break;
      case ripiano == 41 && (colonna >= 2 && colonna <= 3):
        slot = setCassetti(4);
        label = 'Ripiano';
      break;
      case ripiano == 41 && colonna == 4:
        slot = setCf4();
        label = 'Cassetto';
      break;
      default:
        slot = setCassetti(10);
        label = 'Ripiano'
      }
      let selvoid = ""; if (ripiano == null) { selvoid = " selected"; }
      options.push("<option disabled"+selvoid+">-- "+label+" --</option>")
      $.each(slot, function(index, val) {
      if (val == ripiano) { var selected = " selected"; }else{ var selected = "" }
      options.push("<option value='"+val+"'"+selected+">"+val+"</option>");
      });
      $("#ripianoLabel").text(label);
      $("[name=ripiano]").html(options.join());

      let tcl = data[0].tcl;
      if (tcl) {
        $("#toggleLA").attr('checked', true);
        var fieldset = $('#toggleLA').data('fieldset');
        $("#"+fieldset+" label").toggleClass('text-danger');
        $("#"+fieldset+" .tab").prop('disabled',(i,v)=>!v).prop('required',(i,v)=>!v);
        $("[name=tcl]").val(tcl);
        let prvp = data[0].prvp;
        $("[name=prvp]").val(prvp);
        let prvc = data[0].prvc;
        const dati = {
        sel:$('[name=prvc]'),
        tab:'liste.comuni',
        valor:prvc,
        filter:{field:'provincia',value:prvp}
        }
        subList(dati);
      }

      let scan = data[0].scan;
      if (scan) {
        $("#toggleRE").attr('checked', true);
        var fieldset = $('#toggleRE').data('fieldset');
        $("#"+fieldset+" label").toggleClass('text-danger');
        $("#"+fieldset+" .tab").prop('disabled',(i,v)=>!v).prop('required',(i,v)=>!v);
        $("[name=scan]").val(scan);
        let dsca = data[0].dsca;
        $("[name=dsca]").val(dsca);
        let dscd = data[0].dscd;
        $("[name=dscd]").val(dscd);
      }

      let dtzg = data[0].dtzg;
      $("[name=dtzg]").val(dtzg);
      let dtzs = data[0].dtzs;
      $("[name=dtzs]").val(dtzs);
      let dtsi = data[0].dtsi;
      if (dtsi) {
        $("#toggleDTS").attr('checked', true);
        var fieldset = $('#toggleDTS').data('fieldset');
        $("#"+fieldset+" label").toggleClass('text-danger');
        $("#"+fieldset+" .tab").prop('disabled',(i,v)=>!v).prop('required',(i,v)=>!v);
        $("[name=dtsi]").val(dtsi);
        let dtsf = data[0].dtsf;
        $("[name=dtsf]").val(dtsf);
      }
      let dtm = data[0].dtm.replace(/{|}|"/g,'').split(',');
      let dtm_testo = data[0].dtm_testo.replace(/{|}|"/g,'').split(',');
      let cnt_dtm = dtm.length;
      for (let x = 0; x < cnt_dtm; x++) {
        let v = dtm[x];
        if (v) {
        let text = dtm_testo[x];
        var group = $("<div/>", {class:'input-group mb-3', id:'choice'+v}).appendTo('#dtmWrap');
        $("<input/>",{type:'text', class:'form-control form-control-sm', name:'dtmText'}).val(text).prop('disabled', true).appendTo(group);
        var addon = $("<div/>", {class:'input-group-append'}).appendTo(group);
        $("<button/>",{class:'btn btn-danger btn-sm', type:'button', name:'delDtmOpt'}).val(v).attr("data-text",text).html('<i class="fas fa-times"></i>').appendTo(addon).on('click', function(){
          reuseOption(v,text);
          $('#choice'+v).remove();
          dtmVal = $.grep(dtmVal, function(value) { return value != v;});
          if(dtmVal.length==0){$("[name=dtmSel]").prop('required', true)}
        });
        $('#dtmSel').find('option[value='+v+']').remove();
        $('#dtmSel').prop('required', false);
        dtmVal.push(parseInt(v));
        }
      }
      let materia_arr = data[0].materia.replace(/{|}|"/g,'').split(',');
      let materia_label_arr = data[0].materia_label.replace(/{|}|"/g,'').split(',');
      let cnt_materia = materia_arr.length;
      for (let x = 0; x < cnt_materia; x++) {
        let materia_label_ok = "";
        let materia = materia_arr[x].split('-');
        if (materia[0]) {
          let tecnica = materia[1];
          let cnt_materia_label = materia_label_arr.length;
          for (let y = 0; y < cnt_materia_label; y++) {
            let materia_label = materia_label_arr[y].split('-');
            if (materia_label[0] == materia[0]) { materia_label_ok = materia_label[1]; }
          }
          materai_obj = { id: materia, value: materia_label_ok, label: materia_label_ok };
          mtcWrap(materai_obj, tecnica);
        }
      }
      $("[name=materia]").autocomplete('enable');

      let misa = data[0].misa;
      $("[name=misa]").val(misa);
      let misl = data[0].misl;
      $("[name=misl]").val(misl);
      let misp = data[0].misp;
      $("[name=misp]").val(misp);
      let misd = data[0].misd;
      $("[name=misd]").val(misd);
      let misn = data[0].misn;
      $("[name=misn]").val(misn);
      let miss = data[0].miss;
      $("[name=miss]").val(miss);
      let misg = data[0].misg;
      $("[name=misg]").val(misg);
      let misv = data[0].misv;
      $("[name=misv]").val(misv);
      let misr = data[0].misr;
      if (misr == 'MNR') { $('#misr').attr('checked', true); $('.misure').prop('disabled', (i, v) => !v); }

      if (tipoScheda != 2) {
      let deso = data[0].deso;
      $("[name=deso]").val(deso);
    }else{
      let desa = data[0].desa;
      $("[name=desa]").val(desa);
      let desl = data[0].desl;
      $("[name=desl]").val(desl);
      let desn = data[0].desn;
      $("[name=desn]").val(desn);
      let desf = data[0].desf;
      $("[name=desf]").val(desf);
      let desm = data[0].desm;
      $("[name=desm]").val(desm);
      let desg = data[0].desg;
      $("[name=desg]").val(desg);
      let desr = data[0].desr;
      $("[name=desr]").val(desr);
      let dest = data[0].dest;
      $("[name=dest]").val(dest);
      let desv = data[0].desv;
      $("[name=desv]").val(desv);
      let desu = data[0].desu;
      $("[name=desu]").val(desu);
      let desd = data[0].desd;
      $("[name=desd]").val(desd);

      let invn = data[0].invn;
      $("[name=invn]").val(invn);
      let stis = data[0].stis;
      $("[name=stis]").val(stis);
      let stid = data[0].stid;
      $("[name=stid]").val(stid);

      let ftax = data[0].ftax;
      $("[name=ftax]").val(ftax);
      let ftap = data[0].ftap;
      $("[name=ftap]").val(ftap);
      let ftan = data[0].ftan;
      $("[name=ftan]").val(ftan);
    }

      let stcc = data[0].stcc;
      $("[name=stcc]").val(stcc);

      let cdgg = data[0].cdgg;
      $("[name=cdgg]").val(cdgg);

      let adsp = data[0].adsp;
      $("[name=adsp][value="+adsp+"]").prop("checked", true);
      let adsm = data[0].adsm;
      $("[name=adsm][value="+adsm+"]").prop("checked", true);

    let bibliodiv = "";
      let biblio_arr = data[0].biblio.replace(/{|}|"/g,'').split(',');
      let cnt_biblio = biblio_arr.length;
    if (cnt_biblio > 0 && data[0].biblio != '{NULL}') { bibliodiv += '<br /><h6 class="border-bottom text-danger font-weight-bold">Schede bibliografiche collegate:</h6>'; }
      for (let x = 0; x < cnt_biblio; x++) {
        let biblio = biblio_arr[x].split('||');
        if (biblio[0] != 'NULL') {
          let biblio_id = biblio[0];
          let biblio_nome = biblio[1];
      let classoodeven = ""; if (x%2 != 0) { classoodeven = " lista_even"; }
      bibliodiv += "&bull; <a href='scheda_biblio.php?act=view&id_biblio="+biblio_id+"&id_scheda="+id_scheda+"&tipo="+tipoScheda+"&act_scheda="+action+"' target='_self' title='Visualizza / Modifica scheda'>"+biblio_nome+"</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class='elimina' title='elimina riferimento bibliografico' onClick='delbiblioref("+biblio_id+");'>X</a><br />";
        }
      }
    $('#bliblio_list').html(bibliodiv);

      let cmpd = data[0].data_ins;
      $("[name=cmpd]").val(cmpd);
      let cmpnString = data[0].compilatore_nome;
      $("[name=cmpnString]").val(cmpnString);
      let cmpn = data[0].compilatore;
      $("[name=cmpn]").val(cmpn);
      let fur = data[0].fur;
      $("[name=fur]").val(fur);
    ////////////////////// fine passaggio dati
  })
  .fail(function(data) { console.log(data); });
  }
  ////////// fine mostra dati x EDIT

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
  $("body").on('change', '[name=dtmSel]', function() {
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
    console.log(dtmVal);
  });
  $("body").on('change', '[name=cls2]', function() {
    let v = $(this).val();
    $.ajax({
      url: API,
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

  $('[name=elimina_scheda]').on('click', function (e) {
      form = $("#formScheda");
      e.preventDefault();
    if (!confirm("Attenzione! Stai per elimnare la scheda: procedere?")){
      return false;
    }else{
      $(".tastischeda").hide();
      let trigger = "deleteScheda";
      $.ajax({
      url: API,
      type: 'POST',
      dataType: 'json',
      data: {trigger : trigger, id:id_scheda}
      })
      .done(function(data) {
      if (data.res === true || data.msg == 'There is no active transaction') {
        $(".toast").addClass('bg-success');
        $(".toast-body").html('La scheda è stata correttamente eliminata');
        $(".toast").toast({delay:3000});
        $(".toast").toast('show');
        $('.toast').on('hidden.bs.toast', function () {
        $(".toast").removeClass('bg-success');
        window.location.href = 'scheda_lista.php?tipo='+tipoScheda;
        })
        window.setTimeout(function(){ window.location.href = 'scheda_lista.php?tipo='+tipoScheda; },3000);
      }else {
        $(".toast").removeClass('[class^="bg-"]').addClass('bg-danger');
        $("#headerTxt").html('Errore nella query');
        $(".toast>.toast-body").html(data.msg);
        $(".toast").toast({delay:3000});
        $(".toast").toast('show');
        $('.toast').on('hidden.bs.toast', function () {
        $(".toast").removeClass('bg-danger');
        })
        $(".tastischeda").show();
      }
      })
      .fail(function() {console.log("error"); });
    }
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
      dati.scheda.titolo = $("[name=titolo]").val();
      if (dati.scheda.titolo == "") {
        alert("Attenzione, devi inserire un titolo per la scheda!");
        return false;
      }
      let trigger = "addScheda";
      let insomodiftxt = "inserita";
      if (action == "edit" && id_scheda > 0) {
        trigger = "editScheda";
        insomodiftxt = "modificata";
        dati.scheda.id = [];
        dati.scheda.id = id_scheda ;
      }else{
        dati.scheda.tipo = tipoScheda;
      }
      if($("[name=inventario]").val()){dati.scheda.inventario = $("[name=inventario]").val();}
      if($("[name=suffix]").val()){dati.scheda.suffix = $("[name=suffix]").val();}
      dati.cd.tsk = parseInt($("[name=tsk]").val());
      dati.cd.lir = parseInt($("[name=lir]").val());
      dati.og.ogtd = parseInt($("[name=ogtd]").val());
      dati.lc.pvc = 1;
      dati.lc.ldc = 1;
      dati.lc.ldcs = '';
      dati.lc.piano = $("[name=piano]").val();
      dati.lc.stanza = $("[name=sala]").val();
      dati.lc.contenitore = $("[name=contenitore]").val();
      dati.lc.colonna = $("[name=colonna]").val();
      if (dati.lc.colonna == null) { dati.lc.colonna = 0; }
      dati.lc.ripiano = $("[name=ripiano]").val();
      if (dati.lc.ripiano == null) { dati.lc.ripiano = 0; }
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
      dati.dtm=dtm;
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
      if (tipoScheda != 2) {
        dati.da.deso = $("[name=deso]").val();
    }else{
        dati.da.desa = $("[name=desa]").val();
        dati.da.desl = $("[name=desl]").val();
        dati.da.desn = $("[name=desn]").val();
        dati.da.desf = $("[name=desf]").val();
        dati.da.desm = $("[name=desm]").val();
        dati.da.desg = $("[name=desg]").val();
        dati.da.desr = $("[name=desr]").val();
        dati.da.dest = $("[name=dest]").val();
        dati.da.desv = $("[name=desv]").val();
        dati.da.desu = $("[name=desu]").val();
        dati.da.desd = $("[name=desd]").val();

        dati.ub.invn = $("[name=invn]").val();
        dati.ub.stis = $("[name=stis]").val();
        dati.ub.stid = $("[name=stid]").val();

        dati.nu_do.ftax = $("[name=ftax]").val();
        dati.nu_do.ftap = $("[name=ftap]").val();
        dati.nu_do.ftan = $("[name=ftan]").val();
    }
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
    $(".tastischeda").hide();
      $.ajax({
        url: API,
        type: 'POST',
        dataType: 'json',
        data: {trigger : trigger,  dati}
      })
      .done(function(data) {
        if (data.res === true || data.msg == 'There is no active transaction') {
          $(".toast").addClass('bg-success');
          $(".toast-body").html('La scheda è stata correttamente '+insomodiftxt);
          $(".toast").toast({delay:3000});
          $(".toast").toast('show');
          $('.toast').on('hidden.bs.toast', function () {
            $(".toast").removeClass('bg-success');
      if (action == 'add') {
              window.location.href = 'scheda.php?tipo='+tipoScheda+'&act=edit&id='+data.id+'#submit';
      }else{
              window.location.href = 'scheda.php?tipo='+tipoScheda+'&act=edit&id='+data.id;
      }
          });
      window.setTimeout(function(){
        if (action == 'add') {
          window.location.href = 'scheda.php?tipo='+tipoScheda+'&act=edit&id='+data.id+'#submit';
        }else{
          window.location.href = 'scheda.php?tipo='+tipoScheda+'&act=edit&id='+data.id;
        }
      },3000);
        }else {
          $(".toast").removeClass('[class^="bg-"]').addClass('bg-danger');
          $("#headerTxt").html('Errore nella query');
          $(".toast>.toast-body").html(data.msg);
          $(".toast").toast({delay:3000});
          $(".toast").toast('show');
          $('.toast').on('hidden.bs.toast', function () {
            $(".toast").removeClass('bg-danger');
          })
      $(".tastischeda").show();
        }
      })
      .fail(function() {console.log("error"); });
    }
  })
});
////// fine docuemnt ready

function mtcWrap(item, tecnica_value = null){
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
  $("<input/>",{type:'text', class:'form-control form-control-sm', name:'tecnicaItem'}).val(tecnica_value).prop('disabled', true).appendTo(col2);
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
  let valor = 0;
  if (dati.valor) {
  valor = dati.valor;
    $("<option/>",{value:'', text:'--seleziona valore--'}).prop('disabled',true).appendTo(dati.sel);
  }else{
    $("<option/>",{value:'', text:'--seleziona valore--'}).prop('disabled',true).prop('selected',true).appendTo(dati.sel);
  }
  $.ajax({url: API, type: 'POST', dataType: 'json', data: {trigger:'vocabolari', tab:dati.tab, filter:dati.filter}})
  .done(function(data) {
    if (data.length > 0) {
      data.forEach(function(v,i){
        switch (dati.sel.attr('name')) {
          case 'prvc':
        if (valor == v.codice) { $("<option/>",{value:v.codice, text:v.value}).prop('selected',true).appendTo(dati.sel); }
      else { $("<option/>",{value:v.codice, text:v.value}).appendTo(dati.sel); }
          break;
          default:
            if (valor == v.id) { $("<option/>",{value:v.id, text:v.value}).prop('selected',true).appendTo(dati.sel); }
      else { $("<option/>",{value:v.id, text:v.value}).appendTo(dati.sel); }
        }
      });
      dati.sel.prop('disabled',false);
    }
  })
  .fail(function(data) { console.log(data); });
}

////////////////////////////////////////////////////

function listaScheda(tiposcheda){
  $('#divListaScheda').html('');
  let txtSearch = $("[name=txtSearch]").val();
  let newdata = "";
  $.ajax({
    url: API,
    type: 'POST',
    dataType: 'json',
    data: {trigger:'listaScheda', tipo:tiposcheda, txtSearch:txtSearch}
  })
  .done(function(data) {
    let c = data.length;
    for (let x = 0; x < c; x++) {
      let id = data[x].id;
      let data_ins = data[x].data_ins;
      let titolo = data[x].titolo;
      let compilatore = data[x].compilatore;
      let classoodeven = ""; if (x%2 != 0) { classoodeven = " lista_even"; }
      // newdata += "<a href='scheda.php?tipo="+tiposcheda+"&act=edit&id="+id+"' target='_self' title='Visualizza / Modifica scheda'><div class='row lista"+classoodeven+"'><div class='col'>"+titolo+"  -  "+data_ins+"<span class='lista_compilatore'>"+compilatore+"</span></ div></div></a>";
      newdata += "<div class='row lista"+classoodeven+"'><div class='col'><a href='scheda.php?tipo="+tiposcheda+"&act=edit&id="+id+"' target='_self' title='Visualizza / Modifica scheda'>"+titolo+"  -  "+data_ins+"<span class='lista_compilatore'>"+compilatore+"</span></a></div></div>";
    }
    $('#divListaScheda').html(newdata);
  })
  .fail(function(data) { console.log(data); });
}
