const osmTile = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
const osmAttrib='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
const gStreetTile = 'http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}';
const gSatTile = 'http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}';
const gHybridTile = 'http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}';
const gTerrainTile = 'http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}';
const gSubDomains = ['mt0','mt1','mt2','mt3']
const bingKey = "Arsp1cEoX9gu-KKFYZWbJgdPEa8JkRIUkxcPr8HBVSReztJ6b0MOz3FEgmNRd4nM";
const thunderFKey = "f1151206891e4ca7b1f6eda1e0852b2e";
const thunderFTile = 'https://tile.thunderforest.com/outdoors/{z}/{x}/{y}.png?apikey='+thunderFKey;
const thunderFAttrib = 'Maps &copy; <a href="https://www.thunderforest.com">Thunderforest</a>, Data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap contributors</a>';
const pugliaExt = [[39.78964141975925,14.934096354177731],[42.22655307189329,18.520381599098922]];

const legenda = document.getElementById('scaleDiv');

const grades = [0, 10, 50, 100, 500, 1000, 1000000];
const colors = ['#ffffb2', '#fecc5c', '#fd8d3c', '#f03b20', '#bd0026','#800026'];

let comuniList = [];
let countReperti = [];
let filters = {"principale":true}

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
