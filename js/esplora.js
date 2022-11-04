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
let schede=[];
$(document).ready(function() {
  createCarousel()
  loadSvg('img/piante/piano1.svg');
  getReperti('piano',{piano:1});
  // $("#resTitle").text('primo piano');
  $("[name=piani]").on('click', function(){
    let title;
    let p = $(this).val()
    $("#fuoriVetrinaTxt").hide();
    loadSvg('img/piante/piano'+p+'.svg');
    getReperti('piano',{piano:p});
    infinityScroll()
  })
  $("#fuoriVetrinaTxt").hide();
});


/* www.flaticon.com */
function createCarousel(){
  let bgArr = [];
  const content = $(".carousel-inner");
  for (let i=0, j=BGIMG; i<j; i++) {
    let i = Math.random()*(BGIMG-1) + 1;
    i = Math.ceil(i);
    if (!bgArr.includes(i)) {bgArr.push(i);}
  }
  bgArr.forEach(function(img, i){
    let item = $("<div/>",{class:'carousel-item'}).appendTo(content);
    $("<div/>").css({"background-image":"url(img/banner/"+img+".jpg)"}).appendTo(item);
  });
  $(".carousel-item").eq(0).addClass('active');
  $('#carousel').carousel({wrap:true})
}
function loadSvg(svg){
  $("#svgWrap").load(svg,{},function(){
    let svgEl = svgPanZoom('#pianta', svgOpt);
    $("#pianta #vetrine g > g, #pianta #scaffali g").each(function(i,v){
      $(this).on({
        mouseenter: function(){
          $(this).find('path, rect').addClass('contenitoreHover');
        },
        mouseleave: function(){
          $(this).find('path, rect').removeClass('contenitoreHover');
        },
        click: function(){
          // getRepertiContenitore('contenitore',$(this).prop('id'))
          $("#pianta *").removeClass('salaActive').removeClass('contenitoreActive')
          $(this).find('path, rect').addClass('contenitoreActive');
        }
      });
    })
    $("#pianta #sale>g").each(function(i,v){
      $(this).on({
        mouseenter: function(){
          $(this).find('path, rect').addClass('salaHover');
        },
        mouseleave: function(){
          $(this).find('path, rect').removeClass('salaHover');
        },
        click: function(){
          $("#pianta *").removeClass('salaActive').removeClass('contenitoreActive')
          $(this).find('path, rect').addClass('salaActive');
          // getRepertiSala('sala',$(this).prop('id'))
        }
      });
    })
  });
}
// function getRepertiSala(el,v){
//   let piano = $("[name=piani]:checked").val()
//   let sala = el == 'sala' ? v.slice(1) : v.split('_')[0].slice(1)
//   $.ajax({
//     type: "POST",
//     url: "api/sale.php",
//     dataType: 'json',
//     data: {trigger: 'getRepertiSala', dati:{piano:piano,sala:sala}}
//   })
//   .done(function(data) {
//     console.log(data);
//     let tipoContenitore, numContenitori, subtitle, repertiTxt;
//     if (piano == -1) {
//       $("#fuoriVetrinaTxt").hide()
//       numContenitori = data.numContenitori.count;
//       tipoContenitore = data.numContenitori.count <= 1 ? 'scaffale' : 'scaffali'
//     }else {
//       $("#fuoriVetrinaTxt").show()
//       numContenitori = data.numContenitori.count - 1
//       tipoContenitore = numContenitori == 1 ? 'vetrina' : 'vetrine'
//     }
//     repertiTxt = data.numReperti.count == 1 ? 'reperto' : 'reperti'
//     subtitle = ' contiene '+data.numReperti.count+ ' '+repertiTxt;
//     if (data.numReperti.count > 0) {
//       subtitle +=  ' in '+numContenitori+' '+tipoContenitore;
//     }
//
//     $("#resTitle").text("sala "+data.nomeSala.sala)
//     $("#resSubTitle").text("la sala "+data.nomeSala.sala + subtitle);
//   });
// }
function getReperti(el,v){
  let trigger;
  switch (el) {
    case 'piano': trigger = 'getRepertiPiano'; break;
    case 'sala': trigger = 'getRepertiSala'; break;
    case 'contenitore': trigger = 'getRepertiContenitore'; break;
  }
  $.ajax({
    type: "POST",
    url: "api/sale.php",
    dataType: 'json',
    data: {trigger: trigger, dati:v}
  })
  .done(function(data){
    // console.log(data.schede);
    let info = {sale:data.numSale.count,  contenitori:data.numContenitori.count, reperti:data.numReperti.count}
    setInfo(el,v,info)
    data.schede.forEach(function(item,i){ schede.push(item) });
    infinityScroll()
  })
}

function setInfo(el,v,info){
  $("#resTitle, #resSubTitle").html('')
  // console.log(v);
  let title, text, contenitore;
  switch (true) {
    case v.piano == -1: title = 'deposito'; break ;
    case v.piano == 0: title = 'piano terra'; break ;
    case v.piano == 1: title = 'primo piano'; break ;
    case v.piano == 2: title = 'secondo piano'; break ;
  }

  $("#resTitle").html(title);
  contenitore = v.piano == -1 ? 'scaffali' : 'vetrine';
  if (el == 'piano') {
    text = "Il piano selezionato è suddiviso in <span class='font-weight-bold'>" + info.sale + " stanze</span> nelle quali sono presenti <span class='font-weight-bold'>"+ info.contenitori +" "+ contenitore +"</span> per un totale di <span class='font-weight-bold'>"+ info.reperti + ' reperti</span>';
  }

  $("#resSubTitle").html(text)
}

function infinityScroll(){
  let pagenumber = 0;
  let perpage = 21;
  let loadLock = false;
  let startPage = pagenumber + 1;
  let endPage = parseInt(schede.length / perpage);
  let wrap = $("#findWrap");
  let wrapWidth = wrap.width();
  let itemMeasure = parseInt(wrapWidth / 3);
  let currentDataset = schede.slice(pagenumber * perpage, (pagenumber * perpage) + perpage);

  if (currentDataset.length > 0){
    $("#startPage").text(startPage)
    $("#endPage").text(endPage)
    // document.getElementById('items').appendChild(pageHeader);
    currentDataset.forEach(function(item){
      console.log(item);
      $("<div/>",{class:'item'}).css({"width":itemMeasure, "height":itemMeasure}).appendTo(wrap)
    });
  }
  loadLock = false;
}
