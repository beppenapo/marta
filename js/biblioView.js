const API = 'api/biblio.php';
const ID = $("[name=idScheda]").val();
$(document).ready(function() {
  getScheda(ID);
  $("#scheda-card .list-group-item>span:first-child").css("width","120px");
  $("#scheda-card .list-group-item>span:last-child").css("width","calc(100% - 125px)");
});

$('[name=biblioDel]').on('click', function () {
  if (!confirm("Attenzione! Stai per elimnare la scheda bibliografica, procedere?")){
  return false;
  }

  $.ajax({
  url: API,
  type: 'POST',
  dataType: 'json',
  data: {trigger : 'deleteScheda', id:ID}
  })
  .done(function(data) {
    data.url='bibliografia.php';
    createToast(data);
  })
  .fail(function() {console.log("error"); });

});

function getScheda(id){
  $.ajax({ url: 'api/biblio.php', type: 'POST', dataType: 'json', data: {trigger: 'getScheda', id:id} })
  .done(function(data) {
    console.log(data);
    let scheda = data['scheda'];
    let schede = data['schede'];
    $("#title").text(scheda['titolo']);
    // for (key in data['scheda']) { $("#"+key+">span:last-child").text(data['scheda'][key]);}
    if (scheda['tipoid'] == 1) { $("#titolo_raccolta, #curatore").remove(); }
    $("#idScheda>span:last-child").text(scheda['id']);
    $("#tipo>span:last-child").text(scheda['tipo']);
    $("#autore>span:last-child").text(scheda['autore']);
    $("#altri_autori>span:last-child").text(scheda['altri_autori']);
    $("#titolo_raccolta>span:last-child").text(scheda['titolo_raccolta']);
    $("#curatore>span:last-child").text(scheda['curatore']);
    $("#anno>span:last-child").text(scheda['anno']);
    $("#editore>span:last-child").text(scheda['editore']);
    $("#luogo>span:last-child").text(scheda['luogo']);
    $("#isbn>span:last-child").text(scheda['isbn']);
    $("#url>span:last-child>a").prop("href",scheda['url']).text(scheda['url']);
    let listaSchede = "#schede>ul";
    if(schede.length==0){
      $("#schede .card-body").html('<p>Non sono presenti schede correlate</p>')
      $("#schede us").remove();
    }else {
      $("#totSchede").text(schede.length)
      $("#schede .card-body").remove();
      schede.forEach(function(v,i){
        let tipo = v.tsk == 1 ? 'RA' : 'NU';
        let testo = v.nctn+' - '+tipo+' - '+v.titolo;
        let li = $("<li/>",{class:'list-group-item'}).appendTo(listaSchede);
        $("<a/>",{href:"scheda.php?get="+v.scheda, text: testo}).appendTo(li);
      })
    }
  })
  .fail(function() {console.log("error");});
}
