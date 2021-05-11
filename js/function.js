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

// aggiungi il seguente blocco nelle pagine che prevedono chiamate ajax:
// <div id="loadingDiv" class="flexDiv"><i class='fas fa-circle-notch fa-spin fa-5x'></i></div>
$(document)
.ajaxStart(function(){$("#loadingDiv").removeClass('invisible');})
.ajaxStop(function(){$("#loadingDiv").addClass('invisible');});

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
    postData("scheda.php", {trigger:'vocabolari', tab:obj[key]['tab']}, function(data){
      buildList(data,obj[key]['sel']);
    })
  });
}

function buildList(data, sel){
  let el = $("[name="+sel+"]");
  $.each(data,function(i, v){
    switch (sel) {
      case "prvp":
        $("<option/>", {value:v.codice, text:v.value}).appendTo(el);
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
  console.log(obj);
  obj.titolo='Risultato query';
  classe = obj.res === true ? 'bg-success' : 'bg-danger';
  $(".toast").removeClass('[class^="bg-"]').addClass(classe);
  $("#headerTxt").html('Risultato query');
  $(".toast>.toast-body>.toast-body-msg").html(obj.msg);
  $(".toast").toast({delay:3000});
  $(".toast").show();
  $(".toast").toast('show');
  $('.toast').on('hidden.bs.toast', function () {
    $(".toast").removeClass(classe);
    if (obj.res === true) {
      window.setTimeout(function(){window.location.href = obj.url},1000);
    }
    $(".toast").hide();
  })
}
