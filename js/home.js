$(document)
.ajaxStart(function(){console.log('...loading');})
.ajaxStop(function(){$("#loadingDiv").remove();});
createCarousel()
statHome();
initMiniGallery();
mapInit();

$.ajax({ url: 'api/home.php', type: 'POST', dataType: 'json', data: {trigger: 'ogtdStat'}}).done(ogtdStat);
$.ajax({ url: 'api/home.php', type: 'POST', dataType: 'json', data: {trigger: 'nuCronoStat'}}).done(nuCronoStat);

$("[name=updateMiniGallery]").on('click', initMiniGallery)

function statHome(){
  $.ajax({url:'api/home.php',type:'POST',dataType:'json',data:{trigger:'statHome'}})
  .done(function(data){
    $("#numRa").text(data.ra)
    $("#numNu").text(data.nu)
    $("#numImg").text(data.foto)
    $("#numStereo").text(data.stereo)
    $("#num3d").text(data.modelli)
  })
}

function initMiniGallery(){
  $.ajax({ url: 'api/home.php', type: 'POST', dataType: 'json', data: {trigger: 'miniGallery'}}).done(miniGallery);
}

function ogtdStat(dataset){
  const ctx = $('#ogtdStat');
  let labels = [];
  let items = [];
  dataset.forEach(function(i,v){
    labels.push(i.ogtd);
    items.push(i.count)
  })
  const config = {
    type: 'bar',
    data: {
      datasets:[{
        data:items,
        borderColor:'rgb(198,156,85)' ,
        backgroundColor: 'rgba(198,156,85,.5)',
        borderWidth: 2
      }],
      labels:labels,
    },
    options: {
      plugins:{legend:{display:false}},
      indexAxis: 'y',
      responsive:true
    }
  }
  const ogtdChart = new Chart(ctx, config);
}

function nuCronoStat(dataset){
  const ctx = $('#nuCronoStat');
  let labels = [];
  let items = [];
  dataset.forEach(function(i,v){
    labels.push(i.cronologia);
    items.push(i.count)
  })
  const config = {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        data: items,
        fill: false,
        borderColor: 'rgb(75, 192, 192)',
        tension: 0.1
      }]
    },
    options: {
      plugins:{legend:{display:false}},
      indexAxis: 'y',
      responsive:true
    }
  }
  const nuCronoChart = new Chart(ctx, config);
}

function miniGallery(foto){

  let content = $("#miniGallery > .card-body");
  content.html('');
  foto.forEach(function(i){
    let div = $("<div/>",{class:'itemFotoContent bg-light border rounded'}).appendTo(content).on('click', function(){window.location.href = 'schedaView.php?get='+i.id});
    $("<span/>").text(i.ogtd).appendTo(div)
    $("<div/>",{class:'itemFoto'}).css("background-image", "url("+fotoPath+i.file+")").appendTo(div)
  })
}

function mapInit(){
  var map = L.map('map')
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
        if(m.comune){pop += "<p>"+m.comune+"</p>";}
        if(m.via){pop += "<p>"+m.via+"</p>";}
        files = m.file.replace('{','').replace('}','').split(',');
        pop += "<div>";
        files.forEach(function(item,i){
          if(item !== 'NULL'){
            pop += "<img src='"+fotoPath+item+"' class='img-responsive'>";
          }
        })
        pop += "</div>";
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

tagWrap()
function tagWrap(){
  let tagContainer = $("#tagWrap > .card-body");
  let tagArr = [
    "abbigliamento 1998",
    "animali 2431",
    "arredo 177",
    "decoro_geometrico 516",
    "divinità 486",
    "edifici 64",
    "figure 2621",
    "floreali 763",
    "frutta 683",
    "mezzi_di_trasporto 175",
    "oggetti 1437",
    "pers_mitologici 636",
    "persone 8016",
    "scritte 306",
    "strumenti 282",
    "vegetali 1206"
  ];
  tagArr.forEach((item, i) => {
    $("<button/>",{type:'button', class:'btn btn-outline-marta m-1', value:item}).text(item).appendTo(tagContainer);
    console.log(item);
  });

}
