$(document).ready(function() {
  const scheda = parseInt($("[name=schedaId]").val());
  const nctn = parseInt($("[name=nctnId]").val());
  const tsk = parseInt($("[name=tsk]").val());
  let urlMod = tsk == 1 ? 'scheda-ra-mod.php' : 'scheda-nu-mod.php';
  let cloneMod = tsk == 1 ? 'scheda-ra-clone.php' : 'scheda-nu-clone.php';

  $("[name=modificaScheda]").on('click', function(){ $.redirectPost(urlMod,{s:scheda,act:'mod'}); })
  $("[name=duplicaScheda]").on('click', function(){ $.redirectPost(cloneMod,{s:scheda,act:'clone'}); })

  $(".list-group-item > span").each(function(){
    if ($(this).text()=='dato non inserito') {
      $(this).removeClass('font-weight-bold').addClass('text-secondary')
    }
  })

  $("[name=delBiblioScheda]").on('click', function() {
    dati = {}
    dati.biblio = $(this).data('biblio');
    dati.scheda = $(this).data('scheda');
    if (window.confirm("vuoi davvero eliminare l'associazione tra il record bibliografico scelto e la presente scheda?")) {
      delBiblioScheda(dati);
    }
  });
  $("[name=eliminaScheda]").on('click', function(event) {
    dati = {}
    dati.id = parseInt(scheda);
    dati.nctn = parseInt(nctn);
    if (window.confirm("Eliminando la scheda eliminerai anche tutti i dati collegati come file, immagini ecc.\nSei sicuro di voler procedere con l'eliminazione?")) {
      delScheda(dati);
    }
  })
  $("[name=cambiaStato]").on('click', (e) => {
    let stato = e.target.value;
    let confMsg='';
    switch (stato) {
      case 'chiusa':
        confMsg = "Stai per chiudere la scheda, se confermi non potrai più effettuare modifiche. Per riaprire la scheda in modifica devi contattare un responsabile.";
      break;
      case 'riapri':
        confMsg = "Stai per riaprire la scheda, in questo modo permetterai al compilatore di modificarne nuovamente i dati o di aggiungere nuovi contenuti come foto, documenti ecc.";
      break;
      case 'verificata':
        confMsg = 'Stai per cambiare lo stato della scheda in "verificata", ciò significa che la scheda sarà pronta per essere inviata al server ICCD. Se vuoi modificare la scheda devi farla riaprire dal funzionario di riferimento';
      break;
      case 'inviata':
        confMsg = 'Stai per inviare la scheda al server ICCD, non potrai più riaprirla finché non sarà accettata o rifiutata da parte di ICCD';
      break;
      case 'accettata':
        confMsg = "Se confermi, chiudi l'iter di creazione e validazione della scheda! Se decidi di riprirla ad eventuali modifiche dovrai ripetere il processo dall'inizio";
      break;
    }
    if(stato == 'riapri'){
      stato = 'chiusa';
      val = false;
    }else {
      val = true;
    }
    if (window.confirm(confMsg)) { cambiaStatoScheda(scheda, stato, val); }
  });
  getFoto(scheda);
  mapInit();
});
function getStatoMsg(scheda, stato, valore){
  let msg;
  if(stato == 'chiusa' && valore == true){msg = 'Ok la scheda risulta chiusa';}
  if(stato == 'chiusa' && valore == false){msg = 'Ok la scheda risulta aperta';}
  if(stato == 'verificata' && valore == true){msg = 'Ok la scheda è stata verificata da un responsabile';}
  if(stato == 'verificata' && valore == false){msg = 'Ok la scheda è in attesa di essere verificata da un responsabile';}
  if(stato == 'inviata' && valore == true){msg = 'Ok la scheda è stata inviata al server ICCD';}
  if(stato == 'inviata' && valore == false){msg = 'Ok la scheda è in attesa di essere inviata al server ICCD';}
  if(stato == 'accettata' && valore == true){msg = 'Ok la scheda è stata accettata dal server ICCD';}
  if(stato == 'accettata' && valore == false){msg = 'Ok la scheda è in attesa di essere accettata dal server ICCD';}
  return msg
}
function cambiaStatoScheda(scheda,stato, valore){
  let msg = getStatoMsg(scheda, stato, valore);
  let dati = {trigger:'cambiaStatoScheda', scheda:scheda}
  if (stato == 'chiusa') { dati.chiusa = valore;}
  if (stato == 'verificata') { dati.verificata = valore;}
  if (stato == 'inviata') { dati.inviata = valore;}
  if (stato == 'accettata') { dati.accettata = valore;}

  $.ajax({
    url: "api/scheda.php",
    type: 'POST',
    dataType: 'json',
    data: dati
  })
  .done(function(data){
    obj={}
    obj.res=data
    obj.msg = data === true ? msg : 'Errore nella query: '+data.msg;
    obj.btn = [];
    obj.btn.push("<button type='button' class='btn btn-light btn-sm' name='continua'>ok</button>");
    toast(obj);
  })
  .fail(function (jqXHR, textStatus, error) {
    console.log("Post error: " + error);
  });
}

function checkScheda(scheda, callBack){
  $.ajax({
    url: "api/scheda.php",
    type: 'POST',
    dataType: 'json',
    data: {trigger:'getStatoScheda', id:scheda}
  })
  .done(callBack)
  .fail(function (jqXHR, textStatus, error) {
    console.log("Post error: " + error);
  });
}

function initChart(check){
  let biblio = $(".biblioList").length;
  let foto = $(".fotoDiv").length;
  let w, value, chartLabel,bgColor;
  if (check.chiusa == false) {
    chartLabel = "Per chiudere la scheda devi inserire";
    if (biblio == 0 & foto == 0) {
      chartLabel = chartLabel + " 1 riferimento bibliografico e almeno 2 immagini";
      value = 0;
      bgColor = '#8a0505';
    }
    if (biblio == 0 & foto == 1) {
      chartLabel = chartLabel + " 1 riferimento bibliografico e almeno un'altra immagine";
      value = 15;
      bgColor = '#8a0505';
    }
    if (biblio == 0 & foto >= 2) {
      chartLabel = chartLabel + " 1 riferimento bibliografico";
      value = 30;
      bgColor = '#8a0505';
    }
    if (biblio == 1 & foto == 0) {
      chartLabel = chartLabel + " almeno 2 immagini";
      value = 15
      bgColor = '#8a0505';
    }
    if (biblio == 1 & foto == 1) {
      chartLabel = chartLabel + " almeno un'altra immagine";
      value = 30;
      bgColor = '#8a0505';
    }
    if (biblio == 1 & foto >1) {
      chartLabel = "ok, puoi chiudere la scheda!";
      value = 45;
      bgColor = '#ffc003';
    }
  }
  if (check.chiusa == true & check.verificata == false) {
    chartLabel = " ok, la scheda risulta chiusa ma non ancora verificata";
    value = 45;
    bgColor = '#ffc003';
  }
  if (check.verificata == true & check.inviata == false) {
    chartLabel = " ok, la scheda risulta chiusa e verificata ma non ancora inviata al server ICCD";
    value = 60;
    bgColor = '#ffc003';
  }
  if (check.inviata == true & check.accettata == false) {
    chartLabel = " ok, la scheda risulta chiusa, verificata e inviata ma non è ancora stata accettata dal server ICCD";
    value = 75;
    bgColor = '#ffc003';
  }
  if (check.accettata == true) {
    chartLabel = "Complimenti, iter completato!!!!<br>La scheda risulta chiusa, verificata, inviata e accettata dal server ICCD";
    value = 100;
    bgColor = '#32ad32';
  }

  value >= 45 ?  $("#chiudiScheda").show() : $("#chiudiScheda").hide()
  w = screen.width < 768 ? 200 : 400;
  var knob = pureknob.createKnob(w, w);
  knob.setProperty('angleStart', -0.75 * Math.PI);
  knob.setProperty('angleEnd', 0.75 * Math.PI);
  knob.setProperty('colorFG', bgColor);
  knob.setProperty('trackWidth', 0.4);
  knob.setProperty('valMin', 0);
  knob.setProperty('valMax', 100);
  knob.setProperty('readonly', true);
  knob.setValue(value);
  var node = knob.node();
  var elem = document.getElementById('knob');
  elem.appendChild(node);
  $("#labelStato").html(chartLabel).css("color",bgColor);
}

function delScheda(dati){
  $.ajax({
    url: "api/scheda.php",
    type: 'POST',
    dataType: 'json',
    data: {trigger:'delScheda', dati:dati}
  })
  .done(function(data){
    console.log(data);
    obj={}
    obj.res=data
    obj.msg = data === true ? 'La scheda e tutti gli oggetti collegati sono stati eliminati.' : data.msg;
    obj.classe = data === true ? 'bg-success' : 'bg-danger';
    obj.url='schede.php';
    obj.btn = [];
    obj.btn.push("<a href='"+obj.url+"' class='btn btn-light btn-sm'>ok</a>");
    toast(obj);
  })
  .fail(function (jqXHR, textStatus, error) {
    console.log("Post error: " + error);
  });
}

function delBiblioScheda(dati){
  $.ajax({
    url: "api/scheda.php",
    type: 'POST',
    dataType: 'json',
    data: {trigger:'delBiblioScheda', dati:dati}
  })
  .done(function(data){
    obj={}
    obj.res=data
    obj.msg = data === true ? 'il riferimento bibliografico è stato eliminato' : 'Errore nella query: '+data.msg;
    obj.url = 'schedaView.php?get='+$("[name=schedaId]").val();
    obj.btn = [];
    obj.btn.push("<a href='"+obj.url+"' class='btn btn-light btn-sm'>ok</a>");
    toast(obj);
  })
  .fail(function (jqXHR, textStatus, error) {
    console.log("Post error: " + error);
  });
}

function mapInit(){
  let checkComune,rank=0;
  let checkPoint = [];
  if ($("[name=id_comune]").length + $("[name=gpdpx]").length > 0) {
    $("#alertWrap").hide();
  }
  if ($("[name=id_comune]").length > 0) { checkComune = $("[name=id_comune]").val(); }
  if ($("[name=gpdpx]").length > 0) { checkPoint= [$("[name=gpdpy]").val(), $("[name=gpdpx]").val()];}
  $("#cardInfoMappa").hide();
  $("#map").css({"width":"100%","height":$("#mappaFieldset").innerWidth()});

  var map = L.map('map',{maxBounds:pugliaExt}).fitBounds(pugliaExt);
  map.setMinZoom(map.getZoom());
  let osm = L.tileLayer(osmTile, {attribution: osmAttrib});
  let gStreets = L.tileLayer(gStreetTile,{maxZoom: 20, subdomains:gSubDomains });
  let gSat = L.tileLayer(gSatTile,{maxZoom: 20, subdomains:gSubDomains});
  let gTerrain = L.tileLayer(gTerrainTile,{maxZoom: 20, subdomains:gSubDomains});
  gSat.addTo(map)
  var baseLayers = {
    "Terrain":gTerrain,
    "Satellite": gSat,
    "OpenStreetMap": osm,
    "Google Street": gStreets
  };
  var overlay = {};
  let comune = L.featureGroup().addTo(map);
  let marker = L.featureGroup().addTo(map);
  //se è stato salvato il Comune lo aggiungo alla mappa

  if (checkComune > 0) {
    rank = 1;
    let idComune = $("[name=id_comune]").val();
    $.getJSON( 'api/geom.php',{ trigger: 'getComune', id:idComune})
    .done(function( json ) {
      console.log(json);
      let l = L.geoJson(json).addTo(comune);
      if (checkPoint.length == 0) { map.fitBounds(l.getBounds()); }
    })
    .fail(function( jqxhr, textStatus, error ) {
      console.log("Request Failed: " + jqxhr+", "+textStatus + ", " + error );
    });
    overlay["Comune"]=comune;
  }

  if (checkPoint.length > 0) {
    rank = 2;
    L.marker(checkPoint).addTo(marker);
    overlay["Reperto"]=marker;
    map.removeLayer(comune)
    map.fitBounds(marker.getBounds());
  }

  L.control.layers(baseLayers, overlay, {position: 'bottomright'}).addTo(map);

  let resetMap = L.Control.extend({
    options: { position: 'topleft'},
    onAdd: function (map) {
      var container = L.DomUtil.create('div', 'extentControl leaflet-bar leaflet-control leaflet-touch');

      btn1=$("<a/>",{href:'#', title:'zoom massimo'}).attr({"data-toggle":"tooltip","data-placement":"right"}).appendTo(container);
      $("<i/>",{class:'fa-solid fa-crosshairs'}).appendTo(btn1)
      btn1.on('click', function (e) {
        e.preventDefault()
        map.fitBounds(pugliaExt);
      });

      if (checkComune > 0){
        btn2=$("<a/>",{href:'#', title:'zoom al comune'}).attr({"data-toggle":"tooltip","data-placement":"right"}).appendTo(container);
        $("<i/>",{class:'fa-solid fa-map-location-dot'}).appendTo(btn2)
        btn2.on('click', function (e) {
          e.preventDefault()
          map.addLayer(comune)
          map.fitBounds(comune.getBounds());
        });
      }
      if (checkPoint.length > 0) {
        btn3=$("<a/>",{href:'#', title:'zoom al reperto'}).attr({"data-toggle":"tooltip","data-placement":"right"}).appendTo(container);
        $("<i/>",{class:'fa-solid fa-location-dot'}).appendTo(btn3)
        btn3.on('click', function (e) {
          e.preventDefault()
          map.removeLayer(comune)
          map.addLayer(marker)
          map.fitBounds(marker.getBounds());
        });
      }
      return container;
    }
  })

  map.addControl(new resetMap());
}

$("#fotoModal").hide();
function getFoto(scheda){
  $('.fotoWrap').html('');
  let wrapWidth = $(".fotoWrap").innerWidth();
  let w;
  switch (true) {
  case screen.width < 768:
    w = '100%';
  break;
  case screen.width < 1200:
    w = (wrapWidth / 2) - 5 +"px";
  break;
  case screen.width >= 1200:
    w = (wrapWidth / 3) - 5 +"px";
  break;
}

  $.ajax({
    url: "api/scheda.php",
    type: 'POST',
    dataType: 'json',
    data: {trigger:'getFoto', id:scheda}
  })
  .done(function(data){
    if (data.length == 0) {
      $(".fotoWrap").html("<h5>Nessuna immagine caricata</h5>");
      return;
    }
    data.forEach((item, i) => {
      let div = $("<div/>",{class:'fotoDiv'}).css({"width":w,"height":w,"background-image": "url("+fotoPath+item.file+")"});
      let overlay = $("<div/>",{class:'fotoOverlay animated'}).html('<i class="bi bi-arrows-fullscreen text-white"></i>').appendTo(div);
      div.appendTo('.fotoWrap')
      div.on('click', () => {
        $("#divImgOrig").css({"background-image":"url("+fotoPath+item.file+")"});
        $("#fotoModal").fadeIn('fast');
        $("#closeModal").on('click', (e) => {
          e.preventDefault();
          $("#fotoModal").fadeOut('fast');
        })
        $("#downloadImg").attr("href",fotoPath+item.file);
        $("#delImg").on('click', (e) => {
          e.preventDefault();
          dati={id:item.id, scheda:item.scheda, file:item.file}
          if (window.confirm("Sei sicuro di voler eliminare l'immagine? Se confermi l'immagine verrà definitivamente eliminata dal server e non sarà più possibile recuperarla.")) {
            delImg(dati);
          }
        })
      })
    });
  })
  .fail(function (jqXHR, textStatus, error) { console.log("Post error: " + error); }
  );
  if ($("[name=logged]").val()) { checkScheda(scheda,initChart) }
}

function delImg(dati){
  $.ajax({
    url: "api/file.php",
    type: 'POST',
    dataType: 'json',
    data: {trigger:'delImg', dati:dati}
  })
  .done(function(data){
    obj={}
    obj.res=data
    obj.msg = data === true ? "l'immagine è stata eliminata" : 'Errore nella query: '+data.msg;
    obj.btn = [];
    obj.btn.push("<button type='button' class='btn btn-light btn-sm' name='continua'>ok</button>");
    $("#fotoModal").fadeOut('fast', function(){
      toast(obj);
    });
  })
  .fail(function (jqXHR, textStatus, error) {
    console.log("Post error: " + error);
  });
}
