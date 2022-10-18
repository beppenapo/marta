let svgOpt = {
  // viewportSelector: '.svg-pan-zoom_viewport'
  // , panEnabled: true
  controlIconsEnabled: true
  // , zoomEnabled: true
  // , dblClickZoomEnabled: true
  // , mouseWheelZoomEnabled: true
  // , preventMouseEventsDefault: true
  // , zoomScaleSensitivity: 0.2
  // , minZoom: 0.5
  // , maxZoom: 10
  , fit: true
  // , contain: false
  // , center: true
  // , refreshRate: 'auto'
  // , beforeZoom: function(){}
  // , onZoom: function(){}
  // , beforePan: function(){}
  // , onPan: function(){}
  // , onUpdatedCTM: function(){}
  // , customEventsHandler: {}
  // , eventsListenerElement: null
}
$(document).ready(function() {
  loadSvg('img/piante/piano1.svg');
});
function loadSvg(svg){
  $("#svgWrap").load(svg,{},function(){
    let svgEl = svgPanZoom('#pianta', svgOpt);
    $("#pianta #sale>path").each(function(i,v){
      // $(this).on({
      //   mouseenter: function () {
      //     $(this).css({'fill': 'rgb(198,156,85)'});
      //   },
      //   mouseleave: function () {
      //     $(this).css({'fill': '#ffffff'});
      //   }
      // });
    })
  });
}
