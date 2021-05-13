const ID = $("[name=idScheda]").val();
$(document).ready(function() {
  getScheda(ID);
  $("#scheda-card .list-group-item>span:first-child").css("width","120px");
  $("#scheda-card .list-group-item>span:last-child").css("width","calc(100% - 125px)");
});

function getScheda(id){
  $.ajax({ url: 'api/biblio.php', type: 'POST', dataType: 'json', data: {trigger: 'getScheda', id:id} })
  .done(function(data) {
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
    schede.forEach(function(v,i){
      let tipo = v.tipo == 1 ? 'RA' : 'NU';
      let testo = 'scheda '+tipo+' num.'+v.scheda+', '+v.ogtd;
      let li = $("<li/>",{class:'list-group-item'}).appendTo(listaSchede);
      $("<a/>",{href:"scheda.php?get="+v.scheda, text: testo}).appendTo(li);
    })
  })
  .fail(function() {console.log("error");});
}
