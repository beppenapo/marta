$(document).ajaxStart(showLoading).ajaxStop(hideLoading);
let comuni;
let countRepertiMsg = 'clicca su un Comune per visualizzare il numeri di reperti presenti sul territorio comunale';
const countRepertiDiv = document.getElementById('countReperti')
legenda.innerHTML = '';
const colori = chroma.scale(colors).colors(grades.length-1);

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
  countRepertiDiv.innerHTML=countRepertiMsg;
  var map = L.map('map',{zoomControl:false})
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
  comuni = L.featureGroup().addTo(map);
  // var markers = L.markerClusterGroup();

  $.getJSON( 'api/geom.php',{ trigger: 'getComune', id:0}).done(function( json ) {
    json.features.forEach(function(feature) { countReperti.push(feature.properties.count) });

    for (let i = 0; i < colori.length; i++) {
      const colorDiv = document.createElement('div');
      colorDiv.classList.add('colorLegend');
      colorDiv.style.backgroundColor = colori[i];
      const textDiv = document.createElement('div');
      textDiv.classList.add('textLegend');
      const rangeLabel = document.createElement('span');
      rangeLabel.textContent = '> '+grades[i];
      textDiv.appendChild(rangeLabel);
      const container = document.createElement('div');
      container.appendChild(colorDiv);
      container.appendChild(textDiv);
      legenda.appendChild(container);
    }

    let l = L.geoJson(json,{style:styleComuni,onEachFeature: onEachComune}).addTo(comuni);
    map.fitBounds(l.getBounds());
  })
  .fail(function( jqxhr, textStatus, error ) {
    console.log("Request Failed: " + jqxhr+", "+textStatus + ", " + error );
  });
  // overlay["Comuni"]=comuni;

  // $.ajax({type: "GET", url: "api/geom.php", dataType: 'json', data: {trigger: 'getMarker'}})
  // .done(function(data){
  //   data.forEach(function(m,i){
  //     let marker = L.marker([m.gpdpy,m.gpdpx],{
  //       ogtd:m.ogtd
  //       ,classe:m.classe
  //       ,via:m.via
  //       ,href: 'schedaView.php?get='+m.id
  //     });
      // let pop = "<div class='text-center mapPopUp'>";
      // pop += "<h5>"+m.ogtd+"</h5>";
      // pop += "<p class='font-weight-bold m-0'>"+m.classe+"</p>";
      // if(m.comune){pop += "<p>"+m.comune+"</p>";}
      // if(m.via){pop += "<p>"+m.via+"</p>";}
      // files = m.file.replace('{','').replace('}','').split(',');
      // pop += "<div class='popUpImgContainer'>";
      // files.forEach(function(item,i){
      //   if(item !== 'NULL'){
      //     pop += "<img src='"+fotoPath+item+"' class='img-responsive'>";
      //   }
      // })
      // pop += "</div>";
      // pop += "<hr>";
      // pop += "<a href='schedaView.php?get="+m.scheda+"'>apri scheda</a>";
      // pop += "</div>";
      // marker.bindPopup(pop);
  //     markers.addLayer(marker);
  //   })
  // })
  
  // overlay["Reperti"]=markers;
  // markers.addTo(map);
  L.control.layers(baseLayers, overlay, {position: 'topright'}).addTo(map);

  map.on('zoom', (e) => { map.getZoom() >= 15 ? comuni.remove() : comuni.addTo(map) })

  $("#myZoomIn").on('click', function(){map.zoomIn()})
  $("#myZoomOut").on('click', function(){map.zoomOut()})
  $("#myZoomReset").on('click', function(){ 
    map.fitBounds(comuni.getBounds());
    countRepertiDiv.innerHTML=countRepertiMsg; 
  })  
}

tagWrap(tagHome)
function tagHome(data){
  let tagContainer = $("#tagWrap > .card-body");
  data.forEach((item, i) => {
    $("<a/>",{href:'#', class:'btn btn-outline-marta m-1'})
      .text(item.tag+' '+item.count)
      .appendTo(tagContainer)
      .on('click', (el)=>{
        el.preventDefault();
        localStorage.removeItem('filters');
        setFilters('principale', 'true', 'update');
        setFilters('tags',item.tag,'update')
        window.location.href = 'sfoglia.php'
      })
  });
}

function onEachComune (feature, layer){
  layer.on('click', function(){
    console.log(feature.properties);
    const item = feature.properties;
    let txt = `Comune di <strong>${item.comune}</strong>: <strong>${item.count}</strong> `;
    txt += parseInt(item.count) == 1 ? "reperto ritrovato" : "reperti ritrovati";
    countRepertiDiv.innerHTML=txt;

    comuni.eachLayer(function(layer) { layer.setStyle({ fillOpacity: 0.4 }); });
    layer.setStyle({ fillOpacity: 1});
  });
}

