let comuniList = [];
mapInit()
let geocoderList = $("#geocoderResult");
geocoderList.hide();
$("[name=geocoderBtn]").on('click', function(e){
  if( $("[name=geocoderForm]")[0].checkValidity() ){
    e.preventDefault();
    cercaVia($("[name=geocoderInput]").val());
  }
})

function mapInit(){
  map = L.map('map',{maxBounds:pugliaExt})
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
      let wrap = ("#comuniList .list-group");
      let l = L.geoJson(json, {
        onEachFeature: function (feature, layer){
          $("<button/>", {class:'list-group-item list-group-item-action', type:'button'})
            .text(feature.properties.comune)
            .appendTo(wrap)
            .on('click', function(){ map.fitBounds(layer.getBounds()) })
        }
      }).addTo(comune);
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
  map.on('zoom', (e) => {
    map.getZoom() >= 15 ? comune.remove() : comune.addTo(map)
  })
}

function cercaVia(ind){
  let string = geoApi+ind+'&format=json&addressdetails=1&bounded=1';
  geocoderList.html('');
  $.getJSON(string, function(json, textStatus) {
    if(json.length == 0){
      $("<button/>",{type:'button', class:"btn-sm list-group-item list-group-item-action disabled"}).text('nessun indirizzo trovato, riprova').appendTo(geocoderList);
      geocoderList.fadeIn('fast');
      return false;
    }
    json.forEach((item, i) => {
      $("<button/>",{type:'button', class:"btn-sm list-group-item list-group-item-action"}).text(item.display_name).appendTo(geocoderList).on('click', function(){
        geocoderList.fadeOut('fast', function() { geocoderList.html(''); });
        $("[name=geocoderInput]").val(item.display_name);
        map.fitBounds([
          [item.boundingbox[0],item.boundingbox[2]],
          [item.boundingbox[1],item.boundingbox[3]]
        ])
      });
      geocoderList.fadeIn('fast');
    });
  });
}
