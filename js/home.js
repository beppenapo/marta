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
    $("<div/>",{class:'itemFoto'}).appendTo(div)
  })
}

function mapInit(){
  var map = L.map('map');
  map.setMinZoom(map.getZoom());
  let osm = L.tileLayer(osmTile, {attribution: osmAttrib});
  osm.addTo(map)
  let comune = L.featureGroup().addTo(map);

  $.getJSON( 'api/geom.php',{ trigger: 'getComune', id:0})
    .done(function( json ) {
      console.log(json.features);
      let l = L.geoJson(json).addTo(comune);
      map.fitBounds(l.getBounds());
    })
    .fail(function( jqxhr, textStatus, error ) {
      console.log("Request Failed: " + jqxhr+", "+textStatus + ", " + error );
    });

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
