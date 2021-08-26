$(document).ready(function() {
  const scheda = parseInt($("[name=schedaId]").val());
  const nctn = parseInt($("[name=nctnId]").val());
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
$("#chiudiScheda").on('click', (e) => {
  if (window.confirm("Stai per chiudere la scheda, se confermi non potrai più effettuare modifiche. Per riaprire la scheda in modifica devi contattare un responsabile.")) {
    cambiaStatoScheda(scheda, 'chiusa', true);
  }
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
    chartLabel = chartLabel + " ok, la scheda risulta chiusa ma non ancora verificata";
    value = 45;
    bgColor = '#ffc003';
  }
  if (check.verificata == true & check.inviata == false) {
    chartLabel = chartLabel + " ok, la scheda risulta chiusa e verificata ma non ancora inviata al server ICCD";
    value = 60;
    bgColor = '#ffc003';
  }
  if (check.inviata == true & check.accettata == false) {
    chartLabel = chartLabel + " ok, la scheda risulta chiusa, verificata e inviata ma non è ancora stata accettata dal server ICCD";
    value = 75;
    bgColor = '#ffc003';
  }
  if (check.accettata == true) {
    chartLabel = chartLabel + "Iter completato!!!! La scheda risulta chiusa, verificata, inviata e accettata dal server ICCD";
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
  $("#labelStato").text(chartLabel).css("color",bgColor);
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
  let mappaDim = $("#mappaFieldset").innerWidth();
  let x = parseFloat($("[name=gpdpx]").val()).toFixed(4)
  let y = parseFloat($("[name=gpdpy]").val()).toFixed(4)
  let epsg = parseInt($("[name=epsg]").val())
  let zoom = 13;
  let center = [40.4391259,17.2153126];
  // console.log([x,y,epsg]);

  $("#map").css({"width":"100%","height":mappaDim});

  var map = L.map('map',{minZoom:zoom}).setView(center,zoom);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);
  L.marker([40.4737451,17.2365453]).addTo(map).bindPopup("MArTA").openPopup();
  let osm = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
    opacity:0.7
  }).addTo(map)
}

$("#fotoModal").hide();
function getFoto(scheda){
  $('.fotoWrap').html('');
  let wrapWidth = $(".fotoWrap").innerWidth();
  let w,folder;
  switch (true) {
    case screen.width < 768:
      w = '100%';
      folder = 'file/foto/small/';
    break;
    case screen.width < 1200:
      w = (wrapWidth / 2) - 5 +"px";
      folder = 'file/foto/medium/';
    break;
    case screen.width >= 1200:
      w = (wrapWidth / 3) - 5 +"px";
      folder = 'file/foto/large/';
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
      let div = $("<div/>",{class:'fotoDiv'}).css({"width":w,"height":w,"background-image": "url("+folder+item.file+")"});
      let overlay = $("<div/>",{class:'fotoOverlay animated'}).html('<i class="bi bi-arrows-fullscreen text-white"></i>').appendTo(div);
      div.appendTo('.fotoWrap')
      div.on('click', () => {
        $("#divImgOrig").css({"background-image":"url(file/foto/orig/"+item.file+")"});
        $("#fotoModal").fadeIn('fast');
        $("#closeModal").on('click', (e) => {
          e.preventDefault();
          $("#fotoModal").fadeOut('fast');
        })
        $("#downloadImg").attr("href","file/foto/orig/"+item.file);
        $("#delImg").on('click', (e) => {
          e.preventDefault();
          dati={id:item.id, scheda:item.scheda, file:item.file}
          if (window.confirm("Sei sicuro di voler eliminare l'immagine? Se confermi l'immagine verrà definitivamente eliminata dal server e non sarà più possibile recuperarla.")) {
            delImg(dati);
          }
        })
      })
    });
    checkScheda(scheda)
  })
  .fail(function (jqXHR, textStatus, error) {
    console.log("Post error: " + error);
  });
  checkScheda(scheda,initChart)
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
