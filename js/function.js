//NOTE: creazione meta base
const BASE = window.location.protocol + '//' + window.location.hostname + (window.location.port ? ':' + window.location.port : '') + "/marta/";
document.head.innerHTML = document.head.innerHTML + "<base href='" +  BASE + "' />";
const spinner = "<i class='fas fa-circle-notch fa-spin fa-3x'></i>";
const toolTipOpt = {container: 'body', boundary: 'viewport', selector: '[data-toggle=tooltip]', html: true}
//NOTE: gestione menù laterale
if (screen.width >= 992 ) {
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
  $("body>nav#mainMenu").on("click", function (event) {
    event.stopPropagation();
  });
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
