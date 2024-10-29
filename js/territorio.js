$(document).ajaxStart(showLoading).ajaxStop(hideLoading);
let comuni, markers;
const ITEMS_PER_PAGE = 24;
const FOTO = "http://91.121.82.80/marta/file/foto/";

const galleryDiv = document.getElementById('mapGallery');
const WRAP = document.getElementById('totalItems');

const input = document.getElementById('geocoderInput');
const resultsContainer = document.getElementById('geocoderResult');
const legenda = document.getElementById('scaleDiv');
legenda.innerHTML = '';

const grades = [0, 10, 50, 100, 500, 1000, 1000000];
const colors = ['#ffffb2', '#fecc5c', '#fd8d3c', '#f03b20', '#bd0026','#800026'];

let totalPagesKnown = false;
let totalPages = 0;
let currentPage = 1;
let itemsLoaded = 0;
let isLoading = false;
let comuniList = [];
let countReperti = [];
let filters = {"principale":true}


const geoCardTemplate = document.createElement('template');
geoCardTemplate.innerHTML = `
<div class="geoCard">
  <div class="img"></div>
  <div class="text">
    <h6 class="card-title"></h6>
    <p class="card-text"></p>
  </div>
  <div class="card-footer">
    <a href="" class="btn btn-sm btn-marta text-white card-url">
      <i class="fa-solid fa-link"></i> scheda
    </a>
  </div>
</div>
`;
mapInit()

$("#closeGallery").on('click', function(){
  $("#mapGallery").addClass('invisible')
  WRAP.innerHTML='';
  resetGallery()
})


function mapInit(){
  map = L.map('map',{/*maxBounds:pugliaExt,*/zoomControl: false})
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
  markers = L.markerClusterGroup();
  
  $.getJSON( 'api/geom.php',{ trigger: 'getComune', id:0}).done(function( json ) {
    json.features.forEach(function(feature) { countReperti.push(feature.properties.count) });
    const colori = chroma.scale(colors).colors(grades.length-1);
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

    let poligoni = L.geoJson(json, {
      style: styleComuni,
      onEachFeature: onEachPolygon
    }).addTo(comuni);
    map.fitBounds(poligoni.getBounds());

    input.addEventListener('input', function() {
      const query = input.value.toLowerCase();
      // Filtra i Comuni per la query
      const results = comuniList.filter(comune => comune.comune.toLowerCase().includes(query));
      
      // Mostra i risultati sotto l'input
      resultsContainer.innerHTML = '';
      results.forEach(comune => {
        const resultItem = document.createElement('button');
        resultItem.classList.add('list-group-item','result-item');
        resultItem.textContent = comune.comune+" ("+comune.count+ ")";

        // Evento click per zoomare e selezionare il poligono
        resultItem.addEventListener('click', function() {
          filters['comune']=comune.id;
          zoomToPoly(comune.layer)
          mapGallery()
          input.value =  comune.comune+" ("+comune.count+ ")";
          resultsContainer.innerHTML = '';
        });

        resultsContainer.appendChild(resultItem);
      });
    });
  })
  .fail(function( jqxhr, textStatus, error ) {
    console.log("Request Failed: " + jqxhr+", "+textStatus + ", " + error );
  });
  overlay["Comuni"]=comuni;

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
  L.control.layers(baseLayers, overlay, {position: 'topright'}).addTo(map);

  map.on('zoom', (e) => { map.getZoom() >= 15 ? comuni.remove() : comuni.addTo(map) })

  $("#myZoomIn").on('click', function(){map.zoomIn()})
  $("#myZoomOut").on('click', function(){map.zoomOut()})
  $("#myZoomReset").on('click', function(){
    resetGallery()
    map.fitBounds(comuni.getBounds());
  })  
}

function onEachPolygon (feature, layer){
  comuniList.push({
    id: feature.properties.id,
    comune: feature.properties.comune,
    count: feature.properties.count,
    layer: layer
  });
    
  layer.on('click', function(){
    input.value = feature.properties.comune + " ("+feature.properties.count+")";
    filters['comune'] = feature.properties.id
    zoomToPoly(layer)
    mapGallery()
  });
}

function zoomToPoly(poly){
  comuni.eachLayer(function(layer) {
    map.removeLayer(layer)
    layer.setStyle({ opacity: 0, fillOpacity: 0});
  });
  map.addLayer(poly)
  poly.setStyle({ opacity: 1, fillOpacity: 0.2});
  map.fitBounds(poly.getBounds());
}

function styleComuni(feature) {
  return {
    fillColor: getColor(feature.properties.count),
    weight: 1,
    opacity: 1,
    color: getColor(feature.properties.count),
    fillOpacity: 0.4
  };
}

function getColor(count) {
  if (count < 10) return colors[0];
  else if (count < 50) return colors[1];
  else if (count < 100) return colors[2];
  else if (count < 500) return colors[3];
  else if (count < 1000) return colors[4];
  else return colors[5]; 
}

function resetGallery(){
  input.value='';
  comuni.eachLayer(function(layer) {
    map.addLayer(layer)
    layer.setStyle({ opacity: 1, fillOpacity: 0.2});
  });
}

async function mapGallery(){
  try {
    WRAP.innerHTML=''
    currentPage = 1;
    itemsLoaded = 0;
    await gallery({"comune":filters.comune, "principale":true});
    $("#mapGallery").removeClass('invisible');
  } catch (error) {
    console.error('Errore:', error);
  }
}

// Nascondi la lista risultati al clic esterno
document.addEventListener('click', function(event) {
  const isClickInside = resultsContainer.contains(event.target) || input.contains(event.target);
  if (!isClickInside) { resultsContainer.innerHTML = ''; }
});

["wheel", "mousedown", "mouseup"].forEach(eventType => {
  galleryDiv.addEventListener(eventType, (event) => {
    if (eventType === "wheel") {
      event.stopPropagation();
    } else if (eventType === "mousedown") {
      if (map.dragging) {
        map.dragging.disable();
      } else {
        map.setOptions({ draggable: false }); // Per Google Maps
      }
    } else if (eventType === "mouseup") {
      if (map.dragging) {
        map.dragging.enable();
      } else {
        map.setOptions({ draggable: true });
      }
    }
  });
});

WRAP.addEventListener('scroll',()=>{
  if (WRAP.scrollTop + WRAP.clientHeight >= WRAP.scrollHeight - 100) {
    if (totalPagesKnown && currentPage < totalPages) {
      gallery(filters)
    }
  }
})