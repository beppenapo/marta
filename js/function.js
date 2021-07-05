// aggiungi il seguente blocco nelle pagine che prevedono chiamate ajax:
// <div id="loadingDiv" class="flexDiv"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
let start
$(document)
.ajaxStart(function(){ $("#loadingDiv").removeClass('invisible');})
.ajaxStop(function(){ $("#loadingDiv").addClass('invisible');});
//NOTE: creazione meta base
const BASE = window.location.protocol + '//' + window.location.hostname + (window.location.port ? ':' + window.location.port : '') + "/marta/";
document.head.innerHTML = document.head.innerHTML + "<base href='" +  BASE + "' />";
const TOTRA = 20000;
const TOTNU = 20000;
const TOTFOTO = 80000;
const TOTSTEREO = 5000;
const TOT3D = 110;
///////////////////////////
let log = $("body>header").data('log');
const spinner = "<i class='fas fa-circle-notch fa-spin fa-3x'></i>";
const toolTipOpt = {container: 'body', boundary: 'viewport', selector: '[data-toggle=tooltip]', html: true}
//NOTE: gestione menù laterale
if (screen.width >= 992 ) {
  log == 'y' ? $("body>main").addClass('mainPadding') : $("body>main").removeClass('mainPadding');
  $("body>nav#mainMenu").addClass('open');
}else {
  $("body>nav#mainMenu").addClass('closed');
  $(document).on("click", function () {
    if ($("body>nav#mainMenu").hasClass('open')) {
      $("body>nav#mainMenu").toggleClass('open closed').toggleClass('shadow-lg');
    }
  });
  $("body").on('click', '#toggleMenu', function(event) {
    event.preventDefault();
    event.stopPropagation();
    $("body>nav#mainMenu").toggleClass('open closed').toggleClass('shadow-lg');
  });
  $("body>nav#mainMenu").on("click", function (event) { event.stopPropagation(); });
}

// toggle nctn
$("[name=toggleNctn]").on('click', function(){
  $('[name=nctn]')
    .prop('disabled', function(i, v) { return !v; })
    .prop('required', function(i, v) { return !v; });
})

// funzioni per select dinamiche sezione LC
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
  $.each(slot, function(index, val){options.push("<option value='"+val+"'>"+val+"</option>");});
  $("#ripianoLabel").text(label);
  $("[name=ripiano]").html(options.join());
  $("#lcRipianoDiv").fadeIn('fast');
})

//Sblocca obbligatorietà di contesto
$("body").on('click', '[name=toggleSection]', function() {
  var fieldset = $(this).data('fieldset');
  $("#"+fieldset+" .tab").prop('disabled',(i,v)=>!v);
  $("#"+fieldset+" label.obbligatorio").toggleClass('text-danger');
  $("#"+fieldset+" .tab.obbligatorio").prop('required',(i,v)=>!v);
});

//Sblocca Misure
$("[name=misr]").on('click', function(event) {
  $(".misure").prop('disabled',(i,v)=>!v);
})

$("body").on('change', '[name=prvp]', function(el) {
  const dati = {
    el:el,
    sel:$('[name=prvc]'),
    tab:'liste.pvcc',
    filter:{field:'pvcp',value:$(this).val(), op:'='}
  }
  subList(dati);
});

//funzioni per la gestione della cronologia
$("body").on('change', '[name=dtzg]', setCrono);
$("body").on('change', '[name=dtzs]', function() {
  let dtzg = $("[name=dtzg]").val();
  if (!dtzg) {
    alert('Attenzione, ricordati di selezionare una fascia cronologica');
    return false;
  }
  setCrono()
});
function setCrono(){
  let dtzg = $("[name=dtzg]").val()
  let dtzs = $("[name=dtzs]").val()
  let dati = {trigger:'setCrono',dtzg:dtzg};
  if (dtzs) { dati.dtzs=dtzs; }
  $.ajax({
    type: "POST",
    url: "api/scheda.php",
    data: dati,
    dataType: 'json',
    success: function(data){
      $("[name=dtsi]").val(data[0]['dtsi']);
      $("[name=dtsf]").val(data[0]['dtsf']);
    }
  });
}

var dtm=[];
$("body").on('change', '[name=dtm]', function() {
  let v = parseInt($(this).val());
  let t = $(this).find("option:selected").text();
  if (dtm.includes(v)===true) {
    alert('La definizione "'+t+'" è già stata selezionata!');
    return false;
  }
  dtm.push(v);
  $(this).prop("required", false);
  var group = $("<div/>", {class:'input-group mt-3', id:'dtm'+v}).appendTo('#dtmWrap');
  $("<input/>",{type:'text', class:'form-control form-control-sm', name:'dtmText'}).val(t).prop('disabled', true).appendTo(group);
  var addon = $("<div/>", {class:'input-group-append'}).appendTo(group);
  $("<button/>",{class:'btn btn-danger btn-sm', type:'button', name:'delDtmOpt'})
    .html('<i class="fas fa-times"></i>')
    .appendTo(addon)
    .on('click', function(){
      group.remove();
      dtm = dtm.filter(item => item !== v)
      if(dtm.length == 0){$("[name=dtm]").prop("required", true);}
    });
});

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
    let l = tecnicaItem.val().split(', ');
    if (l.includes(v)===true) {
      alert('La tecnica "'+v+'" è già stata selezionata!');
      return false;
    }
    tecnicaItem.val(tecnicaItem.val()+', '+ v);
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

//TODO: errore di visualizzazione, offset sbagliato, da sistemare
$("body").tooltip(toolTipOpt);

$("[name=logOutBtn]").on('click', function(e){
  e.preventDefault();
  localStorage.removeItem("sex");
  $.redirectPost('logout.php');
})
//NOTE: copyright footer
let data = getData();
let copyright = "MArTA - Museo Archeologico Nazionale di Taranto - ";
copyright = (data.y = 2020) ? copyright + '2020' : copyright + '2020 - '+copyright.y;
copyright=copyright+"  Tutti i diritti riservati";
$(".copyright>span").html(copyright);

//NOTE: funzioni generali
function getData(){
  let data = new Date();
  let d = data.getDate();
  let m = data.getMonth() + 1;
  let y = data.getFullYear();
  return {d:d, m:m, y:y}
}

$.extend({
  redirectPost: function(location, args){
    const form = $('<form></form>');
    form.attr("method", "post");
    form.attr("action", location);
    $.each( args, function( key, value ) {
      let field = $('<input></input>');
      field.attr("type", "hidden");
      field.attr("name", key);
      field.attr("value", value);
      form.append(field);
    });
    $(form).appendTo('body').submit();
  }
});
function nl2br(str){return str.replace(/(?:\r\n|\r|\n)/g, '<br>');}
function getList(obj){
  Object.keys(obj).forEach(function (key){
    postData("scheda.php", {trigger:'vocabolari', tab:obj[key]['tab'], filter:obj[key]['filter']}, function(data){verniciatura
      buildList(data,obj[key]['sel']);
    })
  });
}

function buildList(data, sel){
  let el = $("[name="+sel+"]");
  $("<option/>", {value:'', text:'--seleziona valore--'}).prop('selected', true).prop('disabled', false).appendTo(el);
  $.each(data,function(i, v){
    switch (sel) {
      case "prvp":
        $("<option/>", {value:v.codice, text:v.pvcp}).appendTo(el);
      break;
      default: $("<option/>", {value:v.id, text:v.value}).appendTo(el);
    }
  });
  if (sel == 'cdgg') { $("[name=cdgg] option[value=1]").prop('selected', true); }
}

function postData(url, dati, callback){
  $.ajax({
    type: "POST",
    url: "api/"+url,
    data: dati,
    dataType: 'json',
    success: function(data){ return callback(data); }
  });
}

function stat(){
  $("#totSchede").text(TOTRA+TOTNU);
  $.ajax({url:'api/home.php',type:'POST',dataType:'json',data:{trigger:'statHome'}})
  .done(function(data){
    let raPerc = parseInt(data['ra']*100/TOTRA);
    let nuPerc = parseInt(data['nu']*100/TOTNU);
    $("#numschede").text(parseInt(data['ra'])+parseInt(data['nu']));
    $("#percSchedeOk").text(parseInt(raPerc+nuPerc));
    $("#raBar").attr('style','width:'+raPerc+'%').attr('aria-valuenow',raPerc);
    $("#nuBar").attr('style','width:'+nuPerc+'%').attr('aria-valuenow',nuPerc);

    $("#numFoto").text(data['foto']);
    let fotoPerc = parseInt(data['foto']*100/TOTFOTO);
    $("#fotoBar").attr('style','width:'+fotoPerc+'%').attr('aria-valuenow',fotoPerc);
    $("#percFoto").text(fotoPerc);

    $("#numStereo").text(data['stereo']);
    let stereoPerc = parseInt(data['stereo']*100/TOTSTEREO);
    $("#stereoBar").attr('style','width:'+stereoPerc+'%').attr('aria-valuenow',stereoPerc);
    $("#stereoFoto").text(stereoPerc);

    $("#numModelli").text(data['modelli']);
    let modelliPerc = parseInt(data['modelli']*100/TOT3D);
    $("#3dBar").attr('style','width:'+modelliPerc+'%').attr('aria-valuenow',modelliPerc);
    $("#perc3d").text(modelliPerc);
  })
  .fail(function(){console.log('error');});
}
$(".toast").hide();
function createToast(obj){
  obj.titolo='Risultato query';
  classe = obj.res === true ? 'bg-success' : 'bg-danger';
  $(".toast").removeClass('[class^="bg-"]').addClass(classe);
  $("#headerTxt").html('Risultato query');
  $(".toast>.toast-body>.toast-body-msg").html(obj.msg);
  $(".toast").toast({delay:3000});
  $(".toast").show();
  $(".toast").toast('show');
  $("[name='continua']").on('click', function(){window.location.reload(true);})

  if (obj.res === true) {
    $("[name='continua']").show();
    $("[name='viewRec']").text('visualizza record').on('click', function(){window.location.href = obj.url});
  }else {
    $("[name='viewRec']").text('ok, chiudi alert');
    $("[name='continua']").hide();
    $('.toast').on('hidden.bs.toast', function () {
      $(".toast").removeClass(classe);
      $(".toast").hide();
    })
  }
}

// NOTE: funzioni gestione schede ///
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
    let selected = "";
    if (value == null) { selected = " selected"; }
    options.push("<option value='' disabled"+selected+">-- sala --</option>")
    $.each(data, function(index, el) {
      selected = el.id == value ? 'selected' : '';
      let opt = !el.descrizione ? el.sala : el.descrizione;
      options.push("<option value='"+el.id+"'"+selected+">"+opt+"</option>");
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

function mtcWrap(item, tecnica_value = null){
  var label = item.label.replace(/\s+|'/g, '-');
  const wrap = $("#mtcWrap");
  if ($("#"+label+"Row").length > 0) {
    $("[name=materia]").val('');
    $("[name=tecnica]").val('').prop('disabled', true);
    $("[name=addTecnica], [name=addMtc]").prop('disabled', true);
    alert("Attenzione! La materia '"+item.label+"' è stata già definita, puoi cancellarla e ridefinirla o scegliere una nuova materia");
    return false
  }
  $("[name=materia]").autocomplete('disable');
  $("[name=materia]").prop('disabled', true);
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
    $("[name=materia]").prop('disabled', false);
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
  $("<option/>").val(v).text(text).appendTo('[name=dtm]');
  var options = $("[name=dtm] option");
  options.detach().sort(function(a,b) {
    var at = $(a).text();
    var bt = $(b).text();
    return (at > bt)?1:((at < bt)?-1:0);verniciatura
  });
  options.appendTo("[name=dtm]");
}

function subList(dati){
  dati.sel.html('').prop('disabled',true);
  let v = 0;
  if (dati.v) {
    v = dati.v;
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
            if (v == v.codice) {
              $("<option/>",{value:v.codice, text:v.pvcc}).prop('selected',true).appendTo(dati.sel);
            } else {
              $("<option/>",{value:v.codice, text:v.pvcc}).appendTo(dati.sel);
            }
          break;
          default:
            if (v == v.id) { $("<option/>",{value:v.id, text:v.value}).prop('selected',true).appendTo(dati.sel); }
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
      newdata += "<div class='row lista"+classoodeven+"'><div class='col'><a href='scheda.php?tipo="+tiposcheda+"&act=edit&id="+id+"' target='_self' title='Visualizza / Modifica scheda'>"+titolo+"  -  "+data_ins+"<span class='lista_compilatore'>"+compilatore+"</span></a></div></div>";
    }
    $('#divLisNutaScheda').html(newdata);
  })
  .fail(function(data) { console.log(data); });
}

////////////////////////////////////////////
$("#errorDiv").hide();
function salvaScheda(e){
  $("#errorDiv").hide()
  $("#errorDiv>ul").html('');
  let isvalidate = $("#formScheda")[0].checkValidity()
  // let isvalidate = true
  if (isvalidate) {
    let dati={}
    let tab=[]
    let field = []
    let val = []
    let mtcVal = []
    let errori = []
    e.preventDefault();
    $("[data-table]").each(function(){
      if ($(this).is("input:text") || $(this).is("input[type=number]") || $(this).is("input:hidden") || $(this).is("select") || $(this).is("textarea") || $(this).is(":radio:checked") || $(this).is(":checkbox:checked")) {
        if (!$(this).is(':disabled')) {
          if ($(this).val()) {
            tab.push($(this).data('table'));
            field.push({tab:$(this).data('table'),field:$(this).attr('name')});
            val.push({tab:$(this).data('table'),field:$(this).attr('name'),val:$(this).val()});
          }
        }
      }
    });
    tab = tab.filter((v, p) => tab.indexOf(v) == p);
    $.each(tab,function(i,v){ dati[v]={} })
    $.each(field,function(i,v){ dati[v.tab][v.field]={} })
    $.each(val,function(i,v){ dati[v.tab][v.field]=v.val })

    if (dtm.length == 0) {
      errori.push("Il campo DTM - 'Motivazione cronologia' è obbligatorio! Devi scegliere almeno un valore dalla lista.");
    }else {
      dati.dtm=dtm;
    }

    $("#mtcWrap>div").each(function(){
      label = $(this).find("[name=materiaLabel]").val();
      materia = $(this).find("[name=materiaItem]").val();
      materia = parseInt(materia);
      tecnica = $(this).find("[name=tecnicaItem]").val();
      if (tecnica) {
        mtcVal.push({materia,tecnica});
      }else {
        errori.push("La materia '"+label+"' non ha nessuna tecnica associata. Seleziona uno o più valori dalla lista delle tecniche e clicca sul tasto ok per confermare la scelta.");
      }
    });
    if (mtcVal.length == 0) {
      errori.push("Devi selezionare almeno una materia e una tecnica! Segui le istruzioni presenti nella sezione specifica per una corretta compilazione dei campi.");
    }else {
      dati.mtc=mtcVal;
    }

    let countMis = 0;
    $(".misure").each(function(){ if($(this).val()){countMis ++;} })
    if (!$("[name=misr]").is(':checked') && countMis == 0) {
      errori.push("Devi inserire almeno una misura o cliccare su 'misure non rilevabili'");
    }

    if(errori.length>0){
      $("#errorDiv").fadeIn('fast')
      $("#errorDiv>ul").html('');
      errori.forEach((errore, i) => {
        $("<li/>",{class:'list-group-item'}).text(errore).appendTo('#errorDiv>ul')
      });
      return false;
    }
    console.log(dati);
    $.ajax({
      url: 'api/scheda.php',
      type: "POST",
      dataType: 'json',
      data: {trigger : 'addScheda',  dati}
    })
    .done(function(data){
      data.url='schedaView.php?sk='+data.scheda;
      createToast(data);
    })
    .fail(function(){console.log("error");});

  }
}
