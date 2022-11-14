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
let wrap = $("#findWrap");
let wrapWidth = wrap.width();
let itemMeasure = parseInt((wrapWidth / 5)-4);
let schede=[];
let pagenumber = 0;
let perpage = 20;
$(document).ready(function() {
  createCarousel()
  loadSvg('img/piante/piano1.svg');
  getReperti('piano',{piano:1});
  $("[name=piani]").on('click', function(){
    let title;
    let p = $(this).val()
    $("#fuoriVetrinaTxt").hide();
    loadSvg('img/piante/piano'+p+'.svg');
    getReperti('piano',{piano:p});
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
          let piano = $("[name=piani]:checked").val()
          let dati = {piano:piano,sala:$(this).prop('id').slice(1)};
          getReperti('sala',dati);
        }
      });
    })

    $("#pianta #vetrine g > g, #pianta #scaffali g").each(function(i,v){
      $(this).on({
        mouseenter: function(){
          $(this).find('path, rect').addClass('contenitoreHover');
        },
        mouseleave: function(){
          $(this).find('path, rect').removeClass('contenitoreHover');
        },
        click: function(){
          $("#pianta *").removeClass('salaActive').removeClass('contenitoreActive')
          $(this).find('path, rect').addClass('contenitoreActive');
          let piano = $("[name=piani]:checked").val()
          let sala = $(this).prop('id').split('_')[0].slice(1);
          let contenitore = $(this).prop('id').split('_').pop().slice(1);
          let dati = {piano:piano, sala:sala, contenitore:contenitore}
          getReperti('contenitore',dati);
        }
      });
    })
  });
}

function getReperti(el,v){
  window.scrollTo(500,500);
  wrap.html('');
  schede=[];
  pagenumber = 0;
  $.ajax({
    type: "POST",
    url: "api/sale.php",
    dataType: 'json',
    data: {trigger: 'getReperti', dati:v}
  })
  .done(function(data){
    info = {sale:data.sale.count, contenitori:data.contenitori.count, reperti:data.schede.length}
    if(data.schede.length > 0){
      if(el == 'sala'){info.sala=data.schede[0].sala;}
      if(el == 'contenitore'){
        info.sala=data.schede[0].sala;
        info.contenitore=data.schede[0].contenitore;
      }
    }
    data.schede.forEach(function(item,i){ schede.push(item); });
    setInfo(el,v,info)
    // loadGallery()
  })
}

function setInfo(el,v,info){
  console.log({el,v,info});
  $("#resTitle, #resSubTitle").html('')
  let title, text, tipo, contenitori, reperti;
  if (info.reperti == 0) {
    $("<div/>",{class:'alert alert-warning mx-auto text-center'}).text('Non sono presenti reperti schedati per la sala o il contenitore selezionati.').appendTo(wrap);
    return false;
  }
  switch (true) {
    case v.piano == -1: title = 'deposito'; break ;
    case v.piano == 0: title = 'piano terra'; break ;
    case v.piano == 1: title = 'primo piano'; break ;
    case v.piano == 2: title = 'secondo piano'; break ;
  }
  if (v.piano == -1) {
    contenitori = parseInt(info.contenitori);
    tipo = contenitori == 1 ? 'scaffale' : 'scaffali';
  }else {
    contenitori = parseInt(info.contenitori - 1);
    //escludo i fuori vetrina
    tipo = contenitori == 1 ? 'vetrina' : 'vetrine';
  }

  reperti = parseInt(info.reperti) == 1 ? parseInt(info.reperti) + ' reperto' : parseInt(info.reperti) + ' reperti'

  if (contenitori == 0) {reperti += ' fuori vetrina';}

  if (el == 'piano') {
    text = "Il piano selezionato è suddiviso in <span class='font-weight-bold'>" + info.sale + " stanze</span> nelle quali sono presenti <span class='font-weight-bold'>"+ contenitori +" "+ tipo +"</span> per un totale di <span class='font-weight-bold'>"+ reperti + '</span>';
  }

  if (el == 'sala') {
    title += ', sala '+info.sala;
    text = "Nella sala selezionata sono presenti <span class='font-weight-bold'>"+ contenitori +" "+ tipo +"</span> per un totale di <span class='font-weight-bold'>"+ reperti + '</span>';
  }
  if (el == 'contenitore') {
    let el = v.piano == -1 ? 'scaffale' : 'vetrina';
    let incipit = v.piano == -1 ? 'Nello scaffale selezionato' : 'Nella vetrina selezionata';
    title += ', sala '+info.sala+', '+el+' '+info.contenitore;
    text = incipit + " sono presenti <span class='font-weight-bold'>"+ reperti + '</span>';
  }
  $("#resTitle").html(title);
  $("#resSubTitle").html(text)
  setTimeout(loadGallery(),500)

}



function loadGallery(){
  let currentDataset = schede.slice(pagenumber * perpage, (pagenumber * perpage) + perpage);
  if (currentDataset.length > 0){
    currentDataset.forEach(function(item){
      let div = $("<div/>",{class:'item bg-white shadow', title:'visualizza scheda'})
      .attr({"data-toggle":'tooltip'})
      .css({
        "width":itemMeasure
        , "height":itemMeasure
        , "background-image": "url(img/icone/ogtd/"+item.classe_id+".png)"
      })
      .on('click', function(){window.location.href = 'schedaView.php?get='+item.id})
      .appendTo(wrap)
      let legenda = $("<div/>",{class:'text-center legenda'}).appendTo(div)
      $("<small/>",{class:'d-block'}).text('Piano: '+item.piano+' Sala: '+item.sala).appendTo(legenda)
      $("<small/>",{class:'d-block'}).text(item.classe).appendTo(legenda)
      $("<h5/>",{class:'p-0'}).text(item.ogtd).appendTo(legenda)
    });
  }
}
window.addEventListener('scroll',()=>{
  if(window.scrollY + window.innerHeight >= document.documentElement.scrollHeight){
    pagenumber++;
    loadGallery();
  }
})
