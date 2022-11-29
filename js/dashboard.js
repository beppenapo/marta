let biblioList = schedeList=[];
let content =  $("#scrollSchede");
let contentBiblio =  $("#scrollBiblio");
let scrollDiv =  document.getElementById("scrollSchede");
let scrollBiblioDiv =  document.getElementById("scrollBiblio");
let href = 'schedaView.php?get=';
let urlIco = '<i class="fas fa-link"></i>';
let pagenumber = pageBiblio = 0;
let perpage = 50;
let resetBiblio = false;
const userClass = parseInt($("[name=classe]").val());
const utente = parseInt($("[name=utente]").val());

if (userClass == 3) { schede({tipo:10, operatore:utente}) }
if (userClass == 1) { schede({tipo:0}) }

biblio([]);
initComunicazioni();
mapInit();
buildUserTable();
if (userClass !== 3) { statoSchede(); }

$(".btn-toolbar [name=cerca]").on('click', function(){
  let filter = {}
  $("[name=checkBtn]").each(function(i,btn){
    if ($(this).is('.active')) { filter['tipo'] = $(this).val() }
  })
  $("[name=filterBtn]").each(function(i,el){
    if ($(this).val()) {
      let key = $(this).prop('id').split('-').pop();
      let val = $(this).val();
      filter[key]=val;
    }
  })
  if (Object.keys(filter).length === 0) {
    alert('per effettuare una ricerca devi inserire almeno 1 parametro tra quelli disponibili');
    return false;
  }
  console.log(filter);
})

$("[name=searchBiblioBtn]").on('click', function(){
  let dati = {}
  let aut = $("[name = biblioAut]");
  let title = $("[name = biblioTitle]");
  let tipo = $("[name = biblioTipo]");
  if (aut.val()) {dati.autore = aut.val()}
  if (title.val()) {dati.titolo = title.val()}
  if (tipo.val()) {dati.tipo = tipo.val()}
  if(Object.keys(dati).length === 0){
    alert("Per cercare un record bibliografico devi inserire almeno un filtro di ricerca!");
    return false;
  }
  biblio(dati);
  if (resetBiblio == false) {
    resetBiblio = true;
    $("<button/>",{class:'btn btn-sm btn-outline-secondary', type:'button', name:'resetBiblioBtn'}).html('<i class="fas fa-repeat"></i>').appendTo('#searchBiblioToolbar').on('click',function(){
      biblio([]);
      $(".filtroBiblio").val('');
      $(this).remove();
      resetBiblio = false;
    })
  }
})

$("[name=usrBtn]").on('click', function(){
  $("[name = searchNctn], [name = searchInv]").removeClass('is-invalid');
  $("[name=checkBtn],[name = usrBtn]").removeClass('active');
  $(this).addClass('active')
  let dati = {tipo:11,operatore: $(this).val()}
  schede(dati)
})

$("[name=checkBtn]").on('click', function(){
  $("[name = searchNctn], [name = searchInv]").removeClass('is-invalid');
  $("[name=checkBtn],[name = usrBtn]").removeClass('active');
  let tipo = $(this).val();
  let dati = {tipo:tipo}
  if(tipo == 8){
    if (!$("[name = searchNctn]").val()) {
      $("[name = searchNctn]").addClass('is-invalid')
      return false;
    }
    $("[name = searchNctn]").removeClass('is-invalid')
    dati.nctn = $("[name = searchNctn]").val()
  }
  if(tipo == 9){
    if (!$("[name = searchInv]").val()) {
      $("[name = searchInv]").addClass('is-invalid')
      return false;
    }
    $("[name = searchInv]").removeClass('is-invalid')
    dati.inv = $("[name = searchInv]").val()
  }
  if ($(this).is('.checkBtn')){$("[name = searchNctn], [name = searchInv]").val('')}
  if ($(this).is('.searchNctnBtn')){$("[name = searchInv]").val('')}
  if ($(this).is('.searchInvBtn')){$("[name = searchNctn]").val('')}
  $(this).addClass('active')
  schede(dati);
})

$("[name=addComunicazioneBtn]").on('click', function(){
  $("[name=testo]").val('');
  $("[name=idComunicazione]").val('');
  $("#addComunicazioneDiv").modal('show');
});
$("[name=saveComunicazione]").on('click', function(e){
  isvalidate = $("[name=addComunicazioneForm]")[0].checkValidity()
  if (isvalidate) {
    e.preventDefault();
    dati = !$("[name=idComunicazione]").val() ? {trigger : 'addComunicazione', testo:$("[name=testo]").val()} : {trigger : 'editComunicazione', id:$("[name=idComunicazione]").val(), testo:$("[name=testo]").val()}
    $.ajax({ url: 'api/dashboard.php', type: 'POST', dataType: 'json', data: dati })
    .done(function(data) {
      alert('nota salvata correttamente');
      $('#addComunicazioneDiv').modal('hide');
      initComunicazioni();
    })
    .fail(function() {console.log("errore nel salvataggio della comunicazione"); });
  }
})
//CBAEURSKUZBYXE
$('body').on('click', '[name=updateUser]', function() { $.redirectPost('usrAdd.php',{id:$(this).val()}); });
$('body').on('click', 'a.schedatore', function(e) {
  e.preventDefault();
  localStorage.setItem('operatore',JSON.stringify([$(this).data('id'),$(this).data('schedatore')]));
  $.redirectPost('schede.php');
});

function schede(dati){
  content.html('');
  schedeList=[];
  ogtdList=[];
  pianoList=[];
  pagenumber=0;
  $.ajax({
    type: "POST",
    url: "api/dashboard.php",
    dataType: 'json',
    data: {trigger: 'schede', dati:dati}
  })
  .done(function(data){
    console.log(data);
    if(data.length == 0) {
      content.html('<h6 class="text-center my-5">nessuna scheda trovata</h6>')
      return false;
    }

    data.forEach(function(item,i){
      schedeList.push(item);
      ogtdList.push({id:item.ogtdid, ogtd:item.ogtd})
      pianoList.push({piano:item.piano})
    });
    buildSelectFiltri()
    scrollSchede()
    scrollDiv.addEventListener('scroll',function(e){
      var lastDiv = document.querySelector(".colonneSchede:last-child");
      var lastDivOffset = lastDiv.offsetTop + lastDiv.clientHeight;
      var pageOffset = scrollDiv.scrollTop + scrollDiv.clientHeight;
      if (pageOffset > lastDivOffset - 10) {
        pagenumber++;
        scrollSchede();
      }
    })
    $("#numSchedeCard").text(data.length)
  })
}

function buildSelectFiltri(){
  const ogtd = $("#filter-ogtd");
  const piano = $("#filter-piano");

  let ogtdArr = [...new Map(ogtdList.map(item => [item['id'], item])).values()];
  ogtdArr.sort((a, b) => {let fa = a.ogtd.toLowerCase(), fb = b.ogtd.toLowerCase(); if (fa < fb) { return -1; } if (fa > fb) { return 1; } return 0;});

  let pianoArr = [...new Map(pianoList.map(item => [item['piano'], item])).values()];
  pianoArr.sort((a, b) => {let fa = a.piano, fb = b.piano; if (fa < fb) { return -1; } if (fa > fb) { return 1; } return 0;});

  $("#filtra_schede select").html('');

  $("<option/>").val('').text('ogtd').prop("selected", true).appendTo(ogtd)
  $("<option/>").val('').text('piano').prop("selected", true).appendTo(piano)

  ogtdArr.forEach((item, i) => { $("<option/>").val(item.id).text(item.ogtd).appendTo(ogtd) });
  pianoArr.forEach((item, i) => { $("<option/>").val(item.piano).text(item.piano).appendTo(piano) });
}

function scrollSchede(){
  let currentDataset = schedeList.slice(pagenumber * perpage, (pagenumber * perpage) + perpage);
  if (currentDataset.length > 0){
    currentDataset.forEach(function(item){
      let li = $("<li/>",{class:'list-group-item colonne colonneSchede'}).appendTo(content)
      let piano;
      switch (item.piano) {
        case -1: piano = 'Deposito'; break;
        case 0: piano = 'Piano terra'; break;
        case 1: piano = 'Primo piano'; break;
        case 2: piano = 'Secondo piano'; break;
        case 3: piano = 'Terzo piano'; break;
      }
      $("<span/>").text(item.nctn).appendTo(li)
      $("<span/>").text(item.inventario).appendTo(li)
      $("<span/>").text(item.titolo).appendTo(li)
      $("<span/>").text(item.ogtd).appendTo(li)
      $("<span/>").text(piano).appendTo(li)
      $("<span/>").text(item.sala).appendTo(li)
      $("<span/>").text(item.cassetta).appendTo(li)
      let link = $("<span/>").appendTo(li)
      $("<a/>",{href:href+item.scheda, title:'apri scheda'}).attr("data-toggle","tooltip").html(urlIco).appendTo(link)
    });
  }
}

function biblio(dati){
  contentBiblio.html('');
  schedeBiblio=[];
  pageBiblio = 0;
  $.ajax({
    type: "POST",
    url: "api/dashboard.php",
    dataType: 'json',
    data: {trigger: 'biblio', dati:dati}
  })
  .done(function(data){
    if(data.length == 0) {
      contentBiblio.html('<h6 class="text-center my-5">nessun record bibliografico corrisponde ai tuoi criteri di ricerca</h6>')
      return false;
    }
    data.forEach(function(item,i){ schedeBiblio.push(item); });
    scrollBiblio()
    scrollBiblioDiv.addEventListener('scroll',function(e){
      var lastDiv = document.querySelector(".colonneBiblio:last-child");
      var lastDivOffset = lastDiv.offsetTop + lastDiv.clientHeight;
      var pageOffset = scrollBiblioDiv.scrollTop + scrollBiblioDiv.clientHeight;
      if (pageOffset > lastDivOffset - 10) {
        pageBiblio++;
        scrollBiblio();
      }
    })
  })
}

function scrollBiblio(){
  let currentDataset = schedeBiblio.slice(pageBiblio * perpage, (pageBiblio * perpage) + perpage);
  if (currentDataset.length > 0){
    currentDataset.forEach(function(item){
      let li = $("<li/>",{class:'list-group-item colonne colonneBiblio'}).appendTo(contentBiblio)
      $("<span/>").text(item.id).appendTo(li)
      $("<span/>").text(item.tipo).appendTo(li)
      $("<span/>").text(item.autore).appendTo(li)
      $("<span/>").text(nl2br(item.titolo)).appendTo(li)
      let link = $("<span/>").appendTo(li)
      $("<a/>",{href:href+item.id, title:'apri scheda'}).attr("data-toggle","tooltip").html(urlIco).appendTo(link)
    });
  }
}

function initComunicazioni(){
  $.ajax({ url: 'api/dashboard.php', type: 'POST', dataType: 'json', data: {trigger : 'comunicazioni'} })
  .done(function(data) {
    let sex = parseInt(localStorage.getItem('sex'));
    wrap = $("#comunicazioni>.list-group");
    wrap.html('');
    data.forEach(function(item,index){
      div = $("<div/>",{class:'list-group-item flex-column align-items-start'}).appendTo(wrap);
      $("<p/>", {class:'mb-1'}).html(nl2br(item.testo)).appendTo(div);
      footer = $("<div/>",{class:'d-flex w-100 justify-content-between'}).appendTo(div);
      $("<small/>").html(item.data).appendTo(footer);
      btnWrap = $("<div/>",{class:'d-flex w-100 justify-content-start mt-3'}).appendTo(div);
      $("<button/>",{type:'button', class:'btn btn-sm btn-light text-dark mr-2 p-1', name:'editComunicazione'})
        .html('<small>modifica</small>')
        .appendTo(btnWrap)
        .on('click', function(){
          $("[name=testo]").val(item.testo);
          $("[name=idComunicazione]").val(item.id);
          $("#addComunicazioneDiv").modal('show');
        });
      $("<button/>",{type:'button', class:'btn btn-sm btn-light text-dark', name:'delComunicazione'})
        .html('<small>elimina</small>')
        .appendTo(btnWrap)
        .on('click', function(){
          if(confirm('vuoi davvero eliminare la nota?')){
            $.ajax({url: 'api/dashboard.php', type: 'POST', dataType: 'json', data: {trigger: 'delComunicazione', id:item.id}})
            .done(function(data) {
              alert('nota eliminata definitivamente')
              initComunicazioni()
            })
            .fail(function(data) {
              alert("errore durante l'eliminazione della nota");
            });
          }
        });
    });
  })
  .fail(function() {console.log("errore caricamento comunicazioni"); });
}

function buildUserTable(){
  $.ajax({ url: 'api/usr.php', type: 'POST', dataType: 'json', data: {trigger: 'getUser'} })
  .done(function(data) {
    data.forEach(function(v,i){
      act = v.attivo == true ? "<i class='fas fa-check-circle text-success' data-toggle='tooltip' title='attivo'></i>" : "<i class='fas fa-times-circle text-danger' data-toggle='tooltip' title='non attivo'></i>";
      classe = '<i class="fas '+v.ico+'" data-toggle="tooltip" title="'+v.classe+'"></i>';
      btnUpdate = $("<button/>", {class:'btn btn-light btn-sm', type:'button', name:'updateUser', value:v.id}).html("<i class='fas fa-edit' data-toggle='tooltip' title='modifica utente'></i>");
      tr = $("<tr/>").appendTo('#dataTable');
      $("<td/>",{text:v.cognome+" "+v.nome}).appendTo(tr);
      $("<td/>",{text:v.email}).appendTo(tr);
      $("<td/>",{text:v.cellulare}).appendTo(tr);
      if (userClass !== 3) {
        $("<td/>",{html:classe, class:'text-center'}).appendTo(tr);
        $("<td/>",{html:act, class:'text-center'}).appendTo(tr);
        $("<td/>",{html:btnUpdate, class:'text-center'}).appendTo(tr);
      }
    })
    initDataTab('#dataTable')
  })
  .fail(function() {console.log("error");});
}

function statoSchede(){
  $.ajax({
    url: 'api/dashboard.php',
    type: 'POST',
    dataType: 'json',
    data: {trigger: 'statoSchede'}
  })
  .done(statoChart);
}

function statoChart(dataset){
  item = dataset[0];
  const ctx = $('#statoChart');
  const labels = ['Aperte','Chiuse','Verificate','Inviate','Accettate'];
  const data = {
    labels: labels,
    datasets: [{
      data: [item.aperta, item.chiusa, item.verificata, item.inviata, item.accettata],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        'rgba(255, 205, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(54, 162, 235, 0.2)'
      ],
      borderColor: [
        'rgb(255, 99, 132)',
        'rgb(255, 159, 64)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(54, 162, 235)'
      ],
      borderWidth: 1
    }]
  };
  const config = {
    type: 'bar',
    data: data,
    options: {
      plugins:{legend:{display:false}},
      scales: {y: {beginAtZero: true}},
      responsive:true
    },
  };
  const myChart = new Chart(ctx, config);
}

function buildSchedeTable(){
  let dati = {trigger:'statoSchede'}
  if (userClass == 3) {dati.cmpn = utente;}
  let opt={ url: 'api/dashboard.php', type: 'POST', dataType: 'json', data: dati }
  $.ajax(opt)
  .done(function(data) {
    data.forEach(function(v,i){
      let vero = "<i class='fas fa-check-circle text-success'></i>";
      let falso = "<i class='fas fa-times-circle text-danger'></i>";
      let link = $("<a/>", {href:'schedaView.php?get='+v.scheda}).html("<i class='fas fa-arrow-right'></i>");
      tr = $("<tr/>").appendTo('#dataTableScheda');
      $("<td/>",{text:v.nctn}).appendTo(tr);
      $("<td/>",{text:v.titolo}).appendTo(tr);
      $("<td/>",{class:'text-center'}).html(v.chiusa == true ? vero : falso).appendTo(tr);
      $("<td/>",{class:'text-center'}).html(v.verificata == true ? vero : falso).appendTo(tr);
      $("<td/>",{class:'text-center'}).html(v.inviata == true ? vero : falso).appendTo(tr);
      $("<td/>",{class:'text-center'}).html(v.accettata == true ? vero : falso).appendTo(tr);
      $("<td/>",{text:v.cmpd}).appendTo(tr);
      $("<td/>",{class:'text-center',html:link}).appendTo(tr);
    })
    initDataTab('#dataTableScheda')
  })
  .fail(function(xhr, ajaxOptions, thrownError) {
    console.log("boh!"+[xhr, ajaxOptions, thrownError]);
  });
}
const userDataOpt = {
  paging: true,
  lengthMenu: [5,10, 20, 50, 100],
  order: [],
  columnDefs: [{targets  : 'no-sort', orderable: false }],
  destroy:true,
  retrieve:true,
  responsive: true,
  html:true,
  language: { url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Italian.json' }
}
function initDataTab(tab){ $(tab).DataTable(userDataOpt); }


function mapInit(){
  var map = L.map('map',{maxBounds:pugliaExt})
  map.setMinZoom(map.getZoom());
  let osm = L.tileLayer(osmTile, {attribution: osmAttrib});
  let gStreets = L.tileLayer(gStreetTile,{maxZoom: 20, subdomains:gSubDomains });
  let gSat = L.tileLayer(gSatTile,{maxZoom: 20, subdomains:gSubDomains});
  let gTerrain = L.tileLayer(gTerrainTile,{maxZoom: 20, subdomains:gSubDomains});
  osm.addTo(map)
  var baseLayers = {
    "OpenStreetMap": osm,
    "Terrain":gTerrain,
    "Satellite": gSat,
    "Google Street": gStreets
  };
  var overlay = {};
  let comune = L.featureGroup().addTo(map);
  var markers = L.markerClusterGroup();

  // $.getJSON( 'api/geom.php',{ trigger: 'getComune', dati:{id:0,map:1}})
  $.getJSON( 'api/geom.php',{ trigger: 'getComune', id:0})
    .done(function( json ) {
      let l = L.geoJson(json).addTo(comune);
      map.fitBounds(l.getBounds());
    })
    .fail(function( jqxhr, textStatus, error ) {
      console.log("Request Failed: " + jqxhr+", "+textStatus + ", " + error );
    });
  overlay["Comuni"]=comune;

  $.ajax({
    type: "GET",
    url: "api/geom.php",
    dataType: 'json',
    data: {trigger: 'getMarker'}
  })
    .done(function(data){
      data.forEach(function(m,i){
        let marker = L.marker([m.gpdpy,m.gpdpx],{
          ogtd:m.ogtd
          ,classe:m.classe
          ,via:m.via
          ,href: 'schedaView.php?get='+m.id
        });
        let pop = "<div class='text-center mapPopUp'>";
        pop += "<h5>"+m.ogtd+"</h5>";
        pop += "<p class='font-weight-bold'>"+m.classe+"</p>";
        pop += "<p>"+m.comune+"</p>";
        pop += "<p>"+m.via+"</p>";
        pop += "<hr>";
        pop += "<a href='schedaView.php?get="+m.scheda+"'>apri scheda</a>";
        pop += "</div>";
        marker.bindPopup(pop);
        markers.addLayer(marker);
      })
    })
    overlay["Reperti"]=markers;
    markers.addTo(map);
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

      btn2=$("<a/>",{href:'#', title:'zoom al comune'}).attr({"data-toggle":"tooltip","data-placement":"right"}).appendTo(container);
      $("<i/>",{class:'fa-solid fa-map-location-dot'}).appendTo(btn2)
      btn2.on('click', function (e) {
        e.preventDefault()
        map.addLayer(comune)
        map.fitBounds(comune.getBounds());
      });
      return container;
    }
  })

  map.addControl(new resetMap());
}

function statDashboard(){
  $("#totSchede").text(TOTRA+TOTNU);
  $.ajax({url:'api/home.php',type:'POST',dataType:'json',data:{trigger:'statHome'}})
  .done(function(data){
    let totSchede = parseInt(data['ra'])+parseInt(data['nu']);
    $("#numschede").text(totSchede);

    $("#percSchedeOk").text(parseInt(totSchede*100/40000));
    $("#raBar").text('RA '+data['ra']);
    $("#nuBar").text('NU '+data['nu']);

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
